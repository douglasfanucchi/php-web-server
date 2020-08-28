<?php

namespace PHPWebServer;

class Response
{
  private string $responseJson;
  private int $statusCode;
  private array $headers;

  public function __construct(\stdClass $responseData, int $statusCode)
  {
    $this->responseJson = json_encode($responseData);
    $this->statusCode = $statusCode;
  }

  public function addHeader(string $key, string $value)
  {
    $this->headers[$key] = $value;
  }

  public function __toString()
  {
    $httpInfo = "HTTP/1.1 " . $this->getStatusCodeInfo();
    $header   = $this->getRawHeader($this->headers);
    $body     = PHP_EOL . $this->responseJson;

    return $httpInfo . $header . $body;
  }

  private function getStatusCodeInfo()
  {
    switch ($this->statusCode) {
      case 200:
        return '200 OK';
      case 500:
        return '500 ERROR';
    }
  }

  private function getRawHeader(array $headers)
  {
    return array_reduce(array_keys($headers), function ($store, $key) use ($headers) {
      return $store . "{$key}: {$headers[$key]}" . PHP_EOL;
    }, '');
  }
}
