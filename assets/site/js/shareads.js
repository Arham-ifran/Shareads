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
    var cookies;
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
    var affid = readCookie('affid');
    if ('undefined' == typeof affid || affid == 'undefined')
    {
        affid = getUrlVars()["affid"];
        date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
        document.cookie = "affid=" + affid + "; expires=" + expires + "; path=/";

    }
    var shType = readCookie('shType');
    if ('undefined' == typeof shType || shType == 'undefined')
    {
        shType = getUrlVars()["type"];
        date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
        document.cookie = "shType=" + shType + "; expires=" + expires + "; path=/";
    }
    var pid = readCookie('pid');
    if ('undefined' == typeof pid || pid == 'undefined')
    {
        pid = getUrlVars()["prd"];
        date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
        document.cookie = "pid=" + pid + "; expires=" + expires + "; path=/";

    }
    var url = "https://www.shareads.com/analytics/?pro=";
    request_uri = location.pathname + location.search;
    var obj = new Object();
    obj.affid = affid;
    obj.type = shType;
    obj.url = encodeURIComponent(document.URL.replace(/.*?:\/\//g, "") + '&type=' + obj.type);
    obj.uri = encodeURIComponent(request_uri);
    obj.userIp = userIp;
    obj.userAgent = navigator.userAgent;
    obj.referrer = encodeURIComponent(document.referrer);
    var jsonString = JSON.stringify(obj);
    var ifr = document.createElement('img');
    ifr.setAttribute('id', 'msk');
    ifr.setAttribute('data-url', document.URL);
    ifr.setAttribute("style", "width:1px;height:1px;");
    ifr.setAttribute("src", url + encodeURIComponent(jsonString));
    document.body.appendChild(ifr);
});
