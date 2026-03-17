<?php
// php/login.php
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

function normalizeProjects($projects) {
    if (!is_array($projects)) {
        return [];
    }

    $normalized = [];
    foreach ($projects as $project) {
        if (is_array($project)) {
            $title = trim((string) ($project['title'] ?? ''));
            $link = trim((string) ($project['link'] ?? ''));
            if ($title !== '') {
                $normalized[] = [
                    'title' => $title,
                    'link' => $link
                ];
            }
            continue;
        }

        $title = trim((string) $project);
        if ($title !== '') {
            $normalized[] = [
                'title' => $title,
                'link' => ''
            ];
        }
    }

    return $normalized;
}

function normalizeCertificates($certificates) {
    if (!is_array($certificates)) {
        return [];
    }

    $normalized = [];
    foreach ($certificates as $certificate) {
        if (is_array($certificate)) {
            $name = trim((string) ($certificate['name'] ?? ''));
            $image = trim((string) ($certificate['image'] ?? ''));
            if ($name !== '') {
                $normalized[] = [
                    'name' => $name,
                    'image' => $image
                ];
            }
            continue;
        }

        $name = trim((string) $certificate);
        if ($name !== '') {
            $normalized[] = [
                'name' => $name,
                'image' => ''
            ];
        }
    }

    return $normalized;
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
                'certificates' => normalizeCertificates($user['certificates'] ?? []),
                'projects' => normalizeProjects($user['projects'] ?? [['title' => 'Online Student Portfolio & Registration System', 'link' => '']])
            ];
            header("Location: ../portfolio.html");
            exit;
        }
    }

    echo "Invalid email or password. <a href='../login.html'>Try again</a>";
}
?>
