<?php

namespace PHPWebServer;

class Response
{
  private string $responseJson;
  private int $statusCode;

  public function __construct(\stdClass $responseData, int $statusCode)
  {
    $this->responseJson = json_encode($responseData);
    $this->statusCode = $statusCode;
  }
}
