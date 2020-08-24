<?php

namespace PHPWebServer;

class Request
{
  private string $rawRequest;
  public string $method;

  public function __construct(string $rawRequest)
  {
    $this->rawRequest = $rawRequest;
    $this->setMethod();
  }

  private function setMethod()
  {
    $methods = ["GET", "POST", "DELETE", "OPTIONS", "PUT"];

    foreach ($methods as $method)
      if (preg_match("/{$method}/", $this->rawRequest, $matches)) break;

    $this->method = trim(array_shift($matches));
  }
}
