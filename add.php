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

    if (empty($errors)) {

        if (!empty($_FILES['file']['name'])) {
            $tmp_name = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $filename = uniqid() . $filename;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
        }

        $task_file = 'uploads/' . $filename;

        $sql = 'INSERT INTO task (name, date, cat_id, file, user_id, dt_add) VALUES (?, ?, ?, ?, 1, NOW())';
        $stmt = db_get_prepare_stmt($con, $sql, [$task_name, $task_date, $task_cat_id, $task_file]);
        $res = mysqli_stmt_execute($stmt);

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
} else {
    if ($cat_id) {
        $page_content = include_template(
            'add.php',
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
            'add.php',
            [
                'categories' => get_categories($con, $user_id),
                'tasks' => get_tasks($con),
                'show_complete_tasks' => $show_complete_tasks
            ]
        );
    }

}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'page_title' => 'Дела в порядке'
    ]
);

print($layout_content);
