<?php
require_once 'helpers.php';
require_once 'init.php';
$show_complete_tasks = rand(0, 1);
$user_id = 1;
$cat_id = $_GET['cat_id'] ?? false;
layout_content($con, $cat_id, $user_id, $show_complete_tasks);