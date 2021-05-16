<?php
require_once 'helpers.php';
require_once 'init.php';
$show_complete_tasks = rand(0, 1);

/*шаблонизация*/
$page_content = include_template(
    'main.php', 
    [
        'categories' => get_categories(1, $con), 
        'tasks' => get_tasks($con), 
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