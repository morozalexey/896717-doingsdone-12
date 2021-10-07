<?php
/**
 * @var mysqli $con подключение к базе
 */
require_once 'init.php';
require_once 'helpers.php';
require_once 'models.php';

$user = check_user_auth($_SESSION);
if (!empty($user)) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['email', 'pass'];
    $errors = [];
    $email = trim($_POST['email']);
    $pass = $_POST['pass'];
    $user = create_user($con, $email);
    $hash = $user['pass'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        } elseif (!check_email($con, $email)) {
            $errors['email'] = 'Такой пользователь не найден';
        } elseif (!password_verify($pass, $hash)) {
            $errors['pass'] = 'Неверный пароль';
        }
    }
    if (!count($errors) || $user) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit();
    }
}
$page_content = include_template('auth.php', ['errors' => $errors ?? false]);
$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content ?? false,
        'page_title' => 'Дела в порядке'
    ]
);
print($layout_content);
