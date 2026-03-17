<?php
// php/register.php
session_start();

$jsonFile = '../data/users.json';

function normalizeList($value) {
    $items = is_array($value) ? $value : explode(',', (string) $value);
    $normalized = [];

    foreach ($items as $item) {
        foreach (explode(',', (string) $item) as $part) {
            $part = trim($part);
            if ($part !== '') {
                $normalized[] = $part;
            }
        }
    }

    return array_values($normalized);
}

function parseProjectLinks($value) {
    $lines = preg_split('/\r\n|\r|\n/', (string) $value);
    $projects = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }

        $parts = array_map('trim', explode('|', $line, 2));
        $title = $parts[0] ?? '';
        $link = $parts[1] ?? '';

        if ($title === '') {
            continue;
        }

        $projects[] = [
            'title' => $title,
            'link' => $link
        ];
    }

    return $projects;
}

function parseCertificates($namesValue, $imagesValue) {
    $names = normalizeList($namesValue);
    $images = normalizeList($imagesValue);
    $certificates = [];

    foreach ($names as $index => $name) {
        $certificates[] = [
            'name' => $name,
            'image' => $images[$index] ?? ''
        ];
    }

    return $certificates;
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
    $certificates = parseCertificates($_POST['certificates'] ?? '', $_POST['certificateImages'] ?? '');
    $projects = parseProjectLinks($_POST['projectLinks'] ?? '');

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
        'certificates' => $certificates,
        'projects' => !empty($projects) ? $projects : [[
            'title' => 'Online Student Portfolio & Registration System',
            'link' => ''
        ]]
    ];

    $users[] = $newUser;
    file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT));

    echo "Registration successful! <a href='../login.html'>Login here</a>";
}
?>
