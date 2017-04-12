<?php
/**
 * Created by PhpStorm.
 * User: colinleung
 * Date: 11/4/2017
 * Time: 6:08 PM
 */

/* emmet
.facebook-email-login.multi-page-form>.carousel[data-active=1]>ul.box

li.selection>div>(.ui.button.icon.facebook>a>i.icon.facebook+{Facebook})+.br+(.ui.button.icon.password.page-control[data-item=2]>a>i.icon.ellipsis.horizontal+{EmailLogin})+.br+span.page-control[data-item=4]{Register}
li>form[method=post]>input:h[name=fbid]+input:h[name=fbtoken]+input:h[name=fbname]+input:h[name=destination]+(.ui.left.icon.input>input[name=email placeholder=email]+i.icon.mail)+.br+(.ui.left.icon.input>input[type=password name=password placeholder=password]+i.icon.lock)+.br+button.ui.button[type=submit]{submit}+.br+span.page-control[data-item=3]{forgotPassword}
li>form[method=post]>(.ui.left.icon.input>input[name=email placeholder=email]+i.icon.mail)+.br+button.ui.button[type=submit]{send}
li>form[method=post]>(.ui.left.icon.input>input[name=username placeholder=email]+i.icon.mail)+.br+button.ui.button[type=submit]{send}
*/

Helper_Template::instance()->link('css/carousel.css');
Helper_Template::instance()->link('css/skin.css');

$amd_config = URL::site(Helper_Filesearch::get_media($city, $language,'js/user/config/login.js'));
$amd_config = str_replace('.js', '', $amd_config);

$default_username = isset($_GET['username']) ? htmlentities($_GET['username']) : '';
$destination = isset($destination)? $destination : '';
?>

<div class="facebook-email-login multi-page-form">
  <div class="carousel" data-active="1">
    <ul class="box">
      <li class="selection">
        <h3 class="ui"><i class="privacy icon"></i>請選擇登入方式</h3>

        <div class="ui button icon facebook"><a href="#"><i class="facebook icon"></i>facebook 登入</a></div>
        <div class="br"></div>
        <div class="ui button icon password page-control" data-item="2"><a href="#"><i class="ellipsis horizontal icon"></i>密碼登入</a></div>
        <div class="br"></div>
        <div class="register"><a class="page-control" data-item="4" href="/contributor/invite">新用戶請按此註冊</a></div>

      </li>

      <li>
        <div>
          <h3 class="ui"><i class="ellipsis horizontal icon"></i>密碼登入</h3>
          <form method="post" action="/user/login_submit" autocomplete="off">
            <input type="hidden" name="fbid">
            <input type="hidden" name="fbtoken">
            <input type="hidden" name="fbname">
            <input type="hidden" name="destination" value="<?=$destination?>">
            <div class="ui left icon input"><input title="email" name="username" type="email" placeholder="電郵地址" value="<?=$default_username?>" autocomplete="off" required="required"/><i class="mail icon"></i></div>
            <div class="br"></div>
            <div class="ui left icon input"><input title="password" name="password" type="password" placeholder="密碼" autocomplete="new-password" required="required"/><i class="lock icon"></i></div>
            <div class="br"></div>
            <button class="ui button" type="submit">登入</button>
            <div class="br"></div>
            <a class="forgot-password page-control" data-item="3" href="/user/reset_password">忘記密碼？</a>
          </form>
        </div>
      </li>

      <li>
        <h3><i class="help circle icon"></i>忘記密碼</h3>
        <div class="ui ordered steps alt">
          <div class="active step">
            <div class="content">
              <div class="title">填寫電&#x2060;郵地&#x2060;址</div>
              <div class="description">請填寫註冊的電郵地址</div>
            </div>
          </div>
          <div class="step">
            <div class="content">
              <div class="title">電郵驗&#x2060;証</div>
              <div class="description">檢查你的電郵</div>
            </div>
          </div>
          <div class="step">
            <div class="content">
              <div class="title">確認電&#x2060;郵驗&#x2060;証</div>
              <div class="description">按下電郵內的驗証連結</div>
            </div>
          </div>
          <div class="step">
            <div class="content">
              <div class="title">輸入新密&#x2060;碼</div>
              <div class="description"></div>
            </div>
          </div>
        </div>
        <div class="br"></div>
        <form method="post" action="/user/reset_password_submit" autocomplete="off">
          <div class="ui left icon input"><input title="email" name="username" type="email" placeholder="電郵地址" value="" autocomplete="off" required="required"/><i class="mail icon"></i></div>
          <div class="br"></div>
          <button class="ui button" type="submit">發出重設密碼電郵</button>
        </form>
      </li>

      <li>
        <h3><i class="write icon"></i>註冊成為網站成員</h3>
        <div>請填寫你資助《勁揪體籌旗造字計劃》時，所使用的電郵地址。
        </div>
        <div class="br"></div>
        <form method="post" action="/contributor/invite_submit" autocomplete="off">
          <div class="ui left icon input"><input title="email" name="username" type="email" placeholder="電郵地址" value="" autocomplete="off" required="required"/><i class="mail icon"></i></div>
          <div class="br"></div>
          <button class="ui button" type="submit">發出註冊電郵</button>
        </form>
      </li>
    </ul>
  </div>
</div>

<script data-main="<?=$amd_config?>" src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.3.3/require.min.js"></script>