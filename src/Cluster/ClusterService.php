<?php


namespace Squeezely\RabbitMQ\Management\Cluster;

use Squeezely\RabbitMQ\Management\Client;

class ClusterService extends Client {
    /**
     * @return mixed
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOverview() {
        return $this->sendAuthenticatedRequest('overview');
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getClusterName() {
        $clusterInfo = $this->sendAuthenticatedRequest('cluster-name');
        if(isset($clusterInfo['name'])) {
            return $clusterInfo['name'];
        }
        else {
            return false;
        }
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getExtensions() {
        return $this->sendAuthenticatedRequest('extensions');
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNodes() {
        return $this->sendAuthenticatedRequest('nodes');
    }

    /**
     * @param string $name
     *
     * @param bool   $memory
     * @param bool   $binary
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNode(string $name, bool $memory = false, bool $binary = false) {
        $params = [];
        if($memory) $params[] = 'memory=true';
        if($binary) $params[] = 'binary=true';
        return $this->sendAuthenticatedRequest('nodes/' . $name . ($params ? '?' . implode('&', $params) : ''));
    }
}