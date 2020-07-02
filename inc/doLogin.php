<?php

require_once __DIR__ .'/../inc/bootstrap.php';

// grab the form variables from the request object
$username = request()->get('username');
$password = request()->get('password');

$user = getUserByUserName($username);

// redirect with error if bad username or password
if (empty($user) || !password_verify($password, $user['password'])) {
    $session->getFlashBag()->add('error', 'Invalid username or password.');
    redirect('/login.php');
}


