<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
$order_id = get_input('order_id');
$token = get_post('csrf_token');

//トークンを照合
if(is_valid_csrf_token($token) === true){
  //購入明細を取得
  $order = get_total_price($db,$order_id,$user);
  $details = get_order_detail($db,$order_id,$user);
}else{
  set_error('不正なリクエストです。');
  redirect_to(ORDER_URL);
}
//明細を取得できなかった場合（※デベロッパーツールで不正に注文番号を入力する等）
if(!$order || !$details){
  set_error('明細を取得できませんでした。');
  redirect_to(ORDER_URL);
}

include_once VIEW_PATH . 'order_detail_view.php';