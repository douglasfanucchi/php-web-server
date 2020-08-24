<?php

namespace PHPWebServer;

use Exception;

class Server
{
  protected $hostname;
  protected $port;
  protected $socket;

  public function __construct($hostname, $port)
  {
    $this->hostname = $hostname;
    $this->port     = $port;

    $this->setSocket();
    $this->bind();
  }

  public function listen($callback)
  {
    if (!is_callable($callback)) throw new Exception('This argument should be a callable!');

    socket_listen($this->socket);

    while (true) {
      $client_socket = socket_accept($this->socket);

      if (!$client_socket) {
        continue;
        socket_close($client_socket);
      }

      $rawData = socket_read($client_socket, 1024);

      $request = new Request($rawData);

      $response = call_user_func($callback, $request);

      if (!($response instanceof Response)) {
        throw new Exception("The callback must return an instance of PHPWebServer\\Response");
      }

      socket_write($client_socket, $response->__toString(), strlen($response->__toString()));
      socket_close($client_socket);
    }
  }

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
