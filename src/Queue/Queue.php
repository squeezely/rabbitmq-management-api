<?php


namespace Squeezely\RabbitMQ\Management\Queue;

class Queue {

    /** @var array */
    private $data;

    /**
     * Queue constructor.
     *
     * @param $data
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->data['name'];
    }

    /**
     * @return array
     */
    public function getRawData() {
        return $this->data;
    }

    /**
     * @return bool|string
     */
    public function getVhost() {
        return $this->data['vhost'] ?? false;
    }
}