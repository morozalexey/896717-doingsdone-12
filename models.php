<?php
require_once 'helpers.php';

/**
 * Функция получает из базы массив категорий
 *
 * @param $con подключение к базе
 * @param int $user_id принимает id пользователя
 *
 * @return array массив с категориями
 */
function get_categories($con, $user_id)
{
    $sql = 'SELECT id, name, user_id FROM category WHERE user_id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция получает из базы массив всех задач
 *
 * @param $con подключение к базе
 *
 * @return array массив задач
 */
function get_tasks($con, $user_id)
{
    $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция получает из базы массив задач за исключением выполненных
 *
 * @param $con подключение к базе
 *
 * @return array массив задач
 */
function get_tasks_without_done($con, $user_id)
{
    $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ? AND done = 0';
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция получает из базы массив задач по выбранной категории
 *
 * @param $con подключение к базе
 * @param int $cat_id принимает id категории
 *
 * @return array массив задач
 */
function get_tasks_by_category($con, $user_id, $cat_id)
{
    $sql = 'SELECT id, name, date, cat_id, file, done FROM task WHERE user_id = ? AND cat_id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id, $cat_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
* Функция для получения значений из POST-запроса.
*
* @param str $name название поля формы
*
* @return str значение поля формы
*/
function getPostVal($name)
{
    if (isset($_POST[$name])) {
        return strip_tags($_POST[$name]) ?? "";
    }
}

/**
 * Функция добавляет в базу новую задачу
 *
 * @param $con подключение к базе
 * @param arr $data принимает массив с данными
 *
 * @return bool возвращает true/false
 */
function insert_task_to_db($con, $data=[])
{
    $sql = 'INSERT INTO task (name, date, cat_id, file, user_id, dt_add) VALUES (?, ?, ?, ?, ?, NOW())';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    return mysqli_stmt_execute($stmt);
}

/**
 * Функция добавляет в базу нового пользователя
 *
 * @param $con подключение к базе
 * @param arr $data принимает массив с данными
 *
 * @return bool возвращает true/false
 */
function insert_user_to_db($con, $data=[])
{
    $sql = 'INSERT INTO users (name, email, pass, dt_add) VALUES (?, ?, ?, NOW())';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    return mysqli_stmt_execute($stmt);
}

/**
 * Функция проверяет введенный email на дубликаты
 *
 * @param $con подключение к базе
 * @param str $user_mail принимает email от пользователя
 *
 * @return bool возвращает true/false
 */
function check_email_dublicate($con, $user_mail)
{
    $email = mysqli_real_escape_string($con, $user_mail);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        return true;
    }
}

/**
* Функция получает данные из базы и формирует массив данных пользователя
*
* @param $con подключение к базе
* @param $email электронный адрес пользователя
*
* @return array массив с данными пользователя или null
*/
function create_user($con, $email)
{
    $sql = 'SELECT id, name, email, pass FROM users WHERE email = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
}

/**
* Функция проверяет, есть ли в базе email введенный в форму авторизации
*
* @param $con подключение к базе
* @param $email электронный адрес пользователя
*
* @return true или false
*/
function check_email($con, $email)
{
    $sql = 'SELECT id FROM `users` WHERE email = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($res) > 0) {
        return true;
    }
}

/**
 * Функция добавляет в базу новую категорию
 *
 * @param $con подключение к базе
 * @param arr $data принимает массив с данными
 *
 * @return bool возвращает true/false
 */
function insert_category_to_db($con, $data=[])
{
    $sql = 'INSERT INTO category (name, user_id) VALUES (?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    return mysqli_stmt_execute($stmt);
}

/**
 * Функция получает из базы массив задач по выбранной категории
 *
 * @param $con подключение к базе
 * @param int $search_data принимает id категории
 * @param int $user_id принимает id юзера
 *
 * @return array массив задач
 */
function get_tasks_by_search($con, $data=[])
{
    $sql = 'SELECT id, name, date, cat_id, file, done FROM task WHERE MATCH(name) AGAINST (?)';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция получает из базы массив задач в зависимости от переданного параметра временного промежутка
 *
 * @param $con подключение к базе
 * @param $user_id айдишник пользователя
 * @param $tasks_controls
 *
 * @return array массив задач
 */
function get_tasks_controls($con, $user_id, $tasks_switch, $show_complete_tasks)
{
    if ($tasks_switch === 'Повестка дня' && !$show_complete_tasks) {
        $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ? AND DATEDIFF (date, NOW()) = 1 AND done = 0';
    } elseif ($tasks_switch === 'Повестка дня' && $show_complete_tasks) {
        $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ? AND DATEDIFF (date, NOW()) = 1';
    }

    if ($tasks_switch === 'Завтра' && !$show_complete_tasks) {
        $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ? AND DATEDIFF (date, NOW()) > 1 AND done = 0';
    } elseif ($tasks_switch === 'Завтра' && $show_complete_tasks) {
        $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ? AND DATEDIFF (date, NOW()) > 1';
    }

    if ($tasks_switch === 'Просроченные' && !$show_complete_tasks) {
        $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ? AND DATEDIFF (date, NOW()) <= 0   AND done = 0';
    } elseif ($tasks_switch === 'Просроченные' && $show_complete_tasks) {
        $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ? AND DATEDIFF (date, NOW()) <= 0';
    }

    if ($tasks_switch === 'Все задачи' && !$show_complete_tasks) {
        $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ? AND done = 0';
    } elseif ($tasks_switch === 'Все задачи' && $show_complete_tasks) {
        $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE user_id = ?';
    }

    $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция меняет значение поле done
 *
 * @param $con подключение к базе
 * @param int $task_id принимает id задачи
 *
 */
function task_checkbox($con, $task_id)
{
    $sql = 'SELECT done  FROM task WHERE id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$task_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $res1 = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $result = $res1[0]['done'];
    if ($result === 1) {
        $sql = 'UPDATE task  SET done = 0 WHERE id = ?';
    } elseif ($result === 0) {
        $sql = 'UPDATE task  SET done = 1 WHERE id = ?';
    }
    $stmt = db_get_prepare_stmt($con, $sql, [$task_id]);
    mysqli_stmt_execute($stmt);
    return false;
}

/**
 * Функция получает из базы массив задач с истекшими сроками
 *
 * @param $con подключение к базе
 *
 * @return array массив задач
 */
function get_overdue_tasks($con)
{
    $sql = 'SELECT id, name, date, cat_id, file, done, user_id FROM task WHERE DATEDIFF (date, NOW()) <= 1 AND done = 0';
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция получаеn пользователей с задачами на сегодня
 *
 * @param $con подключение к базе
 *
 * @return array массив задач
 */
function get_users_tasks_today($con)
{
    $sql = "SELECT users.id, users.name as user_name, users.email, task.name as task_name, task.date FROM users JOIN task ON task.user_id = users.id WHERE task.done = 0 AND task.date = CURRENT_DATE()";
    $stmt = db_get_prepare_stmt($con, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}
