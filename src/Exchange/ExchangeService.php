<?php


namespace Squeezely\RabbitMQ\Management\Exchange;


use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Squeezely\RabbitMQ\Management\Client;
use Squeezely\RabbitMQ\Management\Response;

class ExchangeService extends Client {

    /**
     * @param string $name
     * @param string $vhost
     *
     * @return Exchange|false
     * @throws Exception
     */
    public function getExchange(string $name, string $vhost) {
        try {
            $exchangeGeneralData = $this->sendAuthenticatedRequest('exchanges/' . $vhost . '/' . $name);
        } catch(GuzzleException $e) {
            return false;
        }

        if(isset($exchangeGeneralData['name']) && $exchangeGeneralData['name'] == $name) {
            return new Exchange($exchangeGeneralData);
        }

        return false;
    }

    /**
     * @param string|null $vhost
     * @return Exchange[]
     * @throws GuzzleException
     */
    public function getExchanges(?string $vhost = null) {
        if($vhost !== null) {
            $context = 'exchanges/' . $vhost;
        }
        else {
            $context = 'exchanges';
        }

        $exchanges = [];
        foreach($this->sendAuthenticatedRequest($context) as $queue) {
            $exchanges[] = new Exchange($queue);
        }

        return $exchanges;
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
    public function createExchange(string $name, string $vHost, array $additionConfig = []) {
        /** @var ResponseInterface $res */
        $res = $this->sendAuthenticatedRequest('exchanges/' . $vHost . '/' . $name, 'PUT', $additionConfig, false);
        if($res->getStatusCode() == Response::HTTP_CREATED || $res->getStatusCode() == Response::HTTP_NO_CONTENT) {
            return true;
        }

        return false;
    }

    /**
     * @param Exchange $exchange
     *
     * @return bool
     * @throws GuzzleException
     */
    public function deleteExchange(Exchange $exchange) {
        $res = $this->sendAuthenticatedRequest('exchanges/' . $exchange->getVhost() . '/' . $exchange->getName(), 'DELETE', [], false);
        if($res->getStatusCode() == Response::HTTP_CREATED || $res->getStatusCode() == Response::HTTP_NO_CONTENT) {
            return true;
        }

        return false;
    }
}