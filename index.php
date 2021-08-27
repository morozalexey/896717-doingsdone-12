<?php
require_once 'helpers.php';
require_once 'init.php';

$user = check_user_auth($_SESSION);
$is_auth = isset($user['id']) ? $user['id'] : false;
if ($is_auth) {
    $user_name = $user['name'];
}
$task_checked = $_GET['check'] ?? false;
$task_id = $_GET['task_id'] ?? false;
$show_complete_tasks = $_GET['show_completed'] ?? 0;
$cat_id = $_GET['cat_id'] ?? false;
$search = $_GET['search'] ?? false;
$tasks_controls= $_GET['tasks-controls'] ?? false;

if (!empty($user)) {
    if ($cat_id) {
        $page_content = include_template(
            'main.php',
            [
                'categories' => get_categories($con, $is_auth),
                'tasks' => get_tasks_by_category($con, $cat_id),
                'all_tasks' => get_tasks($con, $is_auth),
                'show_complete_tasks' => $show_complete_tasks,
                'cat_id' => $cat_id
            ]
        );
        if (empty(get_tasks_by_category($con, $cat_id))) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    //полнотекстовый поиск
    } elseif ($search) {
        $page_content = include_template(
            'main.php',
            [
                'categories' => get_categories($con, $is_auth),
                'tasks' => get_tasks_by_search($con, [$search]),
                'all_tasks' => get_tasks($con, $is_auth),
                'show_complete_tasks' => $show_complete_tasks
            ]
        );
        if (empty(get_tasks_by_search($con, [$search]))) {
            $page_content = include_template('search.php');
        }
    //переключатель выполненных задач
    } elseif ($show_complete_tasks) {
        $page_content = include_template(
            'main.php',
            [
                'categories' => get_categories($con, $is_auth),
                'tasks' => get_tasks($con, $is_auth),
                'show_complete_tasks' => $show_complete_tasks,
                'cat_id' => $cat_id
            ]
        );
    //изменение параметра done (не работает)
    } elseif ($task_checked) {
        set_done ($con, $task_id, $task_checked);

        $page_content = include_template(
            'main.php',
            [
                'categories' => get_categories($con, $is_auth),
                'tasks' => get_tasks($con, $is_auth),
                'show_complete_tasks' => $show_complete_tasks,
                'cat_id' => $cat_id
            ]
        );
    //фильтр по срокам задач
    } elseif ($tasks_controls) {
        $task_filter = get_tasks_controls($con, $is_auth, $tasks_controls);

        $page_content = include_template(
            'main.php',
            [
                'categories' => get_categories($con, $is_auth),
                'tasks' => $task_filter,
                'show_complete_tasks' => $show_complete_tasks,
                'cat_id' => $cat_id
            ]
        );
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
