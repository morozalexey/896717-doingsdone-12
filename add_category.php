<?php
/**
 * @var mysqli $con подключение к базе
 */
require_once 'init.php';
require_once 'helpers.php';
require_once 'models.php';

$user = check_user_auth($_SESSION);
if (empty($user)) {
    header('Location: index.php');
    exit;
}
$user = check_user_auth($_SESSION);
$user_id = $user['id'] ?? false;
if ($user_id) {
    $user_name = $user['name'];
}
$category_name = $_POST['name'] ?? false;
$cat_id = $_GET['cat_id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($category_name)) {
        $errors['name'] = 'Поле не заполнено';
    }
    $category_name = strip_tags($_POST['name']);
    if (empty($errors)) {
        insert_category_to_db($con, [$category_name, $user_id]);
        header('Location: /index.php');
    } else {
        $page_content = include_template(
            'add_category.php',
            [
                'errors' => $errors ?? false
            ]
        );
    }
} else {
    $page_content = include_template(
        'add_category.php',
        [
            'categories' => get_categories($con, $user_id),
            'tasks' => get_tasks_by_category($con, $cat_id, $user_id)
        ]
    );
}
$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content ?? false,
        'page_title' => 'Добавление категории',
        'user' => $user ?? false,
        'user_name' => $user_name ?? false
    ]
);
print($layout_content);
