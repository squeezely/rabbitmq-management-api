<?php


namespace Squeezely\RabbitMQ\Management\Queue;


use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Squeezely\RabbitMQ\Management\Client;
use Squeezely\RabbitMQ\Management\Response;

class QueueService extends Client {

    /**
     * @param string $name
     * @param string $vhost
     *
     * @return Queue|false
     * @throws Exception
     */
    public function getQueue(string $name, string $vhost) {
        try {
            $vhostGeneralData = $this->sendAuthenticatedRequest('queues/' . $vhost . '/' . $name);
        } catch(GuzzleException $e) {
            return false;
        }

        if(isset($vhostGeneralData['name']) && $vhostGeneralData['name'] == $name) {
            return new Queue($vhostGeneralData);
        }

        return false;
    }

    /**
     * @param string|null $vhost
     * @return Queue[]
     * @throws GuzzleException
     */
    public function getQueues(string $vhost = null) {
        if($vhost !== null) {
            $context = 'queues/' . $vhost;
        }
        else {
            $context = 'queues';
        }

        $queues = [];
        foreach($this->sendAuthenticatedRequest($context) as $queue) {
            if(!isset($this->queues[$queue['vhost']])) {
                $queues[] = new Queue($queue);
            }
        }

        return $queues;
    }

    /**
     *
     * Will return true when created or already exists, on failure returns false
     *
     * @param string $name
     * @param string $vHost
     * @param array  $additionConfig
     *
     * @return bool
     * @throws GuzzleException
     */
    public function createQueue(string $name, string $vHost, array $additionConfig = []) {
        /** @var ResponseInterface $res */
        $res = $this->sendAuthenticatedRequest('queues/' . $vHost . '/' . $name, 'PUT', $additionConfig, false);
        if($res->getStatusCode() == Response::HTTP_CREATED || $res->getStatusCode() == Response::HTTP_NO_CONTENT) {
            return true;
        }

        return false;
    }

    /**
     * @param Queue $queue
     *
     * @return bool
     * @throws GuzzleException
     */
    public function deleteQueue(Queue $queue) {
        $res = $this->sendAuthenticatedRequest('queues/' . $queue->getVhost() . '/' . $queue->getName(), 'DELETE', [], false);
        if($res->getStatusCode() == Response::HTTP_CREATED || $res->getStatusCode() == Response::HTTP_NO_CONTENT) {
            return true;
        }

        return false;
    }
}