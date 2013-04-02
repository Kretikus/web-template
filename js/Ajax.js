define(function() {

    function createXHR() {
        var xhr;
        try { xhr = new XMLHttpRequest(); } catch(e1) {
            try { xhr = new ActiveXObject("Microsoft.XMLHTTP"); } catch(e2) {
                try { xhr = new ActiveXObject("Msxml2.XMLHTTP"); } catch(e3) {
                    xhr = null;
                }
            }
        }
        return xhr;
    }

    function nop() {}

    // ========================================================================

    var Ajax = function() {};
    var ajax = new Ajax();

    ajax.request = function(method, url, callback, content, headers, contentType) {
        var xhr = createXHR();
        if (!xhr) {
            callback();
            return;
        }

        xhr.open(method, url, true);
        if (headers) {
            for (var h in headers) {
                xhr.setRequestHeader(h, headers[h]);
            }
        }
        if (content) xhr.setRequestHeader('Content-Type', contentType ? contentType : 'application/json');

        xhr.onreadystatechange = function() {
            if (xhr.readyState != 4) return;
            xhr.onreadystatechange = nop;
            callback(xhr.status, xhr.responseText, xhr);
        };
        xhr.send(content);
    };

    return ajax;
});

