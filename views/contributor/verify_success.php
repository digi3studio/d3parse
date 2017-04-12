<?php
$username = isset($_GET['username']) ? $_GET['username'] : '';
?>
<?= Helper_Filesearch::get_view_apply_values($city, $language, 'contributor/register_steps', array('step'=>4))?>

<div>唔該哂！電郵地址成功確認！你已經完成註冊。</div>
<div>依家即刻登入啦</div>

<div class="no-forgot no-register <?=$form_state?>">
  <?= Helper_Filesearch::get_view_apply_values($city, $language, 'user/login' ,array('username'=>$username))->render(); ?>
</div>
