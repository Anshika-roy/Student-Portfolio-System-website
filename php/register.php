<?php
// php/register.php
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

// Read existing users
$users = [];
if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
    $content = file_get_contents($jsonFile);
    $users = json_decode($content, true) ?? [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['fullName'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');
    $education = normalizeList($_POST['education'] ?? '');
    $skills   = trim($_POST['skills'] ?? '');

    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($education)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Check if email already exists
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            echo "Email already registered! <a href='../login.html'>Login here</a>";
            exit;
        }
    }

    // Add new user
    $newUser = [
        'id'       => count($users) + 1,
        'name'     => $name,
        'email'    => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'phone'    => $phone,
        'education' => $education,
        'skills'   => normalizeList($skills),
        'projects' => ['Online Student Portfolio & Registration System']
    ];

    $users[] = $newUser;
    file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT));

    echo "Registration successful! <a href='../login.html'>Login here</a>";
}
?>
