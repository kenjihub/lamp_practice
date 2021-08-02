-- --------------------------------------------------------

--【購入履歴テーブル】
-- 購入ボタンを押したときにcartsデータを消す前にこちらに挿入

CREATE TABLE `order_history` (
  `order_id` int(11) NOT NULL PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--【購入詳細テーブル】
-- order_historyからorder_idを取得して、注文ごとの詳細を記録

CREATE TABLE `order_detail` (
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 【購入履歴画面】＜一般ユーザーに表示する場合＞
-- WEHER句に該当ユーザーのuser_idを入れて個別の詳細を表示する

SELECT'
order_history.order_id,
order_detail.amount,
order_detail.price,
order_history.created'
FROM'order_history'
INNER JOIN 'order_detail'
ON 'order_history.order_id = order_detail.order_id'
WHERE 'order_history.user_id = :user_id'
ORDER BY 'order_history.order_id DESC';

-- --------------------------------------------------------

-- 【購入履歴画面】＜管理ユーザーに表示する場合＞
-- ORDER BYで新着順に表示する

SELECT`
order_history.order_id,
order_detail.amount,
order_detail.price,
order_history.created,
users.name`
FROM'order_history'
INNER JOIN 'order_detail'
ON 'order_history.order_id = order_detail.order_id'
INNER JOIN 'users'
ON 'order_history.user_id = users.user_id'
ORDER BY 'order_history.order_id DESC';

-- --------------------------------------------------------

-- 【購入詳細画面】＜一般ユーザーに表示する場合＞
-- WEHER句に該当ユーザーのuser_idを入れて個別の詳細を表示する
-- ORDER BYで新着順に表示する

SELECT`
order_history.order_id,
order_detail.amount,
order_detail.price,
order_history.created,
items.name`
FROM'order_history'
INNER JOIN 'order_detail'
ON 'order_history.order_id = order_detail.order_id'
INNER JOIN 'items'
ON 'order_order_detail.item_id = items.item_id'
WHERE 'order_history.user_id = :user_id';

-- --------------------------------------------------------

-- 【購入詳細画面】＜管理ユーザーに表示する場合＞
-- 全ユーザーの詳細を表示するので、分かりやすいようにユーザーnameも表示する

SELECT`
order_history.order_id,
order_detail.amount,
order_detail.price,
order_history.created,
items.name,
users.name`
FROM'order_history'
INNER JOIN 'order_detail'
ON 'order_history.order_id = order_detail.order_id'
INNER JOIN 'items'
ON 'order_order_detail.item_id = items.item_id'
INNER JOIN 'users'
ON 'order_history.user_id = users.user_id'

-- --------------------------------------------------------