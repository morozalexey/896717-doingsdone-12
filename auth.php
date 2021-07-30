<?php
require_once 'helpers.php';
require_once 'models.php';
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email']);
    $pass = $_POST['pass'];
    $user = create_user($con, $email);
    $hash = $user['pass'];

    $required = ['email', 'pass'];
    $errors = [];
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

    if (!count($errors) AND $user) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit();
    } else {
        $page_content = include_template('auth.php', ['form' => $_POST, 'errors' => $errors]);
    }

} else {
    $page_content = include_template('auth.php');

    if (isset($_SESSION['user'])) {
        header("Location: index.php");
        exit();
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
