<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$con = mysqli_connect("localhost", "mysql", "mysql", "doingsdone");
if (!$con) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
}
