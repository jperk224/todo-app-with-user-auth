<?php
//task functions

function getTasks($id = null, $where = null)
{
    global $db;
    $sql = "SELECT * FROM tasks ";
    if (!empty($id) && !empty($where)) {
        $sql .= "WHERE user_id = $id AND $where";
    } elseif (!empty($id)) {
        $sql .= "WHERE user_id = $id";
    } elseif (!empty($where)) {
        $sql .= "WHERE $where";
    }
    $sql .= " ORDER BY id";
    try {
        $statement = $db->prepare($sql);
        $statement->execute();
        $tasks = $statement->fetchAll();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $tasks;
}

function getIncompleteTasks($id)
{
    return getTasks($id, 'status = 0');
}

function getCompleteTasks($id)
{
    return getTasks($id, 'status = 1');
}

function getTask($task_id)
{
    global $db;

    try {
        $statement = $db->prepare('SELECT id, task, status FROM tasks WHERE id=:id');
        $statement->bindParam('id', $task_id);
        $statement->execute();
        $task = $statement->fetch();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $task;
}
function createTask($data)
{
    global $db;

    try {
        $statement = $db->prepare('INSERT INTO tasks (task, status, user_id) VALUES (:task, :status, :userId)');
        $statement->bindParam('task', $data['task']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('userId', $data['userId']);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getTask($db->lastInsertId());
}
function updateTask($data)
{
    global $db;

    try {
        getTask($data['task_id']);
        $statement = $db->prepare('UPDATE tasks SET task=:task, status=:status WHERE id=:id');
        $statement->bindParam('task', $data['task']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('id', $data['task_id']);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getTask($data['task_id']);
}
function updateStatus($data)
{
    global $db;

    try {
        getTask($data['task_id']);
        $statement = $db->prepare('UPDATE tasks SET status=:status WHERE id=:id');
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('id', $data['task_id']);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getTask($data['task_id']);
}
function deleteTask($task_id)
{
    global $db;

    try {
        getTask($task_id);
        $statement = $db->prepare('DELETE FROM tasks WHERE id=:id');
        $statement->bindParam('id', $task_id);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}
