<?php

use Firebase\JWT\JWT as JWT;
use Symfony\Component\HttpFoundation\Cookie as Cookie;

require_once __DIR__ .'/../inc/bootstrap.php';
// var_dump(decodeAuthCookie());

$session->getFlashBag()->add('success', 'Successfully Logged Out');
// expire the authenticaiton cookie
$cookie = setAuthorizationCookie('expired', 1);

redirect('/login.php', ['cookies' => [$cookie]]);
