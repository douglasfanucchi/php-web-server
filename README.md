# About

This is just a project that implements a basic webserver with pure PHP. I made it in order to learn more about how the web works.
Do not use it in production, this is just an experimental project!

## How to install on your machine

- Clone this repository
- Run `$ php composer.phar dumpautoload`
- `./server $port` \$port being the port that you want your webserver to listen

#### You can run this JavaScript code on your browser console to test it

```
fetch(`http://127.0.0.1:${port}`, {
  method: "POST",
  body: JSON.stringify({ firstName: 'YourFirstName', lastName: 'YourLastName' }),
})
  .then((r) => r.json())
  .then(console.log);
```

If the request is successfull you should see something like this
![Response Example](https://raw.githubusercontent.com/douglasfanucchi/php-web-server/master/assets/responsae-example.png)

## How does it work?

If you open the server file you'll find something like this

```
#!/usr/bin/php
<?php

use PHPWebServer\Request;
use PHPWebServer\Response;

require __DIR__ . '/vendor/autoload.php';

array_shift($argv);

$server = new PHPWebServer\Server('127.0.0.1', (int) $argv[0]);
$server->listen(function (Request $request) {
  $response = new stdClass();

  if (empty($request->body)) {
    return new Response($response, 200);
  }

  $response->fullName      = $request->body['firstName'] . " " . $request->body['lastName'];
  $response->requestMethod = "You sent a request with the " . $request->method . " method.";
  $response->headers       = $request->headers;

  return new Response($response, 200);
});

```

This code, basically instantiate a server that is listening to the host 127.0.0.1 (localhost) and the port that is passed through terminal.
Once you instantiate the PHPWebServer\Server you have to call the PHPWebServer\Server::listen method passing a pass a callback as argument.
The callback will receive a PHPWebServer\Request object as parameter and the callback must return an instance of PHPWebServer\Response.
The class PHPWebServer\Response must receive the data in stdClass format as first argument and the second the status code (not all status code are implemented).
