<?php


namespace Squeezely\RabbitMQ\Management;


use GuzzleHttp\Client as HttpClient;
use Squeezely\RabbitMQ\Management\Configuration\ConfigurationInterface;
use Squeezely\RabbitMQ\Management\Configuration\DotEnvConfiguration;

class Client {

    /** @var ConfigurationInterface */
    private $configuration;
    /** @var HttpClient */
    private $httpClient;

    /**
     * Client constructor.
     *
     * @param ConfigurationInterface $configuration
     *
     * @throws \Exception
     */
    public function __construct(ConfigurationInterface $configuration = null) {
        if(!$configuration) {
            $configuration = new DotEnvConfiguration();
        }
        $this->configuration = $configuration;
        $this->httpClient = new HttpClient(['base_uri' => $this->configuration->getUrl()]);
    }


    /**
     * @param $context
     * @param string $method
     * @param array $json
     * @param bool $jsonDecode
     *
     * @return array|mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    protected function sendAuthenticatedRequest($context, $method = 'GET', array $json = [], $jsonDecode = true) {
        $options = [
            'auth' => $this->configuration->getAuthArray(),
            'headers' => [
                'content-type' => 'application/json',
                'User-Agent' => 'Squeezely/RabbitMQ-Management-API'
            ]
        ];

        if($json) {
            $options['json'] = $json;
        }

        $httpResult = $this->httpClient->request($method, $context, $options);

        if($httpResult->getStatusCode() >= 200 && $httpResult->getStatusCode() <= 299) {
            $contents = $httpResult->getBody()->getContents();
            if(!empty($contents) && $jsonDecode === true) {
                $contents = \GuzzleHttp\json_decode($contents, true);
                return $contents;
            }
            elseif($jsonDecode) {
                return [];
            }
            else {
                return $httpResult;
            }
        }
        else {
            throw new \Exception('Unexpected status code ' . $httpResult->getStatusCode());
        }
    }

}