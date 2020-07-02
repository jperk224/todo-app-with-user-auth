<?php

use Firebase\JWT\JWT as JWT;
use Symfony\Component\HttpFoundation\Cookie as Cookie;

// Auth functions

/**
 * Create a cookie from JWT
 * @param jwt JSON web token
 * @param expirationTime token life int
 */
function setAuthorizationCookie($data, $expirationTime)
{
    $cookie = Cookie::create('auth', $data, $expirationTime, '/', $_ENV["COOKIE_DOMAIN"], false, true);
    return $cookie;
}

/**
 * Save a user's data to a JWT
 * @param user user object
 */
function saveUserData($user)
{
    global $session;
    // add a success flash message- works for all login use cases 
    // (i.e. new registration redirect and returning user login)
    $session->getFlashBag()->add('success', 'Successfully logged in. Welcome ' . $user['username'] . '!');
    $expirationTime = time() + 3600;    // token should expire in one hour
    // create the JWT
    $key = base64_encode($_ENV["SECRET_KEY"]);
    $payload = [
        'iss' => request()->getBaseUrl(),
        'sub' => (int) $user['id'],
        'exp' => $expirationTime,
        'iat' => time(),
        'nbf' => time(),
    ];
    $algo = 'HS256';
    $jwt = JWT::encode($payload, $key, $algo);
    $cookie = setAuthorizationCookie($jwt, $expirationTime);
    redirect('/', ['cookies' => [$cookie]]);
}

/**
 * Decode a JWT cookie
 */
function decodeAuthCookie()
{
  try {
    JWT::$leeway = 60;   // allow for 60 seconds for some clock skew when checking the time properties
    $key = base64_encode($_ENV["SECRET_KEY"]);
    $authCookie = request()->cookies->get('auth');
    $algoArray = ['HS256'];
    $decodedCookie = JWT::decode(
      $authCookie,
      $key,
      $algoArray
    );
  } catch (Exception $e) {
      return false;
  }
  return $decodedCookie;
}

/**
 * Check whether the user is authenticated
 * Returns the auth cookie as a PHP object or false by way of decodeAuthCookie()
 */
function isAuthenticated() 
{
    return decodeAuthCookie();
}

/**
 * Require the user to be authorized to view a page
 * If no auth cookie is returned, redirect the user home.
 */
function requireAuth()
{
    if (!isAuthenticated()) {
        global $session;
        $session->getFlashBag()->add('error', 'Not Authorized');
        redirect('/login.php');
    } 
}

/**
 * Hide pages for unauthenticated users
 */
function hideUnauthorizedLinks()
{
    if (!isAuthenticated()) {
        echo 'style="display:none"';
    }
}

/**
 * Hide pages for authenticated users
 */
function hideAuthorizedLinks()
{
    if (isAuthenticated()) {
        echo 'style="display:none"';
    }
}
