<!DOCTYPE html>
<html lang="ja">
  <head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    
    <title>商品一覧</title>
    <link rel="stylesheet" href="<?php print h(STYLESHEET_PATH . 'index.css'); ?>">
  </head>
  <body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    

    <div class="container">
      <h1>商品一覧</h1>
      <?php include VIEW_PATH . 'templates/messages.php'; ?>
      <div class="card-deck">
        <div class="row">
        <?php foreach($items as $item){ ?>
          <div class="col-6 item">
            <div class="card h-100 text-center">
              <div class="card-header">
                <?php print h($item['name']); ?>
              </div>
              <figure class="card-body">
                <img class="card-img" src="<?php print h(IMAGE_PATH . $item['image']); ?>">
                <figcaption>
                  <?php print h(number_format($item['price'])); ?>円
                  <?php if($item['stock'] > 0){ ?>
                    <form action="index_add_cart.php" method="post">
                      <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                      <input type="hidden" name="item_id" value="<?php print h($item['item_id']); ?>">
                      <input type="hidden" name="csrf_token" value="<?php print h($token); ?>">
                    </form>
                  <?php } else { ?>
                    <p class="text-danger">現在売り切れです。</p>
                  <?php } ?>
                </figcaption>
              </figure>
            </div>
          </div>
        <?php } ?>
        </div>
        <div class="container mb-5">
          <div class="row">
            <div class="col page_link">
              <?php if ($now > 1){?>
                      <a href="index.php?page=<?php print h(number_format($now-1))?>"> 前へ</a>
              <?php }else{
                      print h('前へ').'&nbsp'; 
                    } ?>
              <?php for($i = 1; $i <= $max; $i++){
                      if ($i === $now) { 
                        print '&nbsp'.h(number_format($now)).'&nbsp'; 
                      } else {?>
                          <a href="index.php?page=<?php print h(number_format($i))?> "> <?php print '&nbsp'.h(number_format($i)).'&nbsp'?> </a>
                  <?php } 
                    } ?>
              <?php if ($now < $max){?>
                      <a href="index.php?page=<?php print h(number_format($now+1))?>"> 次へ</a>
              <?php }else{
                      print '&nbsp'.h('次へ'); 
                    } ?>
            </div>
            <div class="col text-right page_number">
              <?php print h(number_format(count($all))).'件中'.'&nbsp'.h(number_format($num+1)).'-';
                        if(($num+MAX) <= (count($all))){
                          print h(number_format($num+MAX)).'件目の商品';
                        }else{
                          print h(number_format(count($all))).'件目の商品';
                        } 
                ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </body>
</html>