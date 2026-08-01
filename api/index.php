<?php

  require_once('private/initialize.php');

  header('Content-Type: application/json');

  $response = new stdClass;

  $response->message = 'Hello from the Keeplore API.';

  $response->endpoints = array(
    'GET /artifacts.php' => 'https://' . API_ORIGIN . '/artifacts.php'
  );

  echo json_encode($response);

?>