<?php


namespace Squeezely\RabbitMQ\Management\Bindings;


use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Squeezely\RabbitMQ\Management\Client;
use Squeezely\RabbitMQ\Management\Queue\Queue;
use Squeezely\RabbitMQ\Management\Response;

class BindingService extends Client {

    public function getAllBindings(?string $vhost = null) {
        if($vhost !== null) {
            $context = 'bindings/' . $vhost;
        }
        else {
            $context = 'bindings';
        }

        try {
            $rawBindings = $this->sendAuthenticatedRequest($context);
        } catch(GuzzleException $e) {
            return [];
        }

        $bindings = [];

        foreach($rawBindings as $rawBinding) {
            $binding = new Binding($rawBinding);
            $bindings[] = $binding;
        }

        return $bindings;
    }

    /**
     * @param Queue $queue
     *
     * @return Binding[]|null
     * @throws Exception
     */
    public function getBindingsByQueue(Queue $queue) {
        try {
            $rawBindings = $this->sendAuthenticatedRequest('queues/' . $queue->getVhost() . '/' . $queue->getName() . '/bindings');
        } catch(GuzzleException $e) {
            return null;
        }

        $bindings = [];

        foreach($rawBindings as $rawBinding) {
            $bindings[] = new Binding($rawBinding);
        }

        return $bindings ?: null;
    }

    /**
     * @param Binding $binding
     *
     * @return bool
     * @throws GuzzleException
     */
    public function createBinding(Binding $binding) {
        switch($binding->getDestinationType()) {
            case Binding::DESTINATION_TYPE_QUEUE:
                $context = 'bindings/' . $binding->getVhost() . '/e/' . $binding->getSource() . '/q/' . $binding->getDestination();
                break;
            case Binding::DESTINATION_TYPE_EXCHANGE:
                $context = 'bindings/' . $binding->getVhost() . '/e/' . $binding->getSource() . '/e/' . $binding->getDestination();
                break;
            default:
                throw new Exception('Binding destination type not implemented: ' . $binding->getDestinationType());
        }

        $additionConfig = [
            'routing_key' => $binding->getRoutingKey(),
            'arguments' => $binding->getArguments() ?: []
        ];

        try {
            $res = $this->sendAuthenticatedRequest($context, 'POST', $additionConfig, false);
        } catch(Exception $e){
            var_dump((string) $e->getResponse()->getBody());
        }
        if($res->getStatusCode() == Response::HTTP_CREATED || $res->getStatusCode() == Response::HTTP_NO_CONTENT) {
            return true;
        }

        return false;
    }

    /**
     * @param Binding $binding
     *
     * @return bool
     * @throws GuzzleException
     */
    public function deleteBinding(Binding $binding) {
        switch($binding->getDestinationType()) {
            case Binding::DESTINATION_TYPE_QUEUE:
                $context = 'bindings/' . $binding->getVhost() . '/e/' . $binding->getSource() . '/q/' . $binding->getDestination() . '/' . $binding->getPropertiesKey();
                break;
            case Binding::DESTINATION_TYPE_EXCHANGE:
                $context = 'bindings/' . $binding->getVhost() . '/e/' . $binding->getSource() . '/e/' . $binding->getDestination() . '/' . $binding->getPropertiesKey();
                break;
            default:
                throw new Exception('Binding destination type not implemented: ' . $binding->getDestinationType());
        }

        $res = $this->sendAuthenticatedRequest(
            $context,
            'DELETE',
            [],
            false
        );
        if($res->getStatusCode() == Response::HTTP_CREATED || $res->getStatusCode() == Response::HTTP_NO_CONTENT) {
            return true;
        }

        return false;
    }

}