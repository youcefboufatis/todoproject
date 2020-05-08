<?php

require_once "init.php";

if (isset($_SERVER['REQUEST_METHOD']) == 'POST') {
    if ($_POST['action'] == 'view') {
        if (hash_equals($_POST['token'], $_SESSION['token']) === false) {
            $obj = array('result' => 'failed', 'post_token' => $_POST['token'], 'session_token' => $_SESSION['token']);
            echo json_encode($obj);
        } elseif ($_POST['token'] === $_SESSION["token"]) {
            $stmt = $db->querySelect('todo_task', '*', null);
            $result = $db->getAll($stmt);
            if ($result['status'] > 0) {
                $data = $db->fetchAll($result['query'], $result['status']);
                $obj = array('result' => $result, 'data'=> $data);
                echo json_encode($obj);
            }
        }
    }
    if ($_POST['action'] == 'add') {
        if (hash_equals($_POST['token'], $_SESSION['token']) === false) {
            $obj = array('result' => 'failed', 'post_token' => $_POST['token'], 'session_token' => $_SESSION['token']);
            echo json_encode($obj);
        } elseif ($_POST['token'] === $_SESSION["token"]) {
            $task_name = $_POST['task_name'];
            $stmt = $db->queryInsert('todo_task', 'task_name, task_status', "'{$task_name}', 0");
            $result = $db->insertQuery($stmt);
            if ($result['status'] > 0) {
                $lastID = $db->lastInsertId();
                $stmt = $db->querySelect('todo_task', '*', "task_id=$lastID");
                $result = $db->getAll($stmt);
                if ($result['status'] > 0) {
                    $data = $db->fetchAll($result['query'], $result['status']);
                    $obj = array('result' => $result, 'data'=> $data, 'result1' => $_POST['token']);
                    echo json_encode($obj);
                }
            }
        }
    }
    if ($_POST['action'] == "done") {
        if (hash_equals($_POST['token'], $_SESSION['token']) === false) {
            $obj = array('result' => 'failed', 'post_token' => $_POST['token'], 'session_token' => $_SESSION['token']);
            echo json_encode($obj);
        } elseif ($_POST['token'] === $_SESSION["token"]) {
            $task_status = $_POST['task_status'];
            $task_id = $_POST['task_id'];

            $stmt = $db->queryUpdate('todo_task', "task_status=$task_status", "task_id=$task_id");
            $result = $db->insertQuery($stmt);
            if ($result['status'] > 0) {
                $stmt = $db->querySelect('todo_task', '*', "task_id=$task_id");
                $result = $db->getAll($stmt);
                if ($result['status'] > 0) {
                    $data = $db->fetchAll($result['query'], $result['status']);
                    $obj = array('data' => $data, 'status' => 'checked');
                    echo json_encode($obj);
                }
            }
        }
    }
    if ($_POST['action'] == "not_done") {
        if (hash_equals($_POST['token'], $_SESSION['token']) === false) {
            $obj = array('result' => 'failed', 'post_token' => $_POST['token'], 'session_token' => $_SESSION['token']);
            echo json_encode($obj);
        } elseif ($_POST['token'] === $_SESSION["token"]) {
            $task_status = $_POST['task_status'];
            $task_id = $_POST['task_id'];

            $stmt = $db->queryUpdate('todo_task', "task_status=$task_status", "task_id=$task_id");
            $result = $db->insertQuery($stmt);
            if ($result['status'] > 0) {
                $stmt = $db->querySelect('todo_task', '*', "task_id=$task_id");
                $result = $db->getAll($stmt);
                if ($result['status'] > 0) {
                    $data = $db->fetchAll($result['query'], $result['status']);
                    $obj = array('data' => $data, 'status' => 'checked');
                    echo json_encode($obj);
                }
            }
        }
    }
    if ($_POST['action'] == "delete") {
        if (hash_equals($_POST['token'], $_SESSION['token']) === false) {
            $obj = array('result' => 'failed', 'post_token' => $_POST['token'], 'session_token' => $_SESSION['token']);
            echo json_encode($obj);
        } elseif ($_POST['token'] === $_SESSION["token"]) {
            $task_id = $_POST['task_id'];
            $stmt = $db->queryDelete('todo_task', "task_id=$task_id");
            $result = $db->insertQuery($stmt);
            if ($result['status'] > 0) {
                $obj = array('result' => $result);
                echo json_encode($obj);
            }
        }
    }
}
