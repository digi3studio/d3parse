<?php
/**
 * Created by PhpStorm.
 * User: colinleung
 * Date: 31/7/2016
 * Time: 10:22 PM
 */

Helper_Template::instance()->link('css/carousel.css');

/* emmet
.facebook-email-register.multi-page-form>.carousel[data-active=1]>ul.box
li.selection>(.ui.button.icon.facebook>a>i.icon.facebook+{Facebook})+.br+(.ui.button.icon.password.page-control[data-item=2]>a>i.icon.ellipsis.horizontal+{EmailLogin})
li>form[method=post]>input:h[name=fbid]+input:h[name=fbtoken]+input:h[name=fbname]+input:h[name=code]+(.ui.left.icon.input>input[name=nickname placeholder=nickname]+i.icon.user)+.br+(.ui.left.icon.input>input[type=password name=password placeholder=password]+i.icon.lock)+.br+button.ui.button[type=submit]{submit}
*/

$amd_config = URL::site(Helper_Filesearch::get_media($city, $language,'js/contributor/config/register.js'));
$amd_config = str_replace('.js', '', $amd_config);

?>
<?= Helper_Filesearch::get_view_apply_values($city, $language, 'contributor/register_steps', array('step'=>1))?>

<div class="ui content">多謝 <?=$name?> 支持，請註冊</div>
<div id="email"><i class="ui icon mail"></i>你的電郵地址: <span id="value"><?=$email?></span></div>

<div class="facebook-email-register multi-page-form">
  <div class="carousel" data-active="1">
    <ul class="box">
      <li class="selection">
        <h3 class="ui"><i class="privacy icon"></i>請選擇登入方式</h3>
        <div class="ui button icon facebook"><a href="#"><i class="icon facebook"></i>facebook 登入</a></div>
        <div class="ui horizontal divider">或</div>
        <div class="ui button icon password page-control" data-item="2"><a href="#"><i class="icon ellipsis horizontal"></i>設定登入密碼</a></div>
      </li>

      <li>
        <form action="<?=URL::site('contributor/register_save')?>" method="post">
          <input type="hidden" name="fbid">
          <input type="hidden" name="fbtoken">
          <input type="hidden" name="fbname">
          <input type="hidden" name="code" value="<?=$code?>">
          <div class="ui left icon input">
            <input type="text" name="nickname" placeholder="暱稱" required="required" value="<?=$name?>"><i class="icon user"></i>
          </div>
          <div class="br"></div>
          <div class="ui left icon input">
            <input type="password" name="password" placeholder="密碼" required="required"><i class="icon lock"></i>
          </div>
          <div class="br"></div>
          <button class="ui button" type="submit">提交</button>
        </form>
      </li>

    </ul>
  </div>
</div>
<script data-main="<?=$amd_config?>" src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.3.3/require.min.js"></script>