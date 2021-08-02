<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
  ";
  $params = array(':user_id'=>$user_id);
  return fetch_all_query($db, $sql,$params);
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";
  $params = array(':user_id'=>$user_id,':item_id'=>$item_id);
  return fetch_query($db, $sql,$params);

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['amount'] + 1, $cart['cart_id']);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";
  $params = array(':item_id'=>$item_id,':user_id'=>$user_id,':amount'=>$amount);
  return execute_query($db, $sql,$params);
}

function update_cart_amount($db,$amount,$cart_id){
	$sql = "
    UPDATE 
      carts 
    SET 
      amount = :amount 
    WHERE 
      cart_id = :cart_id 
    LIMIT 1 
    ";
	$params = array(':amount'=>$amount, ':cart_id'=>$cart_id);
	return execute_query($db,$sql,$params);

}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  $params = array(':cart_id'=>$cart_id);
  return execute_query($db, $sql,$params);
}

function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  if (recording_order_history($db,$carts) === false){
    return false;
  }
  delete_user_carts($db, $carts[0]['user_id']);
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";
  $params = array(':user_id'=>$user_id);
  execute_query($db, $sql,$params);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

function recording_order_history($db,$carts){
  try{
    $db->beginTransaction();
    try{
      $sql = "
        INSERT INTO
          order_history(
            user_id
          )
        VALUES(:user_id)
      ";
      $params = array(':user_id'=>$carts[0]['user_id']);
      $id = lastinsertid_query($db,$sql,$params);
      
      foreach($carts as $cart){
        $sql = "
        INSERT INTO
          order_detail(
            order_id,
            item_id,
            amount,
            price
            )
        VALUES(:order_id,:item_id,:amount,:price)
      ";
      $params = array(':order_id'=>$id,':item_id'=>$cart['item_id'],':amount'=>$cart['amount'],':price'=>$cart['price']);
      execute_query($db, $sql, $params);
      }
    $db->commit();
    } catch (PDOException $e) {
      $db->rollback();
      throw $e;
    }
  } catch (PDOException $e) {
    set_error('データベース処理でエラーが発生しました。理由：'.$e->getMessage());
    return false;
  }
}
