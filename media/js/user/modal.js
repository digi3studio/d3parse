/**
 * Created by colinleung on 26/12/2016.
 */
/// <reference path="../../ts/typings/globals/jquery/index.d.ts"/>
/// <reference path="../../ts/typings/globals/semantic-ui/index.d.ts"/>
var isLoggedIn = false;
$(document).ready(function () {
    overrideSubmit();
    $('#nav-login').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#modal-login').modal('show');
    });
    $('#nav-profile, #nav-logout')
        .on('click', overrideModalLink);
    $('#nav-user').on('click', function (e) {
        overrideModalLink(e);
        var base = $('body').data('base');
        $.ajax(base + 'user/package.json').done(function (data) {
            $('#package-content').html(data.html);
            $('#modal-user').attr('data-state', 'idle').modal('show');
        });
    });
    if ($('body').hasClass('member')) {
        $('#nav-user').trigger('click');
    }
});
function overrideModalLink(e) {
    e.preventDefault();
    e.stopPropagation();
    var modal = $('#' + $(this).attr('data-modal'));
    modal.attr('data-state', 'pending');
    modal.modal('show');
    var uri = $(e.currentTarget).attr('href') + '.json';
    $.ajax(uri, {
        success: function (data) {
            onUserPostDone(data, modal);
            modal.attr('data-state', 'idle');
        }
    });
}
function overrideSubmit() {
    $('.modal').each(function (index, elem) {
        var modal = $(elem);
        modal.attr('data-default-state', modal.attr('data-state'));
        var forms = modal.find('form');
        if (forms.length <= 0)
            return;
        var form = $(forms[0]);
        var postURL = form.attr('action') + ".json";
        form.on('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $.post(postURL, form.serialize())
                .done(function (data) {
                onUserPostDone(data, modal);
                modal.modal('hide');
                modal.attr('data-state', 'done');
            })
                .fail(function (data) {
                renderLogout(data, modal);
            });
            modal.attr('data-state', 'pending');
        });
    });
}
function applyResult(data) {
    for (var name in data) {
        var value = data[name];
        var dataElements = $('*[data-user-' + name + ']');
        //boolean
        if (typeof (value) == 'boolean') {
            dataElements.attr('data-user-' + name, value ? 'true' : 'false');
            if (value == true) {
                $('.checkbox-user-' + name).attr('checked', 'checked');
            }
            else {
                $('.checkbox-user-' + name).removeAttr('checked');
            }
            continue;
        }
        $('.user-' + name).html(value);
        $('.input-user-' + name).val(value);
        dataElements.attr('data-user-' + name, (value == null || value == "") ? 'false' : 'true');
    }
}
function renderLogin(data) {
    applyResult(data);
    $('body').addClass('member').removeClass('anonymous').trigger('class-change');
    isLoggedIn = true;
}
function renderLogout(data, modal) {
    console.log(data);
    applyResult({
        'nickname': 'anonymous',
        'username': '',
        'email': ''
    });
    $('body').addClass('anonymous').removeClass('member');
    $('.modal').each(function (index, elem) {
        $(elem)
            .attr('data-state', $(elem).attr('data-default-state'))
            .modal('hide');
    });
    isLoggedIn = false;
}
function onUserPostDone(data, modal) {
    if (data.status == 'error') {
        renderLogout(data, modal);
        var base = $('body').data('base');
        alert(data.message);
        window.location.href = base + 'contributor/invite';
        return;
    }
    if (data.status == 'logout') {
        renderLogout(data, modal);
        return;
    }
    if (isLoggedIn == false) {
        $('#nav-user').trigger('click');
    }
    renderLogin(data);
}
console.log('media/js/user/modal.ts v 1.10');
//# sourceMappingURL=modal.js.map