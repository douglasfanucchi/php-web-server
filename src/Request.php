<?php

namespace PHPWebServer;

class Request
{
  private string $rawRequest;
  public string $method;
  public array $headers;
  public array $body;

  public function __construct(string $rawRequest)
  {
    $this->rawRequest = $rawRequest;
    $this->setRequestMethod();
    $this->setHeader();
    $this->setBody();
  }

  private function setRequestMethod()
  {
    $methods = ["GET", "POST", "DELETE", "OPTIONS", "PUT"];

    foreach ($methods as $method)
      if (preg_match("/{$method}/", $this->rawRequest, $matches)) break;

    $this->method = trim(array_shift($matches));
  }

  private function setHeader()
  {
    $rawHeader  = $this->getRawHeader($this->rawRequest);
    $headerRows = $this->getHeaderRowsAsArray($rawHeader);

    $this->headers = $this->parseHeaderArrayRows($headerRows);
  }

  private function setBody()
  {
    $rawBody = $this->getRawBody($this->rawRequest);

    $this->body = json_decode($rawBody, true);
  }

  private function getRawBody(string $rawRequest)
  {
    $headerBodyDivisor = "(" . PHP_EOL . ")";
    $headerPattern = "/(.+){$headerBodyDivisor}/s";

    return preg_replace($headerPattern, "", $rawRequest);
  }

  private function getRawHeader(string $rawRequest)
  {
    $this->removeRawRequestFirstRow($rawRequest);
    $this->removeRawRequestBody($rawRequest);

    return $rawRequest;
  }

  private function getHeaderRowsAsArray(string $rawHeader): array
  {
    $rowPattern = "/(.*)(\r\n|\r|\n)/";
    preg_match_all($rowPattern, $rawHeader, $matches);

    return array_shift($matches);
  }

  private function removeRawRequestFirstRow(string &$rawRequest)
  {
    $firstRowPattern = "/^(.+)" . PHP_EOL . "/";
    preg_match($firstRowPattern, $rawRequest, $matches);

    $firstRow = array_shift($matches);
    $rawRequest = str_replace($firstRow, "", $rawRequest);
  }

  private function removeRawRequestBody(string &$rawRequest)
  {
    $bodyPattern = "/(\r\n\r\n|\r\r|\n\n)(.*)$/";

    $rawRequest = preg_replace($bodyPattern, "\r\n", $rawRequest);
  }


  private function parseHeaderArrayRows(array $headerRows)
  {
    $headers = [];

    foreach ($headerRows as $headerRow) {
      $row   = explode(": ", $headerRow);
      $key   = trim($row[0]);
      $value = trim($row[1]);

      $headers[$key] = $value;
    }

    return $headers;
  }
}
