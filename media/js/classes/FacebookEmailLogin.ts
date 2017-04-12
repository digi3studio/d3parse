/**
 * Created by colinleung on 7/4/2017.
 */

/// <reference path="../../ts/typings/globals/jquery/index.d.ts"/>
/// <reference path="../../ts/typings/globals/fb/index.d.ts"/>

///<amd-dependency path="jquery" />


export class FacebookEmailLogin{
  private btnFacebookLogin:JQuery;
  private btnPasswordLogin:JQuery;

  constructor(private node:JQuery){
    this.btnFacebookLogin = node.find('.selection').find('div.facebook');
    this.btnPasswordLogin = node.find('.selection').find('div.password');

    this.btnFacebookLogin.on('click', (e)=>{
      e.preventDefault();
      e.stopPropagation();
      if(this.btnFacebookLogin.hasClass('loading')){
        return;
      }

      this.loading(true);

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
//      this.showMessage("請在facebook授權登入勁揪體網站");
      this.loading(false);
    } else {
//      this.showMessage("請登入facebook");
      this.loading(false);
    }
  }

  prepareSignup(id, token) {
    FB.api('/me', (response)=>{
      let hiddenField = this.node.find('input[name="fbid"]');
      hiddenField.attr('value', id);
      this.node.find('input[name="fbtoken"]').attr('value', token);
      this.node.find('input[name="fbname"]').attr('value', response.name);

      let form = hiddenField.parents('form');
      form.submit();
    });
  }

  loading(b:boolean){
    if(b){
      this.btnFacebookLogin.addClass('loading');
      this.btnPasswordLogin.addClass('disabled');
    }else{
      this.btnFacebookLogin.removeClass('loading');
      this.btnPasswordLogin.removeClass('disabled');
    }
  }
}