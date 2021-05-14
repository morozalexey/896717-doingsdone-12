<?php
require_once 'helpers.php';

$show_complete_tasks = rand(0, 1);

/*подключаемся к БД*/
$con = mysqli_connect("localhost", "mysql", "mysql", "doingsdone");

/*получаем категории из базы*/
$user_id = 1;
$sql = 'SELECT * FROM category WHERE user_id = ?';
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$categories = mysqli_fetch_all($res, MYSQLI_ASSOC);

/*получаем задачи из базы*/
$sql = 'SELECT * FROM task';
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

/*шаблонизация*/
$page_content = include_template(
    'main.php', 
    [
        'categories' => $categories, 
        'tasks' => $tasks, 
        'show_complete_tasks' => $show_complete_tasks
    ]
);

$layout_content = include_template(
    'layout.php', 
    [
        'page_content' => $page_content, 
        'page_title' => "Дела в порядке"
    ]
); 

print($layout_content);