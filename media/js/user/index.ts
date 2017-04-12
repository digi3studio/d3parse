/**
 * Created by colinleung on 29/12/2016.
 */
/// <reference path="../../ts/typings/globals/jquery/index.d.ts"/>

$(document).on('ready',function(){
  window.location.href = $('body').data('base');
});