<?php


namespace Squeezely\RabbitMQ\Management\Vhost;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Squeezely\RabbitMQ\Management\Client;

class VhostService extends Client {

    /**
     * @param string $name
     *
     * @param bool $refetch
     *
     * @return Vhost|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getVhost(string $name) {
        try {
            $vhostGeneralData = $this->sendAuthenticatedRequest('vhosts/' . $name);
        } catch(GuzzleException $e) {
            return false;
        }

        if(isset($vhostGeneralData['name']) && $vhostGeneralData['name'] == $name) {
            $vhost = new Vhost($vhostGeneralData, $this->getVhostPermissions($name), $this->getVhostTopicPermissions($name));
            return $vhost;
        }

        return false;
    }

    /**
     *
     * @return Vhost[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getVhosts() {
        $vHosts = [];
        foreach($this->sendAuthenticatedRequest('vhosts') as $vhost) {
                $vHosts[] = new Vhost($vhost, $this->getVhostPermissions($vhost['name']), $this->getVhostTopicPermissions($vhost['name']));
        }

        return $vHosts;
    }

    /**
     *
     * Will return true when created or already exists, on failure returns false
     *
     * @param string $name
     * @param bool $enableTracing
     *
     * @return bool
     * @throws GuzzleException
     */
    public function createVhost(string $name, bool $enableTracing = false) {
        $body = [];
        if($enableTracing === true) {
            $body = ['tracing' => true];
        }

        /** @var Response $res */
        $res = $this->sendAuthenticatedRequest('vhosts/' . $name, 'PUT', $body, false);
        if($res->getStatusCode() == 201 || $res->getStatusCode() == 204) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getVhostTopicPermissions(string $name) {
        return $this->sendAuthenticatedRequest('vhosts/' . $name . '/permissions');
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getVhostPermissions(string $name) {
        return $this->sendAuthenticatedRequest('vhosts/' . $name . '/permissions');
    }

}