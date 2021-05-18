<?php
require_once 'helpers.php';
require_once 'init.php';
$show_complete_tasks = rand(0, 1);

$cat_id = $_GET['cat_id'] ?? '';

if ($cat_id){
    $page_content = include_template(
        'main.php', 
        [
            'categories' => get_categories(1, $con), 
            'tasks' => get_tasks_by_category($con, $cat_id),
            'all_tasks' => get_tasks($con), 
            'show_complete_tasks' => $show_complete_tasks
        ]
    );
    
    if(empty(get_tasks_by_category($con, $cat_id))){
        $layout_content = include_template(
            'layout.php', 
            [
                'page_content' => 'Ошибка 404', 
                'page_title' => "Дела в порядке"
            ]
        ); 
    }else{
        $layout_content = include_template(
            'layout.php', 
            [
                'page_content' => $page_content, 
                'page_title' => "Дела в порядке"
            ]
        ); 
    }   

    print($layout_content);

}else{   
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
            'page_title' => 'Дела в порядке'
        ]
    ); 

    print($layout_content);
}