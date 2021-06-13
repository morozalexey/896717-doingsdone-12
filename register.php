<?php
require_once 'helpers.php';
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required_fields = ['email', 'password', 'name'];
    $errors = [];

    $user_name = $_POST['name'];
    $user_mail = $_POST['email'];
    $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    if (!empty($user_mail)) {
        if (!filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        } elseif (check_email_dublicate($con, $user_mail)) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if (empty($errors)) {

        insert_user_to_db($con, [$user_name, $user_mail, $user_password]);

        header('Location: /index.php');

    } else {
        $page_content = include_template(
            'register.php',
            [
                'errors' => $errors
            ]
        );
    }
} else {
    $page_content = include_template(
        'register.php'
    );
}

$layout_content = include_template(
    'layout_reg.php',
    [
        'page_content' => $page_content,
        'page_title' => 'Регистрация'
    ]
);

print($layout_content);
var_dump(check_email_dublicate($con, $user_mail));
