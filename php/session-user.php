<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Not logged in']);
    exit;
}

$user = $_SESSION['user'];

$normalizeList = function ($value) {
    if (is_array($value)) {
        return array_values(array_filter(array_map('trim', $value), function ($item) {
            return $item !== '';
        }));
    }

    return array_values(array_filter(array_map('trim', explode(',', (string) $value)), function ($item) {
        return $item !== '';
    }));
};

unset($user['password']);

$user['education'] = $normalizeList($user['education'] ?? []);
$user['skills'] = $normalizeList($user['skills'] ?? []);
$user['projects'] = $normalizeList($user['projects'] ?? ['Online Student Portfolio & Registration System']);

echo json_encode($user);
?>