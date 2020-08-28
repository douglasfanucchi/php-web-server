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
}
