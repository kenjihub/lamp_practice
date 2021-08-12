<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

//公開のアイテムを全て取得
$all = get_open_items($db, false);
//全体のページ数を取得
$max = count_max_page($db,$all);

//GETでページ数を取得
$page = (int)get_get('page');
//現在の表示ページ番号を取得
$now = get_now_page($page,$max);
//LIMIT句の開始位置を取得
$num = get_start_number($now);

$items = get_open_items($db, $num);
$token = get_csrf_token();
include_once VIEW_PATH . 'index_view.php';