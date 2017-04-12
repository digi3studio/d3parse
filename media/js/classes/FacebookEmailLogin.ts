/**
 * Created by colinleung on 7/4/2017.
 */

/// <reference path="../../../ts/typings/globals/jquery/index.d.ts"/>
/// <reference path="../../../ts/typings/globals/fb/index.d.ts"/>

///<amd-dependency path="jquery" />


export class FacebookEmailLogin{
  constructor(private node:JQuery){

    node.find('.selection').find('div.facebook').on('click', (e)=>{
      e.preventDefault();
      e.stopPropagation();
      //call FB login box
      FB.login(()=>{
        //after login, check status
        FB.getLoginStatus((response)=>{
          this.statusChangeCallback(response);
        });
      });
    });
  }

  statusChangeCallback(response) {
    if (response.status === 'connected') {
      this.prepareSignup(response.authResponse.userID, response.authResponse.accessToken)
    } else if (response.status === 'not_authorized') {
      this.showMessage("請在facebook授權登入勁揪體網站");
    } else {
      this.showMessage("請登入facebook");
    }
  }

  prepareSignup(id, token) {
    this.showMessage("請稍候");

    FB.api('/me', (response)=>{
      let hiddenField = this.node.find('input[name="fbid"]');
      hiddenField.attr('value', id);
      this.node.find('input[name="fbtoken"]').attr('value', token);
      this.node.find('input[name="fbname"]').attr('value', response.name);

      let form = hiddenField.parents('form');
      form.submit();

      this.showMessage("請稍候...");
//      $('#form').submit();
    });
  }

  showMessage(msg){
    $("#fb-status").html(msg);
  }
}