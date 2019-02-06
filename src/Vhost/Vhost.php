<?php


namespace RabbitMQManagement\Vhost;

class Vhost {

    /** @var array */
    private $generalData;
    /** @var array */
    private $permissionData;
    /** @var array */
    private $topicPermissionData;

    /**
     * Vhost constructor.
     *
     * @param $generalData
     * @param $permissionData
     * @param $topicPermissionData
     */
    public function __construct($generalData, $permissionData, $topicPermissionData) {
        $this->generalData = $generalData;
        $this->permissionData = $permissionData;
        $this->topicPermissionData = $topicPermissionData;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->generalData['name'];
    }

    /**
     * @return array
     */
    public function getPermissions() {
        return $this->permissionData;
    }

    /**
     * @return array
     */
    public function getTopicPermissions() {
        return $this->topicPermissionData;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->generalData;

    }
}