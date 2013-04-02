require.config({
    baseUrl: '/js/',
    paths: {
        'jquery' : '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min',
        'bootstrap' : '../vendor/bootstrap/js/bootstrap.min'
    },
    shim: {
        'bootstrap' : ['jquery'],
    },
    priority: ['jquery'],
    waitSeconds: 15
});

require([
    'jquery',
    'Rmi',
    'Admin',
    '../vendor/crypto/sha256',
    'bootstrap'
], function($, RMI, Admin, Hash) {

    $('.form-signin button').bind('click', function(e) {
        e.preventDefault();
        $('.form-signin-msg').hide();

        var username = $('.form-signin input[type=text]').val();
        var password = $('.form-signin input[type=password]').val();

        RMI.getSalt(username, function(status, data) {
            console.log(data);
	    var salt       = data.salt;
	    var servertime =  "" + data.servertime;

            if (salt.length == 0) {
                $('.form-signin-msg').show();
                return;
            }
	
            var hash = Hash.hmac(Hash.hmac(password, salt), servertime);
            RMI.login(username, hash, servertime, function(status, result) {
                    if (result == 0) {
                        $('.form-signin-msg').show();
                    } else {
                        console.log("Login erfolgreich!");
                        $('.form-signin').hide();
                        $('.navbar').show();
                        $('#content').show();
                    }
                }
            );
        });
    });

/*
$('.form-signin').hide();
$('.navbar').show();
//$('#content').show();
$('#admin').show();

Admin.populateUserList();
*/

});
