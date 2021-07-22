<?php
require_once 'helpers.php';
require_once 'models.php';
require_once 'init.php';
$show_complete_tasks = rand(0, 1);
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $is_auth = $user['id'];
    $user_name = $user['name'];
}

$cat_id = $_GET['cat_id'] ?? false;

if (!empty($user)) {
    if ($cat_id) {
        $page_content = include_template(
            'main.php',
            [
                'categories' => get_categories($con, $is_auth),
                'tasks' => get_tasks_by_category($con, $cat_id),
                'all_tasks' => get_tasks($con, $is_auth),
                'show_complete_tasks' => $show_complete_tasks
            ]
        );
        if (empty(get_tasks_by_category($con, $cat_id))) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    } else {
        $page_content = include_template(
            'main.php',
            [
                'categories' => get_categories($con, $is_auth),
                'tasks' => get_tasks($con, $is_auth),
                'show_complete_tasks' => $show_complete_tasks
            ]
        );
    }

    $layout_content = include_template(
        'layout.php',
        [
            'page_content' => $page_content,
            'page_title' => 'Дела в порядке',
            'user' => $user,
            'user_name' => $user_name
        ]
    );
} else {
    $layout_content = include_template(
        'guest.php'
    );
}

print($layout_content);
echo '<pre>';

print_r($user);


