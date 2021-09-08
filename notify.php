<?php
require_once 'helpers.php';
require_once 'models.php';
require_once 'init.php';
require_once 'vendor/autoload.php';

$transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525);
$transport->setUsername("75f3c8c888f4c0");
$transport->setPassword("d3bf00f9a2376d");

$mailer = new Swift_Mailer($transport);

$users = get_users_tasks_today($con);
$mailing = [];

foreach ($users as $user) {
    $mailing[$user['id']]['user_name'] = $user['task_name'];
    $mailing[$user['id']]['email'] = $user['email'];
    $mailing[$user['id']]['task'][] = [
        'title' => $user['task_name'],
        'deadline' => $user['date']
    ];
};

foreach ($mailing as $task) {
    $letter = new Swift_Message();
    $letter->setSubject("Уведомление от сервиса «Дела в порядке»");
    $letter->setFrom('keks@phpdemo.ru');
    $letter->setTo($user['email']);

    $message = 'Уважаемый, ' . $user['user_name'] .'\n';

    foreach ($task['task'] as $task) {
        $message .= 'У вас запланирована задача: ';
        $message .=  $task['title'];
        $message .= ' на ' . date('d.m.Y', strtotime($task['deadline']));
        $message .= '\n';
    }

    $letter->addPart($message . '<br>', 'text/html');
    $res = $mailer->send($letter);

    if ($res) {
        print('Рассылка успешно отправлена');
    } else {
        print('Не удалось отправить рассылку');
    }
}















// $users_id_sql = 'SELECT DISTINCT user_id FROM task WHERE DATEDIFF (date, NOW()) <= 1 AND done = 0';
// $stmt = db_get_prepare_stmt($con, $users_id_sql);
// mysqli_stmt_execute($stmt);
// $res1 = mysqli_stmt_get_result($stmt);


// if ($res1 && mysqli_num_rows($res1)) {
//     $users_id = mysqli_fetch_all($res1, MYSQLI_ASSOC);

//     $user_info_sql = 'SELECT name, email FROM users';
//     $stmt2 = db_get_prepare_stmt($con, $user_info_sql);
//     mysqli_stmt_execute($stmt2);
//     $res2 = mysqli_stmt_get_result($stmt2);

//     if ($res2 && mysqli_num_rows($res2)) {
//         $user_info = mysqli_fetch_all($res2, MYSQLI_ASSOC);

//         $recipients = [];

//         foreach ($user_info as $user) {
//             $recipients[$user['email']] = $user['email'];
//         }

        // $message = new Swift_Message();
        // $message->setSubject("Самые горячие гифки за этот месяц");
        // $message->setFrom(['keks@phpdemo.ru' => 'GifTube']);
        // $message->setBcc($recipients);

        // $msg_content = include_template('month_email.php', ['gifs' => $gifs]);
        // $message->setBody($msg_content, 'text/html');

        // $result = $mailer->send($message);

        // if ($result) {
        //     print("Рассылка успешно отправлена");
        // }
        // else {
        //     print("Не удалось отправить рассылку");
        // }
//     }
// }




// $tasks_query = 'SELECT users.id, users.name, users.email, task.name, task.date FROM task JOIN users ON task.user_id = users.id WHERE DATEDIFF (task.date, NOW()) <= 1 AND task.done = 0';
// $stmt = db_get_prepare_stmt($con, $super_query);
// mysqli_stmt_execute($stmt);
// $res = mysqli_stmt_get_result($stmt);
// $super_res = mysqli_fetch_all($res, MYSQLI_ASSOC);


// $mail_query = 'SELECT DISTINCT users.email, users.name FROM task JOIN users ON task.user_id = users.id WHERE DATEDIFF (task.date, NOW()) <= 1 AND task.done = 0';
// $stmt = db_get_prepare_stmt($con, $mail_query);
// mysqli_stmt_execute($stmt);
// $res = mysqli_stmt_get_result($stmt);
// $mail_query_res = mysqli_fetch_all($res, MYSQLI_ASSOC);



// $emails = [];

// foreach ($mail_query_res as $item) {
//     //$emails[$item['email']] = $item['name'];

//     $tasks_query = 'SELECT task.name, task.date FROM task JOIN users ON task.user_id = users.id WHERE users.email = ? AND DATEDIFF (task.date, NOW()) <= 1 AND task.done = 0';
//     $stmt = db_get_prepare_stmt($con, $tasks_query, [$item['email']]);
//     mysqli_stmt_execute($stmt);
//     $res = mysqli_stmt_get_result($stmt);
//     $tasks_query_res = mysqli_fetch_all($res, MYSQLI_ASSOC);
// }

// var_dump($tasks_query_res);



// // $sql2 = 'SELECT name, email FROM users';
// // $stmt2 = db_get_prepare_stmt($con, $sql2);
// // mysqli_stmt_execute($stmt2);
// // $res2 = mysqli_stmt_get_result($stmt2);
// // var_dump (mysqli_fetch_all($res2, MYSQLI_ASSOC));

// //var_dump($_SESSION);


// // $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
// // $transport->setUsername("keks@phpdemo.ru");
// // $transport->setPassword("htmlacademy");

// // $mailer = new Swift_Mailer($transport);
