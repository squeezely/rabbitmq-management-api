<?php


namespace SqueezelyRabbitMQManagement\Configuration;


class ArrayConfiguration extends AbstractConfiguration {

    public function __construct(array $configuration) {
        foreach($configuration as $key => $value) {
            $configuration[strtolower($key)] = $value;
        }

        parent::__construct(
            $configuration['hostname'] ?? null,
            $configuration['port'] ?? null,
            $configuration['protocol'] ?? null,
            $configuration['username'] ?? null,
            $configuration['password'] ?? null
        );
    }

}