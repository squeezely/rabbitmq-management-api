<?php


namespace Squeezely\RabbitMQ\Management\Bindings;


class Binding {
    public const DESTINATION_TYPE_EXCHANGE = 'exchange';
    public const DESTINATION_TYPE_QUEUE = 'queue';

    public const DESTINATION_TYPES = [
        self::DESTINATION_TYPE_EXCHANGE,
        self::DESTINATION_TYPE_QUEUE
    ];


    /** @var array */
    private $data;

    /**
     * @var string
     */
    private $source = '';
    /**
     * @var array
     */
    private $arguments = '';
    /**
     * @var string
     */
    private $vhost = '';
    /**
     * @var string
     */
    private $destinationType = '';
    /**
     * @var string
     */
    private $destination = '';
    /**
     * @var string
     */
    private $routing_key = '';
    /**
     * @var string
     */
    private $propertiesKey = '';

    /**
     * Binding constructor.
     *
     * @param $data
     */
    public function __construct(array $data = []) {
        $this->data = $data;

        $this->source = $data['source'] ?? '';
        $this->vhost = $data['vhost'] ?? '';
        $this->destination = $data['destination'] ?? '';
        $this->destinationType = $data['destination_type'] ?? '';
        $this->routing_key = $data['routing_key'] ?? '';
        $this->arguments = $data['arguments'] ?? [];
        $this->propertiesKey = $data['properties_key'] ?? '';
    }

    /**
     * @return bool
     */
    public function isDefaultExchangeBinding() {
        if($this->data['source'] === '' && $this->data['destination'] === $this->data['routing_key']) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getSource(): string {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void {
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function getArguments(): ?array {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(?array $arguments): void {
        $this->arguments = $arguments;
    }

    public function addArguments(string $key, $value): void {
        $this->arguments[$key] = $value;
    }

    /**
     * @return string
     */
    public function getVhost(): string {
        return $this->vhost;
    }

    /**
     * @param string $vhost
     */
    public function setVhost(string $vhost): void {
        $this->vhost = $vhost;
    }

    /**
     * @return string
     */
    public function getDestinationType(): string {
        return $this->destinationType;
    }

    /**
     * @param string $destinationType
     */
    public function setDestinationType(string $destinationType): void {
        $this->destinationType = $destinationType;
    }

    /**
     * @return string
     */
    public function getDestination(): string {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination(string $destination): void {
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getRoutingKey(): string {
        return $this->routing_key;
    }

    /**
     * @param string $routing_key
     */
    public function setRoutingKey(string $routing_key): void {
        $this->routing_key = $routing_key;
    }

    public function getRawData(): array {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getPropertiesKey(): string {
        return $this->propertiesKey;
    }

    /**
     * @param string $propertiesKey
     */
    public function setPropertiesKey(string $propertiesKey): void {
        $this->propertiesKey = $propertiesKey;
    }
}