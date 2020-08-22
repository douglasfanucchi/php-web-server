<?php

namespace PHPWebServer;

use Exception;

class Server
{
  protected $hostname;
  protected $port;
  protected $socket;

  protected function setSocket()
  {
    $this->socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
  }
}
