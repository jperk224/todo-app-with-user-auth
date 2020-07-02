<?php

use Firebase\JWT\JWT as JWT;
use Symfony\Component\HttpFoundation\Cookie as Cookie;

// Auth functions

/**
 * Create a cookie from JWT
 * @param jwt JSON web token
 * @param expirationTime token life int
 */
function jwtCookie($jwt, $expirationTime)
{
    $cookie = Cookie::create('auth', $jwt, $expirationTime, '/', getenv("COOKIE_DOMAIN"), false, true);
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
    $key = $_ENV["SECRET_KEY"];
    $payload = [
        'iss' => request()->getBaseUrl(),
        'sub' => (int) $user['id'],
        'exp' => $expirationTime,
        'iat' => time(),
        'nbf' => time(),
    ];
    $algoArray = 'HS256';
    $jwt = JWT::encode($key, $payload, $algoArray);
    $cookie = jwtCookie($jwt, $expirationTime);
    redirect('/', ['cookies' => [$cookie]]);
}
