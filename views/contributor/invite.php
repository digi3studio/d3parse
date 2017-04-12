<?php
/**
 * Created by PhpStorm.
 * User: colinleung
 * Date: 10/8/2016
 * Time: 1:58 AM
 */?>

<div class="ui container">
<div>《勁揪體》陸續發出邀請信，如果你想提早收到邀請，請填寫你資助《勁揪體籌旗造字計劃》時，所使用的電郵地址。</div>

<form action="<?=URL::site('contributor/invite_submit')?>" method="post" autocomplete="off" >
  <input type="text" name="email" placeholder="電郵地址" autocomplete="off" />
  <input type="submit" value="提交"/>
</form>
</div>