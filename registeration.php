<?php
require_once 'helpers.php';
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required_fields = ['email', 'password', 'name'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    $user_name = $_POST['name'];
    $user_mail = $_POST['email'];
    $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // if (filter_var($user_mail, FILTER_VALIDATE_EMAIL) === false) {
    //     $errors['email'] = 'E-mail введён некорректно';
    // }

    // if (check_email_dublicate($con, $user_mail)) {
    //     $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    // }

    if (empty($errors)) {


        insert_user_to_db($con, [$user_name, $user_mail, $user_password]);

        if ($res && empty($errors)) {
            header("Location: /index.php");
            exit();
        }

    } else {
        $page_content = include_template(
            'registeration.php',
            [
                'errors' => $errors
            ]
        );
    }
}

$page_content = include_template(
    'registeration.php'
);

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'page_title' => 'Регистрация'
    ]
);

print($layout_content);
