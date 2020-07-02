<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/database_connection.php';
require_once __DIR__ . '/functions_tasks.php';
require_once __DIR__ . '/functions_users.php';
require_once __DIR__ . '/functions_auth.php';

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
function redirect($path, $extra = []) { // optional array of extra inof for the response headers
    $response = Response::create(null, Response::HTTP_FOUND, ['Location' => $path]);
    // if cookies exist they need to be passed back to the browser
    if (key_exists('cookies', $extra)) {
        foreach ($extra['cookies'] as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }
    $response->send();
    exit;
}
