<?php
require_once 'helpers.php';
require_once 'init.php';
$show_complete_tasks = rand(0, 1);
$user_id = !empty($_SESSION['user']['id']);
$cat_id = $_GET['cat_id'] ?? false;

if (!empty($_SESSION['user']['id'])) {
    if ($cat_id) {
        $page_content = include_template(
            'main.php',
            [
                'categories' => get_categories($con, $user_id),
                'tasks' => get_tasks_by_category($con, $cat_id),
                'all_tasks' => get_tasks($con),
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
                'categories' => get_categories($con, $user_id),
                'tasks' => get_tasks($con),
                'show_complete_tasks' => $show_complete_tasks
            ]
        );
    }

    $layout_content = include_template(
        'layout.php',
        [
            'page_content' => $page_content,
            'page_title' => 'Дела в порядке'
        ]
    );
} else {
    $layout_content = include_template(
        'guest.php',
        []
    );
}

print($layout_content);
// тестовый комент для склейки версий
