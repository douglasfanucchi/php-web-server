<?php

namespace PHPWebServer;

class Request
{
  private string $rawRequest;
  public string $method;
  public array $headers;

  public function __construct(string $rawRequest)
  {
    $this->rawRequest = $rawRequest;
    $this->setMethod();
    $this->setHeader();
  }

  private function setMethod()
  {
    $methods = ["GET", "POST", "DELETE", "OPTIONS", "PUT"];

    foreach ($methods as $method)
      if (preg_match("/{$method}/", $this->rawRequest, $matches)) break;

    $this->method = trim(array_shift($matches));
  }

  private function setHeader()
  {
    $rawRequestWithoutFirstLine = $this->removeRequestFirstLine();
    $rawHeader                  = $this->getRawHeader($rawRequestWithoutFirstLine);
    $headerLines                = $this->getRequestLines($rawHeader);

    $this->headers = $this->parseHeaderLines($headerLines);
  }

  private function removeRequestFirstLine()
  {
    $firstLinePattern = "/{$this->method}\ \/\ HTTP\/1.1(\r\n|\r|\n)/";
    preg_match($firstLinePattern, $this->rawRequest, $matches);

    $firstLine = array_shift($matches);
    return str_replace($firstLine, "", $this->rawRequest);
  }

  private function getRawHeader(string $rawRequest)
  {
    $bodyPattern = "/(\r\n\r\n|\r\r|\n\n)(.*)$/";

    return preg_replace($bodyPattern, "\r\n", $rawRequest);
  }

  private function getRequestLines(string $rawHeader): array
  {
    $linesPattern = "/(.*)(\r\n|\r|\n)/";
    preg_match_all($linesPattern, $rawHeader, $matches);

    return array_shift($matches);
  }

  private function parseHeaderLines(array $headerLines)
  {
    $headers = [];

    foreach ($headerLines as $headerLine) {
      $line = explode(": ", $headerLine);
      $key = trim($line[0]);
      $value = trim($line[1]);

      $headers[$key] = $value;
    }

    return $headers;
  }
}
