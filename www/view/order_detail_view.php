<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>購入明細</title>
        <link rel="stylesheet" href="<?php print h(STYLESHEET_PATH . 'cart.css'); ?>">
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        <h1>購入明細</h1>
        <div class="container">
            <?php include VIEW_PATH . 'templates/messages.php'; ?>
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
                    <tr>
                        <td><?php print h(number_format($order['order_id'])); ?></td>
                        <?php if ($user['type'] === USER_TYPE_ADMIN){?>
                            <td><?php print h($order['name']); ?></td>
                        <?php } ?>
                        <td><?php print h($order['created']); ?></td>
                        <td><?php print h(number_format($order['total'])); ?>円</td>
                        <td><a href = "order.php" class="btn btn-primary">戻る</a></td>
                    </tr>
                    </tbody>
                </table>
                
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th>商品名</th>
                        <th>商品価格</th>
                        <th>購入数</th>
                        <th>小計</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($details as $detail){ ?>
                    <tr>
                        <td><?php print h($detail['name']); ?></td>
                        <td><?php print h(number_format($detail['price'])); ?>円</td>
                        <td><?php print h(number_format($detail['amount'])); ?>個</td>
                        <td><?php print h(number_format($detail['subtotal'])); ?>円</td>
                    </tr>
                    
                    <?php } ?>
                    </tbody>
                </table>
        </div>
    </body>
</html>