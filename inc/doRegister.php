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

// if you've gotten this far, the username is unique,
// the passwords match, and we're ready to add the user

// hash the password.  PASSWORD_DEFAULT will support
// future enchancements to encryption algorithms
// currently bcrypt in PHP v7.4.1
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// add the user to the DB
addUser($username, $hashedPassword);

// TODO: create a JWT for the session

redirect('/index.php');
