$(function() {
    jQuery("#sidebar .has-sub > a > .arrow").click(function() {
        var a = jQuery(".has-sub.open", $("#sidebar"));
        a.removeClass("open");
        jQuery(".arrow", a).removeClass("open");
        jQuery(".sub", a).slideUp(200);
        a = jQuery(this).parent().next();
        a.is(":visible") ? (jQuery(this).removeClass("open"), jQuery(this).parent().parent().removeClass("open"), a.slideUp(200)) : (jQuery(this).addClass("open"),
                jQuery(this).parent().parent().addClass("open"), a.slideDown(200));

        return false;
    });

    jQuery(document).scroll(function() {
        var sidebar = jQuery("#sidebar");
        var top = parseInt(jQuery(window).scrollTop());
        if (top < 42)
            top = 42;
        sidebar.stop().animate({top: top});
        //sidebar.css("top", top);
        var links = jQuery("#sidebar li");
        for (var i = 0; i < links.length; i++) {
            var link = jQuery(links[i]);
            var href = jQuery("a", link).attr('href').substring(1);
            var nameObj = jQuery(":first-child", jQuery("a[name='" + href + "']").next());
            if (nameObj[0]) {
                var visibleTop = nameObj.offset().top + nameObj.height();
                if (visibleTop >= top && visibleTop <= top + 100) {
                    jQuery("li.active", sidebar).removeClass('active');
                    link.addClass('active');
                    if (link.parent().hasClass('sub')) {
                        link.parent().parent().addClass('active');
                        $(">a>span.arrow", link.parent().parent()).addClass("open");
                    } else {
                        $(">a>span.arrow", link).addClass("open");
                    }
                    var url = location.href;
                    var position = url.indexOf("#");
                    if (position > -1 && href != url.substr(position + 1)) {
                        location.href = url.substr(0, position) + "#" + href;
                    }
                    break;
                }
            }
        }
    });

    jQuery("form").submit(function() {
        var formObj = jQuery(this);

        var need_authorization = formObj.attr("need-authorization") == "true";
        if (need_authorization) {
            if (this.encoding == "multipart/form-data" || this.enctype == "multipart/form-data") {
                alert("This is contains file. This kind of form with authentication is not supported.");
                return false;
            }
        }

        formObj.css('cursor', 'wait');
        var html = "<div class='alert alert-info' style='word-wrap: break-word'><i class='icon-info-sign'></i>Please wait...</div>";
        var resContainer = jQuery("div.result-container", formObj.parent());
        if (resContainer[0] == null) {
            formObj.after("<div class='result-container'>" + html + "</div>");
        } else {
            resContainer.html(html);
        }

        var method = this.method;
        var action = this.action;
        var isMultipart = this.enctype == "multipart/form-data";
        var params = (isMultipart ? new FormData(this) : formObj.serialize());

        var options = {
            url: action,
            type: method,
            data: params,
            beforeSend: function(xhr) {
                if (need_authorization) {
                    var token = jQuery("#token").val();
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(res) {
                var status = res.status;
                var raw = JSON.stringify(res);
                if (typeof res == "object") {
                    res = json2string(res);
                } else {
                    res = raw;
                }
                var resContainer = jQuery("div.result-container", formObj.parent());
                if (status) {
                    var res = "<div class='result result-success'>" + res + "</div>";
                    res += "<div class='result'>" + raw + "</div>";
                    var html = "<div class='alert alert-success' style='word-wrap: break-word'><i class='icon-ok-sign'></i><span class='label label-success'>200</span>  " + res + "</div>";
                    if (resContainer[0] == null) {
                        formObj.after("<div class='result-container'>" + html + "</div>");
                    } else
                        resContainer.html(html);
                } else {
                    var res = "<div class='result result-error'>" + res + "</div>";
                    res += "<div class='result'>" + raw + "</div>";
                    var html = "<div class='alert alert-success' style='word-wrap: break-word'><i class='icon-ok-sign'></i><span class='label label-success'>200</span>  " + res + "</div>";
                    if (resContainer[0] == null) {
                        formObj.after("<div class='result-container'>" + html + "</div>");
                    } else
                        resContainer.html(html);
                }
            },
            error: function(error) {
                var res = "<div class='result result-error'>" + error.responseText + "</div>";
                var resContainer = jQuery("div.result-container", formObj.parent());
                var html = "<div class='alert alert-error' style='word-wrap: break-word'><i class='icon-remove-sign'></i><span class='label label-warning'>" + error.status + "</span>  " + res + "</div>";
                if (resContainer[0] == null) {
                    formObj.after("<div class='result-container'>" + html + "</div>");
                } else
                    resContainer.html(html);
            }
        };
        if (isMultipart) {
            options.contentType = false;
            options.processData = false;
        }
        jQuery.ajax(options).done(function() {
            formObj.css('cursor', 'default');
        });

        return false;
    });
});

function pwd_digest(form, change)
{
    if (!change || form['password'].value != '') {
        form['password'].value = getDigest(form['password'].value);
    }
}

function base64Encode(str) {
    var CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
    var out = "", i = 0, len = str.length, c1, c2, c3;
    while (i < len) {
        c1 = str.charCodeAt(i++) & 0xff;
        if (i == len) {
            out += CHARS.charAt(c1 >> 2);
            out += CHARS.charAt((c1 & 0x3) << 4);
            out += "==";
            break;
        }
        c2 = str.charCodeAt(i++);
        if (i == len) {
            out += CHARS.charAt(c1 >> 2);
            out += CHARS.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
            out += CHARS.charAt((c2 & 0xF) << 2);
            out += "=";
            break;
        }
        c3 = str.charCodeAt(i++);
        out += CHARS.charAt(c1 >> 2);
        out += CHARS.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
        out += CHARS.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >> 6));
        out += CHARS.charAt(c3 & 0x3F);
    }
    return out;
}

function get_base64(obj) {
    var file = $(obj)[0].files[0];

    var reader = new FileReader();
    reader.onload = function(evt) {
        var fileData = evt.target.result;
        var bytes = new Uint8Array(fileData);
        $(obj).next().val(Base64.encodeByteArray(bytes));
    };
    reader.readAsArrayBuffer(file);
}

function convertTitle(formObj) {
    formObj.title.value = base64Encode(formObj.title.value);
}
function convertMessage(formObj) {
    formObj.message.value = base64Encode(formObj.message.value);
}

function json2string(json) {
    var str = "";
    var type = null;
    if (json instanceof Array) {
        type = "array";
    } else {
        type = "object";
    }

    if (type == "array") {
        str += "[";
    } else {
        str += "{";
    }
    var sub = "";
    for (var key in json) {
        if (sub == "") {
            sub += "<ul>";
        }
        sub += "<li>";
        var value = json[key];
        sub += key + ": " + (value != null && typeof value == "object" ? json2string(value) : value);
        sub += "</li>";
    }
    if (sub != "") {
        sub += "</ul>";
    }
    str += sub;
    if (type == "array") {
        str += "]";
    } else {
        str += "}";
    }
    return str;
}