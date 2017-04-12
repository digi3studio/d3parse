/**
 * Created by colinleung on 28/12/2016.
 */
/// <reference path="../../ts/typings/globals/jquery/index.d.ts"/>
/// <reference path="../../ts/typings/globals/fb/index.d.ts"/>
function statusChangeCallback(response) {
    if (response.status === 'connected') {
        prepareSignup(response.authResponse.userID, response.authResponse.accessToken);
    }
    else if (response.status === 'not_authorized') {
        showMessage("請在facebook授權登入勁揪體網站");
    }
    else {
        showMessage("請登入facebook");
    }
}
function prepareSignup(id, token) {
    showMessage("請稍候");
    FB.api('/me', function (response) {
        $('#fbid').attr('value', id);
        $('#fbtoken').attr('value', token);
        $('#fbname').attr('value', response.name);
        showMessage("請稍候...");
        $('#form').submit();
    });
}
function checkLoginState() {
    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
}
function showMessage(msg) {
    $("#fb-status").html(msg);
}
//# sourceMappingURL=login-box.js.map