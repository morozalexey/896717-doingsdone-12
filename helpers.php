<?php
const SECONDS_IN_HOUR = 3600;
const HOURS_IN_DAY = 24;
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция возвращает число задач для переданного проекта. Пройдя по массиву с задачами, функция сравнит значение выбранной id категории ($category_id) со значением ключа ['cat_id'] в каждой задаче. При совпадении она приплюсует единицу к $count.
 *
 * @param array $tasks принимает массив
 * @param integer $category_id принимает значение id категории
 *
 * @return integer колличество задач в проектке
 */
function task_сount($tasks, $category_id)
{
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['cat_id'] === $category_id) {
            $count++;
        }
    }
    return $count;
}

/**
 * Функция считает разницу между текущей датой и датой задачи, переводя дату задачи в timstamp и приводя  разицу между значениями к целому числу
 *
 * @param string принимает дату задачи
 *
 * @return integer разницу между двумя значениями в часах
 */
function is_deadline($task_date)
{
    $current_date = time();
    $task_date_to_timestamp = strtotime($task_date);
    $diff = floor(($task_date_to_timestamp - $current_date)/SECONDS_IN_HOUR);
    return ($diff <= HOURS_IN_DAY);
}

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
function get_tasks($con)
{
    $sql = 'SELECT id, name, date, cat_id, file, done FROM task';
    $stmt = mysqli_prepare($con, $sql);
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
    $sql = 'INSERT INTO task (name, date, cat_id, file, user_id, dt_add) VALUES (?, ?, ?, ?, 1, NOW())';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    return mysqli_stmt_execute($stmt);
}
