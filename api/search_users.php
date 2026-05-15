<?php
// api/search_users.php
require_once __DIR__ . '/../models/UserModel.php';
header('Content-Type: application/json');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($query)) {
    $users = UserModel::getAll();
} else {
    $users = UserModel::searchUsers($query);
}

echo json_encode($users);
?>
