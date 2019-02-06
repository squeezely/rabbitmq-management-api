<?php


namespace Squeezely\RabbitMQ\Management\Configuration;


class DotEnvConfiguration implements ConfigurationInterface {
    public function __construct() {
        //TODO: implement (automatic) dotenv configuration
        throw new \Exception('Not yet implemented, please use AbstractConfiguration for now');
    }

    public function getAuthArray() {
        return [];
    }

    public function getUrl() {
        return '';
    }

    public function getPassword() {
        return '';
    }

    public function getUsername() {
        return '';
    }
}