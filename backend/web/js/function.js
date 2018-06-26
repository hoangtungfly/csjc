String.prototype.nl2br = function()
{
    return this.replace(/\n/g, "<br />");
}
function encodeQuote(str) {
    return str.replace(/"/gi,'&quot;').replace(/'/gi,'&#039;');
}
function count(array) {
    return array.length;
}
function strlen(str) {
    return str.length;
}
function substr(value, start, length) {
    return value.substr(start, length);
}
function trim(str) {
    return str.replace(/^( )+|( )+$/gi, '');
}
function trimAll(str) {
    return trim(str.replace(/( )+/gi, ' '));
}
function explode(delimiter, str) {
    return str.split(delimiter);
}
function implode(delimiter, array) {
    if (array !== null && array.length > 0)
        return array.join(delimiter);
    else
        return '';
}
function cutLetter(str, $count, dotdot) {
    if (strlen(str) > $count) {
        str = trim(str);
        while (str.charAt($count) != ' ') {
            $count--;
        }
    }
    return trim(str.substr(0, $count)) + ((dotdot && strlen(str) > $count) ? dotdot : '');
}


function getGet(str) {
    var result = {};
    var a = explode('?', str);
    if (a.length > 1) {
        var b = explode('&', a[1]);
        for (var i = 0; i < b.length; i++) {
            var c = explode('=', b[i]);
            if (c.length > 1) {
                result[c[0]] = c[1];
            }
        }
    }
    return result;
}

function getFull() {
    return getGet(window.location.href);
}

function hasGet($str) {
    var a = explode('?', window.location.href);
    if (count(a) > 1 && a[1].replace()) {
        var b = explode('&', a[1]);
        for (var i = 0; i < count(b); i++) {
            var c = explode('=', b[i]);
            if (c[0] == $str) {
                return true;
            }
        }
    }
    return false;
}

function number_format(so1) {
    so1 = $.trim(so1);
    var so = (so1 != '0' && so1 != '') ? String(so1.replace(/([^0-9.])+|^(0)+/gi, '')) : so1;
    var sotp = so.split('.');
    so = sotp[0];
    var xau2 = '';
    if (sotp.length > 1) {
        sotp[0] = '';
        xau2 = sotp.join('');
    }
    var mangso = so.split("");
    var count = mangso.length;
    var xau = "";
    var j = 1;
    for (var i = count - 1; i >= 0; i--) {
        xau = String(mangso[i]) + xau;
        if (j % 3 == 0 && j != count)
            xau = "," + xau;
        j++;

    }
    if (sotp.length > 1) {
        xau += '.' + xau2;
    }
    return xau;
}

function number_percent(so1) {
    so1 = $.trim(so1);
    if (so1 != "") {
        var mangso = so1.split('.');
        so1 = mangso[0];
    }
    var so = String(so1.replace(/(\D)+/gi, ''));
    var so2 = parseInt(so);
    if (so2 > 100)
        so = 100;
    return so + '%';
}

function upperCaseFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}