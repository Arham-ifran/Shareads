var userIp;
function loadJSON(path, success, error)
{
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function ()
    {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if (success)
                    success(xhr.responseText);
            } else {
                if (error)
                    error(xhr);
            }
        }
    };
    xhr.open("GET", path, true);
    xhr.send();
}
loadJSON('https://jsonip.com?callback', function (data) {
    data = JSON.parse(data);
    userIp = data.ip;
});
var orderCatcher_form = function (_order_id, _transaction_id) {
    "use strict";

    var cookies;
    var order_id = _order_id;
    var transaction_id = _transaction_id;
    function readCookie(name, c, C, i) {
        if (cookies) {
            return cookies[name];
        }
        c = document.cookie.split('; ');
        cookies = {};
        for (i = c.length - 1; i >= 0; i--) {
            C = c[i].split('=');
            cookies[C[0]] = C[1];
        }
        return cookies[name];
    }
    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }
    var url = "https://www.shareads.com/tracking/order_input?pro=";
    var affid = readCookie('affid');
    if ('undefined' == typeof affid || affid == 'undefined')
    {
        affid = getUrlVars()["affid"];
        var date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
        document.cookie = "affid=" + affid + "; expires=" + expires + "; path=/";
    }
    var obj = new Object();
    obj.affid = affid;
    obj.order_id = order_id;
    obj.transaction_id = transaction_id;
    obj.sale_ip = userIp;
    obj.sale_success_url = window.location.href;
    obj.url = encodeURIComponent(document.URL.replace(/.*?:\/\//g, ""));
    var jsonString = JSON.stringify(obj);
    var ifr = document.createElement('img');
    ifr.setAttribute('id', 'msk_ch');
    ifr.setAttribute('data-url', document.URL);
    ifr.setAttribute("style", "width:1px;height:1px;");
    ifr.setAttribute("src", url + encodeURIComponent(jsonString));
    document.body.appendChild(ifr);
};
var orderCatcherForm = function () {
    "use strict";
    return {
        init: function (_order_id, _transaction_id) {
            orderCatcher_form(_order_id, _transaction_id);
        }
    };
}();