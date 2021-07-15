<?php
// phpinfo();
require_once 'helpers.php';
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required = ['email', 'pass'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    $pass = $_POST['pass'];
    $hash = $user['pass'];

    if (!count($errors) AND $user) {
        if (password_verify($pass, $hash)) {
            $_SESSION['user'] = $user;
        } else {
            $errors['pass'] = 'Неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }



    if (count($errors)) {
    $page_content = include_template('auth.php', ['form' => $_POST, 'errors' => $errors]);
    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    $page_content = include_template('auth.php', []);

    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
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
