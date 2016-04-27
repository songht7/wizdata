/**
type:验证类型
obj:验证数据
**/
var regexEnum = {
    "nonumber": "^([a-zA-Z\u4E00-\u9FA5].*$)|(^.*[a-zA-Z\u4E00-\u9FA5]$)",
    "intenum": "^[0-9]\\d*$",
    "email": "^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$",
    "url": "^http[s]?:\\/\\/([\\w-]+\\.)+[\\w-]+([\\w-./?%&=]*)?$",
    "mobile": "^(\\+86)?(1[0-9]{10})$",
    "phone": "^(\\+86|0|17951)?(13[0-9]|15[0-9]|18[0-9]|14[0-9])[0-9]{8}$",
    "notempty": "^\\S+$",
    "date": "^\\d{4}(\\-|\\/|.)\\d{1,2}\\1\\d{1,2}$",
    "qq": "^[1-9]*[1-9][0-9]*$",
    "tel": "^(([0\\+]\\d{2,3}-)?(0\\d{2,3})-)?(\\d{7,8})(-(\\d{3,}))?$",
    "username": "^\\w+$",
    "idcard": "^[1-9]([0-9]{14}|[0-9]{17}|[0-9]{16}(X|x))$",
    "not_email": "^((?!@).)+$",
    "order": "^(kx|KX)\\d+-\\d+$"
};
function fromexper(type, type2, obj, obj2) {
    var val = obj.val().replace(/(^\s*)|(\s*$)/g, "");
    //console.log(val)
    var i = true;
    switch (type) {
        case 'PassWord':
            var val2 = obj2.val().trim();
            if (val != val2) { i = false; };
            break;
        case 'Passnum':
            if (val.length > 20 || val.length < 6) { i = false; };
            break;
        case 'Replace':
            if (type2 == "notempty") {
                if (val.length <= 0) { i = false; };
            } else {
                var reg = new RegExp(regexEnum[type2]);
                i = reg.test(val);
            }
            break;

    }
    return i;
}

// 显示错误提示
function error(obj, text, type) {
    var prt = obj.parent();
    if (type == undefined) { type = ""; }
    if (text != undefined) {
        var error = "<div class='error " + type + "'>" + text + "</div>"
        //obj.addClass("on");
        if (prt.find(".error").size() <= 0) {
            prt.addClass("isError");
            obj.after(error);
        }

    } else {
        //obj.removeClass("on");
        //console.log(obj.parent())
        prt.removeClass("isError");
        prt.find(".error").remove();
    }

}

//表单提交
function formPost(obj, form) {
    var obj = $(obj);
    var _form = obj.closest("form");
    if (_form[0] == undefined) {
        if (form != "" && form != null && form != undefined) {
            _form = $("#" + form);
        } else {
            _form = $("body").find("form");
        }
    }
    var url = _form.attr("action");
    var errorTxt = "";
    var tips = '';
    var form_type = _form.data("form-type");

    //遍历验证
    _form.find(".ndCheck").each(function (k, t) {
        var _val = $(t).val();
        var verify = $(t).data("verify");
        var type = $(t).data("type");
        if (verify != undefined && verify != null) {
            var check = fromexper("Replace", verify, $(t));
            if (!check) {
                if (verify == 'notempty') { errorTxt = type + "不能为空"; } else { errorTxt = "请输入正确的" + type; }
                error($(t), errorTxt, "noBorder");
                //return false;
            }
        }
    });
    var _loading = '正在提交...';
    var txt = obj.find("span").html();
    if ($(".error").length <= 0 && errorTxt == "") {
        //验证通过
        var data = _form.serialize();
        if (form_type == undefined || form_type == "" || form_type == null) {//无form_type，则ajax跳转
            /**** Ajax ****/
            if (!obj.hasClass("isLoading")) {
                obj.addClass("isLoading");
                obj.find("span").html(_loading);
                $.post(url, data, function (result) {
                    var arr = result.split('|');
                    if (arr[0] == 0) {
                        tips = arr[1];
                        obj.find("span").html(tips);
                        setTimeout(function () {
                            obj.removeClass("isLoading");
                            obj.find("span").html(txt);
                        }, 1000);
                    } else if (arr[0] == 1) {
                        tips = arr[1];
                        obj.find("span").html(tips);
                        if (arr.length == 3) {
                            if (arr[2] == "reload") {
                                setTimeout(function () { window.location.reload(); },500);
                            } else if (arr[2] == "goback") {
                                setTimeout(function () { history.go(-1); }, 500);
                            } else {
                                setTimeout(function () { location.href = arr[2]; }, 500);
                            }
                        }
                        setTimeout(function () {
                            //obj.removeClass("isLoading");
                            obj.find("span").html(txt);
                        }, 1000);
                    }
                });
            }
        } else {
            obj.addClass("isLoading");
            obj.find("span").html(_loading);
            /**** 页面跳转 ****/
            _form.submit();
        }
    }
}
////IE placeholder
function ieplaceholder(obj, type, blur) {
    var data = "";
    // console.log(blur)
    var placeholder = obj.attr("placeholder");
    //console.log(obj,placeholder)
    if (type != "value") {
        data = obj.html();
        if (blur == undefined) { obj.html(placeholder); } else {
            if (blur != "focus") {
                if (data === "") { obj.html(placeholder); }
            } else {
                if (data === placeholder) { obj.html(""); }
            }
        }
    } else {
        data = obj.val();
        if (blur == undefined) { obj.val(placeholder); } else {
            if (blur != "focus") {
                if (data === "") { obj.val(placeholder); }
            } else {
                if (data === placeholder) { obj.val(""); }
            }
        }
    }
}