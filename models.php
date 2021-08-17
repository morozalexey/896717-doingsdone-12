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
 * Функция получает из базы массив задач
 *
 * @param $con подключение к базе
 *
 * @return array массив задач
 */
function get_tasks($con, $user_id)
{
    $sql = 'SELECT id, name, date, cat_id, file, done, user_id  FROM task WHERE user_id = ?';
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
function get_tasks_by_category($con, $cat_id)
{
    $sql = 'SELECT id, name, date, cat_id, file, done FROM task WHERE cat_id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$cat_id]);
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
    return $_POST[$name] ?? "";
}

/**
 * Функция получает из базы id категории
 *
 * @param $con подключение к базе
 * @param int $cat_name принимает имя категории
 *
 * @return int id категории
 */
function get_cat_id_by_cat_name($con, $cat_name)
{
    $sql = 'SELECT DISTINCT category.id FROM task JOIN category ON task.cat_id = category.id WHERE category.name = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$cat_name]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $arr = mysqli_fetch_array($res, MYSQLI_NUM);
    return $arr[0];
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
