<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$cart_id = get_input('cart_id');
$amount = get_input('amount');
$token = get_post('csrf_token');

if(is_valid_csrf_token($token) === true){
  if(update_cart_amount($db,$amount,$cart_id)){
    set_message('購入数を更新しました。');
  } else {
    set_error('購入数の更新に失敗しました。');
  } 
}else{
  set_error('不正なリクエストです。');
}
redirect_to(CART_URL);