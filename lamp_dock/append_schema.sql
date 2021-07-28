-- --------------------------------------------------------

--
-- テーブルの構造 `order_history`
--

CREATE TABLE `order_history` (
  `order_id` int(11) NOT NULL PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `order_details`
--

SELECT`
order_history.order_id,
order_history.user_id,
order_history.item_id,
order_history.amount,
order_history.price,
order_history.created,
items.name`
FROM'order_history'
INNER JOIN 'items'
ON 'order_history.item_id = items.item_id'
GROUP BY 'order_id';

-- --------------------------------------------------------
