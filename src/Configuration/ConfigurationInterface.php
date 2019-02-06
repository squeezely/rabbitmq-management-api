<?php


namespace Squeezely\RabbitMQ\Management\Configuration;


interface ConfigurationInterface {

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return array
     */
    public function getAuthArray();

}