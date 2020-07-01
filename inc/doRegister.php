<?php

require_once __DIR__ .'/../inc/bootstrap.php';

// grab the form variables from the request object
$username = request()->get('username');
$password = request()->get('password');
$confirmPassword = request()->get('confirm_password');

// check form variables before talking to the DB; best practice
if ($password != $confirmPassword) {
  $session->getFlashBag()->add('error', 'Passwords do NOT match');
  redirect('/register.php');
}

// check to see if the username already exists
// if it down, redirect the user back to the registration page
$user = getUserByUserName($username);
if (!empty($user)) {
    $session->getFlashBag()->add('error', 'Username already exists');
    redirect('/register.php');
}
