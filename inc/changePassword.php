<?php

require_once __DIR__ .'/../inc/bootstrap.php';

requireAuth();

// grab form variables sent in the post
$currentPassword = request()->get('current_password');
$newPassword = request()->get('password');
$confirmPassword = request()->get('confirm_password');

// check whether new passwords passed in match
if ($newPassword != $confirmPassword) {
  $session->getFlashBag()->add('error', 'New passwords do not match. Please try again.');
  redirect('/account.php');
}

// if you've made it this far, the user is authenticated and their
// form passwords match
$user = getUserById(decodeAuthCookie()->sub);

// redirect if no user is found
if (empty($user)) {
    $session->getFlashBag()->add('error', 'Some Error Happend. Try again. If it continues, please log out and back in.');
    redirect('/account.php');
}

// check that new password does not equal existing
if (password_verify($newPassword, $user['password'])) {
    $session->getFlashBag()->add('error', 'New passwords cannot be the same as existing. Please try again.');
    redirect('/account.php');
}

// check that current password is valid before updating
if (!password_verify($currentPassword, $user['password'])) {
    $session->getFlashBag()->add('error', 'Could not update password. Please try again.');
    redirect('/account.php');
}

// update the user's password with the new hashed password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

if (!changePassword($hashedPassword, $user['id'])) {
  $session->getFlashBag()->add('error', 'Could not update password, please try again.');
  redirect('/account.php');
}

$session->getFlashBag()->add('success', 'Password Updated');
redirect('/account.php');
