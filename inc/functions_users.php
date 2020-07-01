<?php
// user functions

// users table interface functions

function getAllUsers()
{
    global $db;
    try {
        $sql = 'SELECT * FROM users';
        $resultSet = $db->prepare($sql);
        $resultSet->execute();
        return $resultSet->fetchAll();  // default is fetch_assoc, see database_connection.php
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function getUserByUserName($username)
{
    global $db;
    try {
        $sql = "SELECT * FROM users WHERE username = :username";
        $resultSet = $db->prepare($sql);
        $resultSet->bindParam(':username', $username);
        $resultSet->execute();
        return $resultSet->fetch();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function getUserById($id)
{
    global $db;
    try {
        $sql = "SELECT * FROM users WHERE id = :id";
        $resultSet = $db->prepare($sql);
        $resultSet->bindParam(':id', $id);
        $resultSet->execute();
        return $resultSet->fetch();
    } catch (Exception $e) {
        echo $e->getMessage();
    }    
}

function addUser($username, $password)
{
    global $db;
    try {
        $sql = "INSERT INTO users (username, password)
            VALUES (:username, :password)";
        $resultSet = $db->prepare($sql);
        $resultSet->bindParam(':username', $username);
        $resultSet->bindParam(':password', $password);
        $resultSet->execute();
        // retrun the newly added user, useful for redirect into
        // the app in an authenticated status once registered
        return getUserByUserName($username);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function changePassword($password, $id)
{
    global $db;
    try {
        $sql = "UPDATE users SET password = :password
                WHERE id = :id";
        $resultSet = $db->prepare($sql);
        $resultSet->bindParam(':password', $password);
        $resultSet->bindParam(':id', $id);
        $resultSet->execute();
        // return the user with new password for any needed 
        // authentication updates
        return getUserById($id);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
