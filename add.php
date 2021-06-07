<?php
require_once 'helpers.php';
require_once 'init.php';
$show_complete_tasks = rand(0, 1);
$user_id = 1;
$cat_id = $_GET['cat_id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required_fields = ['name', 'date'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    $task_name = $_POST['name'];
    $task_date = $_POST['date'];
    $task_cat_id = get_cat_id_by_cat_name($con, $_POST['project']);
    $task_file = null;

    if (empty($errors)) {

        if (!empty($_FILES['file']['name'])) {
            $tmp_name = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $filename = uniqid() . $filename;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $task_file = 'uploads/' . $filename;
        }

        insert_task_to_db($con, [$task_name, $task_date, $task_cat_id, $task_file]);

        header('Location: /index.php');

    } else {
        $page_content = include_template(
            'add.php',
            [
                'categories' => get_categories($con, $user_id),
                'tasks' => get_tasks($con),
                'show_complete_tasks' => $show_complete_tasks,
                'errors' => $errors
            ]
        );
    }
}

$page_content = include_template(
    'add.php',
    [
        'categories' => get_categories($con, $user_id),
        'tasks' => get_tasks($con),
        'show_complete_tasks' => $show_complete_tasks
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'page_title' => 'Дела в порядке'
    ]
);

print($layout_content);
