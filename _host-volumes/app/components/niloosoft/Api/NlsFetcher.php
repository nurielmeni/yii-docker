<?php

namespace app\components\niloosoft\niloosoft\api;

use yii\httpclient\Client;
use Yii;

/**
 * REST API Fetcher interface
 */
class NlsFetcher
{
    private $baseUrl;
    private $headers;
    private $data;
    private $nlsSecurity;

    public function __construct($url, $options, $headers = null)
    {
        try {
            $this->baseUrl = $url;
            if (!$this->baseUrl)
                throw new \InvalidArgumentException('The REST API Url is required.');
            if (!$options || !$options instanceof NlsOptions)
                throw new \InvalidArgumentException('Missing Options');

            $this->nlsSecurity = new NlsSecurity($this, $options);

            $this->headers = array_merge($this->nlsSecurity->getHeaders(), $headers && is_array($headers) ? $headers : []);
        } catch (\InvalidArgumentException $ex) {
            Yii::error($ex->getMessage());
        }
    }

    /**
     * @var string $url
     * @var array $headers, key value pairss as headers
     * @var array $data, key value pairs as body data
     */
    public function POST($endPoint, $data = null)
    {
        if (!$endPoint)
            throw new \InvalidArgumentException('endPoint missing');

        try {
            $url = $this->baseUrl . $endPoint;
            if (!self::validRequest($url, $data))
                return;

            $client = new Client([
                'requestConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
            ]);

            $response = $client->createRequest()
                ->setUrl($url)
                ->setHeaders($this->headers)
                ->setMethod('POST')
                ->setData($data)
                ->send();
            if ($response->isOk) {
                throw new \RuntimeException('Error with POST request: ' . $endPoint);
            }

            return $response->data;
        } catch (\Throwable $th) {
            Yii::error($th->getMessage());
        }
    }

    /**
     * @var string $url
     * @var array $headers, key value pairss as headers
     */
    public function GET($endPoint)
    {
        try {
            $url = $this->baseUrl . $endPoint;
            if (!self::validRequest($url))
                return;

            $client = new Client([
                'requestConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
            ]);

            $response = $client->createRequest()
                ->setUrl($url)
                ->setHeaders($this->headers)
                ->setMethod('GET')
                ->send();
            if ($response->isOk) {
                throw new \RuntimeException('Error with GET request: ' . $endPoint);
            }

            return $response->data;

        } catch (\Throwable $th) {
            Yii::error($th->getMessage());
        }
    }



    private function jsonData($data)
    {
        $jsonData = json_encode($data, JSON_THROW_ON_ERROR);
        return $jsonData;
    }

    public function setUrl($url)
    {
        $this->validUrl($url);

        $this->baseUrl = $url;

        return $this;
    }

    public function setHeaders($headers, $append = false)
    {
        $this->validHeaders($headers);

        if ($append) {
            $this->headers[] = $headers;
        } else {
            $this->headers = $headers;
        }

        return $this;
    }

    private function validHeaders($headers)
    {
        if (!$headers || !is_array($headers)) {
            throw new \InvalidArgumentException('Invalid headers');
        }

        return true;
    }

    private function validUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        return true;
    }

    private function validData($data)
    {
        if ($data && !is_array($data)) {
            throw new \InvalidArgumentException('Invalid body');
        }

        return true;
    }

    private function validRequest($url, $data = false)
    {
        return
            $this->validUrl($url) &&
            $this->validHeaders($this->headers) &&
            ($data ? $this->validData($data) : true);
    }
}
