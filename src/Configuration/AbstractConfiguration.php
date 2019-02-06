<?php


namespace RabbitMQManagement\Configuration;


class AbstractConfiguration implements ConfigurationInterface {
    /** @var string */
    private $userName;
    /** @var string */
    private $password;
    /** @var string */
    private $hostname;
    /** @var string */
    private $port;
    /** @var string */
    private $protocol;

    /**
     * Client constructor.
     *
     * @param string $hostname
     * @param int $port
     * @param string|null $protocol
     * @param string|null $userName
     * @param string|null $password
     */
    public function __construct($hostname = null, $port = null, $protocol = null, $userName = null, $password = null) {

        if($hostname === null)
            $hostname = 'localhost';
        if($port === null)
            $port = 15672;
        if($protocol === null)
            $protocol = 'http';

        $this->hostname = $hostname;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->userName = $userName;
        $this->password = $password;
    }

    public function getUrl() {
        return $this->protocol . '://' . $this->hostname . ':' . $this->port . '/api/';
    }

    public function getUsername() {
        return $this->userName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getAuthArray() {
        return [$this->userName, $this->password];
    }

}