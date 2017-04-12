<?php
/**
 * Created by PhpStorm.
 * User: colinleung
 * Date: 3/8/2016
 * Time: 1:46 AM
 */
?>
<?= Helper_Filesearch::get_view_apply_values($city, $language, 'contributor/register_steps', array('step'=>2))?>
<div>成功記錄<?=$nickname;?>的登入資料，確認電郵已發送到<?=$email?></div>
<?= Helper_Filesearch::get_view_apply_values($city, $language, 'user/resend', array('email'=>$email)); ?>
