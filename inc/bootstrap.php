<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/database_connection.php';
require_once __DIR__ . '/functions_tasks.php';
require_once __DIR__ . '/functions_users.php';

// define short references to namespaces
use \Symfony\Component\HttpFoundation\Session\Session as Session;
use \Symfony\Component\HttpFoundation\Request as Request;
use \Symfony\Component\HttpFoundation\Response as Response;

/*
 * Set access to components from \Symfony\Component\HttpFoundation\
 * 1. Session
 * 2. Request
 * 3. Redirect
 */

/**
 * session \Symfony\Component\HttpFoundation\Session 
 * Symfony session API is used over built-in PHP session tools
 */
$session = new Session();
$session->start();  

/** 
 * request \Symfony\Component\HttpFoundation\Request
 * return a request object built from the PHP super globals
 * Object property values hold information about the client request
 */ 
function request() {
    return Request::createFromGlobals();
}

/**
 * redirect \Symfony\Component\HttpFoundation\Response
 * HTTP_FOUND = 302
 * @param $path the URL to redirect to
 */
function redirect($path) {
    $response = Response::create(null, Response::HTTP_FOUND, ['Location' => $path]);
    $response->send();
    exit;
}
