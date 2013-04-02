define(['Ajax'], function(Ajax) {

    var RmiHelper = function(cmd, data, callback) {
        var rmiData =
            {
                signature: cmd,
                params: data
            };
            var postData = JSON.stringify(rmiData);
            Ajax.request('POST', '/rmi.php', callback, postData);
    };

    // ========================================================================

    var Rmi = function() {};
    var rmi = new Rmi();

    // === login ===
    // Rmi example:
    // Rmi.login('testuser', 'testpassword', function(status, status) {});
    // status 0: wrong login
    // status 1: success
    rmi.login = function(username, password, servertime, callback) {
            var data = { username:username, password:password, servertime:servertime };
            RmiHelper('login', data, function(status, jsonText) {
                    var data = JSON.parse(jsonText);
                    callback(status, data.result);
        });
    };

    rmi.getSalt = function(username, callback) {
            var data = { 'username': username };
            RmiHelper('getSalt', data, function(status, jsonText) {
                var data = JSON.parse(jsonText);
                callback(status, data);
        });
    };

    rmi.getUserList = function(callback) {
        RmiHelper('getUserList', null, function(status, jsonText) {
            var data = JSON.parse(jsonText);
            callback(status, data);
        });
    };

    rmi.addUser = function(login, firstname, surname, email, permissions, callback) {
        var data = { login:login, firstname:firstname, surname:surname, email:email, permissions:permissions };
        RmiHelper('addUser', data, function(status, jsonText) {
            var data = JSON.parse(jsonText);
            callback(status, data.result);
        });
    }

    return rmi;
});

