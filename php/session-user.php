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
};

$normalizeProjects = function ($projects) {
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
};

$normalizeCertificates = function ($certificates) {
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
};

unset($user['password']);

$user['education'] = $normalizeList($user['education'] ?? []);
$user['skills'] = $normalizeList($user['skills'] ?? []);
$user['certificates'] = $normalizeCertificates($user['certificates'] ?? []);
$user['projects'] = $normalizeProjects($user['projects'] ?? [['title' => 'Online Student Portfolio & Registration System', 'link' => '']]);

echo json_encode($user);
?>