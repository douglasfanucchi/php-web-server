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

  protected function bind()
  {
    if (!socket_bind($this->socket, $this->hostname, $this->port)) {
      throw new Exception("The socket couldn't be created. \r\n Error: " . socket_strerror(socket_last_error($this->socket)));
    }
  }
}
