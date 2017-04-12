<?php
/**
 * Created by PhpStorm.
 * User: colinleung
 * Date: 28/12/2016
 * Time: 12:30 PM
 */
$step = isset($step)?$step:0;
?>
<div class="ui ordered steps four alt">
  <div class="step<?=($step>1)?' completed':''?><?=($step == 1)?' active':''?>">
    <div class="content">
      <div class="title">設定登入方式</div>
      <div class="description"></div>
    </div>
  </div>
  <div class="step<?=($step>2)?' completed':''?><?=($step == 2)?' active':''?>">
    <div class="content">
      <div class="title">電郵驗証</div>
      <div class="description">檢查你的電郵信箱。</div>
    </div>
  </div>
  <div class="step<?=($step>3)?' completed':''?><?=($step == 3)?' active':''?>">
    <div class="content">
      <div class="title">確認電郵驗証</div>
      <div class="description">按下電郵內的驗証連結。</div>
    </div>
  </div>
  <div class="step<?=($step>4)?' completed':''?><?=($step == 4)?' active':''?>">
    <div class="content">
      <div class="title">開始登入</div>
    </div>
  </div>
</div>
