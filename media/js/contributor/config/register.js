/**
 * Created by colinleung on 11/4/2017.
 */
require.config({
    paths: {
        'jquery':      '//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min',
        'hammerjs':    '//ajax.googleapis.com/ajax/libs/hammerjs/2.0.8/hammer.min',
        'd3_carousel':    '../../classes/Carousel',
        'd3_fblogin': '../../classes/FacebookEmailLogin'
    }
});
require(['d3_carousel', 'jquery', 'd3_fblogin'], function (Carousel_1, $, FBLogin) {

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '529409987446867',
            xfbml      : true,
            version    : 'v2.8'
        });
    };
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    $('.facebook-email-register').each(function(key, value){
        new Carousel_1.Carousel($(value));
        new FBLogin.FacebookEmailLogin($(value));
    });
});