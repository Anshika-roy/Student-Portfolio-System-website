<?php
// php/login.php
session_start();

$jsonFile = '../data/users.json';

function normalizeList($value) {
    if (is_array($value)) {
        return array_values(array_filter(array_map('trim', $value), function ($item) {
            return $item !== '';
        }));
    }

    return array_values(array_filter(array_map('trim', explode(',', (string) $value)), function ($item) {
        return $item !== '';
    }));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    $users = [];
    if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
        $content = file_get_contents($jsonFile);
        $users = json_decode($content, true) ?? [];
    }

    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'] ?? null,
                'name' => $user['name'] ?? '',
                'email' => $user['email'] ?? '',
                'phone' => $user['phone'] ?? '',
                'education' => normalizeList($user['education'] ?? []),
                'skills' => normalizeList($user['skills'] ?? []),
                'projects' => normalizeList($user['projects'] ?? ['Online Student Portfolio & Registration System'])
            ];
            header("Location: ../portfolio.html");
            exit;
        }
    }

    echo "Invalid email or password. <a href='../login.html'>Try again</a>";
}
?>
