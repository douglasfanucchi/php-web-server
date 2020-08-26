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
    $rawRequestWithoutFirstRow = $this->removeRequestFirstRow();
    $rawHeader                 = $this->getRawHeader($rawRequestWithoutFirstRow);
    $headerRows                = $this->getRequestRows($rawHeader);

    $this->headers = $this->parseHeaderRows($headerRows);
  }

  private function removeRequestFirstRow()
  {
    $firstRowPattern = "/{$this->method}\ \/\ HTTP\/1.1(\r\n|\r|\n)/";
    preg_match($firstRowPattern, $this->rawRequest, $matches);

    $firstRow = array_shift($matches);
    return str_replace($firstRow, "", $this->rawRequest);
  }

  private function getRawHeader(string $rawRequest)
  {
    $bodyPattern = "/(\r\n\r\n|\r\r|\n\n)(.*)$/";

    return preg_replace($bodyPattern, "\r\n", $rawRequest);
  }

  private function getRequestRows(string $rawHeader): array
  {
    $rowsPattern = "/(.*)(\r\n|\r|\n)/";
    preg_match_all($rowsPattern, $rawHeader, $matches);

    return array_shift($matches);
  }

  private function parseHeaderRows(array $headerRows)
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
