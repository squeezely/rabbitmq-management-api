<?php


namespace Squeezely\RabbitMQ\Management\Queue;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Squeezely\RabbitMQ\Management\Client;

class QueueService extends Client {

    /**
     * @param string $name
     * @param string $vhost
     *
     * @return Queue|false
     * @throws \Exception
     */
    public function getQueue(string $name, string $vhost) {
        try {
            $vhostGeneralData = $this->sendAuthenticatedRequest('queues/' . $vhost . '/' . $name);
        } catch(GuzzleException $e) {
            return false;
        }

        if(isset($vhostGeneralData['name']) && $vhostGeneralData['name'] == $name) {
            $queue = new Queue($vhostGeneralData, $this->getVhostPermissions($name), $this->getVhostTopicPermissions($name));
            return $queue;
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
     * @param array $additionConfig
     * @return bool
     * @throws GuzzleException
     */
    public function createQueue(string $name, string $vHost, array $additionConfig = []) {
        /** @var Response $res */
        $res = $this->sendAuthenticatedRequest('queues/' . '/' . $vHost . '/' . $name, 'PUT', $additionConfig, false);
        if($res->getStatusCode() == 201 || $res->getStatusCode() == 204) {
            return true;
        }

        return false;
    }

    public function deleteQueue(Queue $queue) {
        $res = $this->sendAuthenticatedRequest('queues/' . $queue->getVhost() . '/' . $queue->getName(), 'DELETE', [], false);
        if($res->getStatusCode() == 201 || $res->getStatusCode() == 204) {
            return true;
        }
    }

}