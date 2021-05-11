<?php
$show_complete_tasks = rand(0, 1);
$categories = [ 
    'inbox' => 'Входящие',
    'study' => 'Учеба',
    'work' => 'Работа',
    'home' => 'Домашние дела',
    'cars' => 'Авто'
];

$tasks = [    
    [
        'name' => 'Собеседование в IT компании',
        'date' => '2022-05-17',
        'category' => $categories['work'],
        'done' => false
    ], [
        'name' => 'Выполнить тестовое задание',
        'date' => '2022-05-17',
        'category' => $categories['study'],
        'done' => false
    ], [
        'name' => 'Сделать задание первого раздела',
        'date' => '2022-05-17',
        'category' => $categories['study'],
        'done' => true
    ], [
        'name' => 'Встреча с другом',
        'date' => '2022-05-17',
        'category' => $categories['inbox'],
        'done' => false
    ], [
        'name' => 'Купить корм для кота',
        'date' => '2021-05-09',
        'category' => $categories['home'],
        'done' => false
    ], [
        'name' => 'Заказать пиццу',
        'date' => '2022-05-17',
        'category' => $categories['home'],
        'done' => false
    ]
];

require_once 'helpers.php';

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