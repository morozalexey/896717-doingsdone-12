<?php
require_once 'helpers.php';
require_once 'models.php';
require_once 'init.php';

$user = check_user_auth($_SESSION);
$user_id = isset($user['id']) ? $user['id'] : false;
if ($user_id) {
    $user_name = $user['name'];
}
$task_checked = isset($_GET['check']) ? intval($_GET['check']) : false;
$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : false;

$show_complete_tasks = $_GET['show_completed'] ?? false;
$cat_id = $_GET['cat_id'] ?? false;
$search = $_GET['search'] ?? false;
$tasks_switch = $_GET['tasks-switch'] ?? false;

$all_tasks = get_tasks($con, $user_id);
$tasks_by_category = get_tasks_by_category($con, $user_id, $cat_id);
$categories = get_categories($con, $user_id);
$tasks_without_done = get_tasks_without_done($con, $user_id);

if (!empty($user)) {
    if ($cat_id) {

        $page_content = include_template(
            'main.php',
            [
                'categories' => $categories,
                'tasks' => $tasks_by_category,
                'all_tasks' => $all_tasks,
                'show_complete_tasks' => $show_complete_tasks,
                'cat_id' => $cat_id
            ]
        );
        if (empty($tasks_by_category)) {
            header("HTTP/1.0 404 Not Found");
            $page_content = include_template(
                '404.php'
            );
        }
    //полнотекстовый поиск
    } elseif ($search) {
        $page_content = include_template(
            'main.php',
            [
                'categories' => $categories,
                'tasks' => get_tasks_by_search($con, [$search]),
                'all_tasks' => $all_tasks,
                'show_complete_tasks' => $show_complete_tasks
            ]
        );
        if (empty(get_tasks_by_search($con, [$search]))) {
            $page_content = include_template('search.php');
        }
    //переключатель выполненных задач
    } elseif ($show_complete_tasks && !$tasks_switch) {
        $page_content = include_template(
            'main.php',
            [
                'categories' => $categories,
                'all_tasks' => $all_tasks,
                'tasks' => $all_tasks,
                'show_complete_tasks' => $show_complete_tasks,
                'cat_id' => $cat_id
            ]
        );
    //изменение параметра done
    } elseif ($task_id && $task_checked) {
        task_checkbox($con, $task_id);
        header('Location:/');
        exit;
    //фильтр по срокам задач
    } elseif ($tasks_switch) {
        $task_filter = get_tasks_controls($con, $user_id, $tasks_switch, $show_complete_tasks);

        $page_content = include_template(
            'main.php',
            [
                'categories' => $categories,
                'tasks' => $task_filter,
                'all_tasks' => $all_tasks,
                'show_complete_tasks' => $show_complete_tasks,
                'cat_id' => $cat_id
            ]
        );
    } else {
        $page_content = include_template(
            'main.php',
            [
                'categories' => $categories,
                'tasks' => $tasks_without_done,
                'all_tasks' => $all_tasks,
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
