<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//購入履歴を取得
function get_order_history($db,$user){
    $sql = '
        SELECT
            order_history.order_id,
            users.name,
            SUM(order_detail.amount*order_detail.price) AS total,
            order_history.created
        FROM 
            order_history
        INNER JOIN 
            order_detail
        ON
            order_history.order_id = order_detail.order_id
        INNER JOIN 
            users
        ON
        order_history.user_id = users.user_id
        ';
    //管理ユーザー以外はuser_idを照合して、自分の履歴のみ表示
    if ($user['type'] !== USER_TYPE_ADMIN){
        $sql .='
            WHERE
                order_history.user_id = :user_id
                ';
    }
    $sql .= '
        GROUP BY
            order_history.order_id
        ORDER BY
            order_history.order_id DESC
    ';
    if ($user['type'] !== USER_TYPE_ADMIN){
        $params = array(':user_id'=>$user['user_id']);
        return fetch_all_query($db, $sql,$params);
    }else{
        return fetch_all_query($db, $sql);
    }
}
//購入明細画面上部に表示するデータを取得
function get_total_price($db,$order_id,$user){
    $sql = '
        SELECT
            order_history.order_id,
            users.name,
            SUM(order_detail.amount*order_detail.price) AS total,
            order_history.created
        FROM 
            order_history
        INNER
            JOIN order_detail
        ON
            order_history.order_id = order_detail.order_id
        INNER JOIN 
            users
        ON
            order_history.user_id = users.user_id
        WHERE
            order_history.order_id = :order_id
            ';
        //管理ユーザー以外はuser_idを照合して、自分の履歴のみ表示
        if ($user['type'] !== USER_TYPE_ADMIN){
            $sql .='
                AND
                    order_history.user_id = :user_id
                ';
        }
        $sql .='
            GROUP BY
                order_history.order_id
            ';
    if ($user['type'] !== USER_TYPE_ADMIN){
        $params = array(':user_id'=>$user['user_id'],':order_id'=>$order_id);
    }else{
        $params = array(':order_id'=>$order_id);
    }
    return fetch_query($db, $sql,$params);
}
//購入明細を取得
function get_order_detail($db,$order_id,$user){
    $sql = '
        SELECT
            items.name,
            order_detail.price,
            order_detail.amount,
            order_detail.amount*order_detail.price AS subtotal
        FROM
            order_history
        INNER JOIN 
            order_detail
        ON
            order_history.order_id = order_detail.order_id
        INNER JOIN
            items
        ON
            order_detail.item_id = items.item_id
        WHERE
            order_history.order_id = :order_id
        ';
    //管理ユーザー以外はuser_idを照合して、自分の履歴のみ表示
    if ($user['type'] !== USER_TYPE_ADMIN){
        $sql .='
            AND
                order_history.user_id = :user_id
            ';
        $params = array(':user_id'=>$user['user_id'],':order_id'=>$order_id);
    }else{
        $params = array(':order_id'=>$order_id);
    }
    return fetch_all_query($db, $sql,$params);
}