<?php
require_once 'helpers.php';
require_once 'init.php';
require_once 'models.php';

$show_complete_tasks = rand(0, 1);
$user = check_user_auth($_SESSION);
$user_id = isset($user['id']) ? $user['id'] : false;
if ($user_id) {
    $user_name = $user['name'];
}
$cat_id = $_GET['cat_id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fields = ['name', 'category'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    $task_name = $_POST['name'];
    $task_date = $_POST['date'];
    $task_cat_id = intval($_POST['category']);
    $task_file = null;

    if (empty($errors)) {

        if (!empty($_FILES['file']['name'])) {
            $tmp_name = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $filename = uniqid() . $filename;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $task_file = 'uploads/' . $filename;
        }

        insert_task_to_db($con, [$task_name, $task_date, $task_cat_id, $task_file, $user_id]);

        header('Location: /index.php');

    } else {
        $page_content = include_template(
            'add.php',
            [
                'categories' => get_categories($con, $user_id),
                'tasks' => get_tasks($con, $user_id),
                'show_complete_tasks' => $show_complete_tasks,
                'errors' => $errors
            ]
        );
    }
} else {
    $page_content = include_template(
        'add.php',
        [
            'categories' => get_categories($con, $user_id),
            'tasks' => get_tasks($con, $user_id),
            'show_complete_tasks' => $show_complete_tasks,
            'all_tasks' => get_tasks($con, $user_id),
            'cat_id' => $cat_id
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

print($layout_content);

