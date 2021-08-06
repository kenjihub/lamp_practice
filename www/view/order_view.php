<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>購入履歴</title>
        <link rel="stylesheet" href="<?php print h(STYLESHEET_PATH . 'cart.css'); ?>">
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        <h1>購入履歴</h1>
        <div class="container">
            <?php include VIEW_PATH . 'templates/messages.php'; ?>
            <?php if(count($orders) > 0){ ?>
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th>注文番号</th>
                        <?php if ($user['type'] === USER_TYPE_ADMIN){?>
                            <th>ユーザー名</th>
                        <?php } ?>
                        <th>購入日時</th>
                        <th>合計金額</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($orders as $order){ ?>
                    <tr>
                        <td><?php print h($order['order_id']); ?></td>
                        <?php if ($user['type'] === USER_TYPE_ADMIN){?>
                            <td><?php print h($order['name']); ?></td>
                        <?php } ?>
                        <td><?php print h($order['created']); ?></td>
                        <td><?php print h(number_format($order['total'])); ?>円</td>
                        <td>
                            <form method="post" action="order_detail.php">
                                <input type="submit" value="明細" class="btn btn-outline-primary">
                                <input type="hidden" name="order_id" value="<?php print h($order['order_id']); ?>">
                                <input type="hidden" name="csrf_token" value="<?php print h($token); ?>">
                            </form>
                        </td>
                    </tr>
                    
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                    <p>購入履歴はありません。</p>
            <?php } ?> 
        </div>
    </body>
</html>