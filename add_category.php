<?php
require_once 'helpers.php';
require_once 'init.php';
$user = check_user_auth($_SESSION);
$is_auth = isset($user['id']) ? $user['id'] : false;
if ($is_auth) {
    $user_name = $user['name'];
}
$cat_id = $_GET['cat_id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category_name = htmlspecialchars($_POST['name']);

    if (empty($category_name)) {
        $errors['name'] = 'Поле не заполнено';
    }

    if (empty($errors)) {

        insert_category_to_db($con, [$category_name, $is_auth]);

        header('Location: /index.php');

    } else {
        $page_content = include_template(
            'add_category.php',
            [
                'errors' => $errors,
                'categories' => get_categories($con, $is_auth),
                'tasks' => get_tasks_by_category($con, $cat_id)
            ]
        );
    }
} else {
    $page_content = include_template(
        'add_category.php',
        [
            'categories' => get_categories($con, $is_auth),
            'tasks' => get_tasks_by_category($con, $cat_id)
        ]
    );
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'page_title' => 'Добавление категории',
        'user' => $user,
        'user_name' => $user_name
    ]
);

print($layout_content);
