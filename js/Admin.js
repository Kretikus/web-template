define(['jquery','Rmi'], function($, RMI) {

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA    -Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

var formIsComplete = function() {
    var login, firstname, surname, email, permissions;
    login       = $('#form-add-user-login').val();
    firstname   = $('#form-add-user-firstname').val();
    surname     = $('#form-add-user-surname').val();
    email       = $('#form-add-user-email').val();

    $('#form-add-user-login').removeClass('input-red');
    $('#form-add-user-firstname').removeClass('input-red');
    $('#form-add-user-surname').removeClass('input-red');
    $('#form-add-user-email').removeClass('input-red');

    if (login.length <= 3) {
        $('#form-add-user-login').addClass('input-red');
        return false;
    }

    if (firstname.length <= 3) {
        $('#form-add-user-firstname').addClass('input-red');
        return false;
    }

    if (surname.length <= 3) {
        $('#form-add-user-surname').addClass('input-red');
        return false;
    }

    if (email.length <= 3 || !validateEmail(email)) {
        $('#form-add-user-email').addClass('input-red');
        return false;
    }

    return true;
}

$('.form-add-user button[type=submit]').bind('click', function(e) {
        e.preventDefault();

        if (!formIsComplete()) {
            $('.form-add-user-msg').show();
            return;
        }
        $('.form-add-user-msg').hide();

        var login, firstname, surname, email, permissions;
        login       = $('#form-add-user-login').val();
        firstname   = $('#form-add-user-firstname').val();
        surname     = $('#form-add-user-surname').val();
        email       = $('#form-add-user-email').val();
        permissions = $('#form-add-user-isadmin').is(':checked') ? 1 : 0;
        RMI.addUser(login, firstname, surname, email, permissions, function(status, result) {
        });
    });

// =====================================================

var Admin = function() {};
var admin = new Admin();

    admin.populateUserList = function() {

        RMI.getUserList(function(status, userList) {
            var $usersTable  = $('.users-table');
            var $usersBody   = $('.users-table tbody');
            var $templateRow = $('#users-table-template');
            $templateRow.detach();
            var i;
            for (i=0; i<userList.length; i++) {
                var user = userList[i];
                var newRow = $templateRow.clone();
                $tds = $('td',newRow);
                $($tds[0]).text(i);
                $($tds[1]).text(user.login);
                $($tds[2]).text(user.firstname);
                $($tds[3]).text(user.surname);
                $($tds[4]).text(user.email);
                $($tds[5]).text(user.permissions == 1 ? 'A' : ' ');
                $($tds[6]).text('-');
                newRow.appendTo($usersBody);
            }
        });
    }

    admin.addUser = function() {

    }

return admin;
});
