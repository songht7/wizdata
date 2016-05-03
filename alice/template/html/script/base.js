var browser = checkBrowser();
var ie8 = false;
var ie9 = false;
if (browser && browser.is == "ie" && browser.vs < 9) { ie8 = true; }
if (browser && browser.is == "ie" && browser.vs <= 9) { ie9 = true; }

$(function () {
    /** 菜单  */
    $(document).on("mouseover","menu li",function(){
        var obj=$(this);
        $("menu .subMenu,menu em").hide();
        if(obj.find(".subMenu").length){
            obj.find(".subMenu").show();
            obj.find("em").show();
        }
    });
    $(document).on("mouseleave","header",function(){
        var obj=$(this);
        $("menu .subMenu,menu em").hide();
    });
    /* 回到顶部 */
    $(document).on("click","#ScrollTop",function(){
        $("html,body").animate({"scrollTop":0},500);
    });
    //去除验证错误提示
    $(document).on("focus", ".ndCheck", function () {
        var obj = $(this);
        var prt = obj.parent();
        prt.removeClass("isError");
        prt.find(".error").remove();
    });
    $(document).on('click', '#MenuBtn', function(event) {
        var obj=$(this);
        if(!obj.hasClass('on')){
            obj.addClass('on');
            $("menu").show();
        }else{
            obj.removeClass('on');
            $("menu").hide();
        }
    });
    $(window).resize(function (event) {
        reSize();
    });

    if ($(".isSkrollr").length) {
        var topHeigth=$("header").outerHeight();
        $(window).scroll(function () {
            var sTop = $(window).scrollTop();
            if (sTop >=topHeigth) { $("header").addClass("isFix") }
            else { $("header").removeClass("isFix");}
        });
    }
    
});

window.onload=function(){
    reSize();
}

var minWidth=1280;
var _innerWidth=1024;
function reSize(){
    var winWidth = $(window).width();
    if ($("header").hasClass("isFix")) {
        if(winWidth-25<=minWidth){
            $("header.isFix").css({"width":winWidth});
            $("header.isFix .inner").css({"width":winWidth-100});
        }else{
            $("header.isFix").css({"width":"100%"});
            $("header.isFix .inner").css({"width":minWidth});
        }
    }else{
        $("header").css({"width":"100%"});
        if(winWidth>800){
            $("header .inner").css({"width":minWidth});
        }else{
            $("header .inner").css({"width":"100%"});
        }
    }

    if(winWidth<=800){
        $("menu").hide();
        $("#MenuBtn").removeClass('on');
    }else{
        $("menu").show();
    }
    /** pro  */
    if($(".isFull").length){
        $(".isFull").each(function(i,e){
            var imgWidth=714,
                frameWidth=$(e).find(".proImg").parent().width(),
                overFlow=imgWidth-frameWidth;
            overFlow=overFlow>0?overFlow:0;
            var side=(winWidth-_innerWidth)/2;
            if(overFlow>=side){
                var cut=overFlow-side;
                cut=side==0?0:cut;
                imgWidth=imgWidth-cut;
            }
                // console.log(imgWidth, frameWidth);
                // console.log(i, overFlow, side, cut, imgWidth);
                // console.log("---------");
            $(e).find(".proImg").css({"width":imgWidth+"px"});
        });
    }
    /** home page`s video **/
    $(document).on('click', '.vdImg', function(event) {
        var obj=$(this),
        url=obj.data("url"),
        vid=obj.data('id');
        $.colorbox({href:url,width:"90%",height:"90%",
            onComplete:function(){
                var video='<video id="VideoBox" controls="controls" autoplay="autoplay" width="100%" height="100%">'
                                +'<source src="template/html/video/v'+vid+'.mp4" type="video/mp4" />'
                                +'您的浏览器不支持 video 标签。'
                            +'</video>';
                $(".popBox").html(video);
            }
        });
    }); 
}
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
        var verify = $(t).data("verify"),
        type = $(t).data("type"),
        ckTypes = $(t).data("cktypes");
        ckTypes=ckTypes==undefined||ckTypes==null?"Replace":ckTypes;
        if (verify != undefined && verify != null) {
            var obj2="";
            if(verify=="reCheck"){
                obj2=$("#NewPassword");
            }
            var check = fromexper(ckTypes, verify, $(t),obj2);
            if (!check) {
                if (verify == 'notempty') { errorTxt = "'"+type + "' can`t be empty"; }
                else if(verify=="reCheck"){errorTxt = "'"+type+"'is not consistent, please confirm";}
                else { errorTxt = "Please enter the correct '"+type+"'";}
                error($(t), errorTxt, "noBorder");
                //return false;
            }
        }
    });
    if ($('#RepairContent').length > 0) {
        if ($('#RepairContent').val() == "") {
            $('#RepairContent').addClass("error");
        }
    }
    var _loading = 'Submitting...';
    var txt = obj.find("span").html();
    if ($(".error").length <= 0 && errorTxt == "") {
        //验证通过
        var data = _form.serialize();
        if (form_type == undefined || form_type == "" || form_type == null) {//无form_type，则ajax跳转
            //$.colorbox({ overlayClose: false, href: "/prompt", close: "<span class='white'>x</span>" });
            /**** Ajax ****/
            if (!obj.hasClass("isLoading")) {
                obj.addClass("isLoading");
                obj.find("span").html(_loading);
                $.post(url, data, function (data) {
                    var data=jQuery.parseJSON(data);
                    console.log(data);
                    if (data.Result == 0) {
                        tips = data.Msg;
                        obj.find("span").html(tips);
                        setTimeout(function () {
                            obj.removeClass("isLoading");
                            obj.find("span").html(txt);
                        }, 1000);
                    } else if (data.Result == 1) {
                        tips = data.Msg;
                        obj.find("span").html(tips);
                        if (data.Type) {
                            if (data.Type == "reload") {
                                setTimeout(function () { window.location.reload(); }, 1500);
                            } else if (data.Type == "goback") {
                                setTimeout(function () { history.go(-1); }, 1500);
                            }else {
                                setTimeout(function () { location.href = data.Type; }, 1500);
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

//检测浏览器及其版本
function checkBrowser() {
    var browser = navigator.appName;
    var b_version = navigator.appVersion;
    var version = b_version.split(";");
    var regx = new RegExp("/[ ]/g");
    var is = vs = "";
    if (browser == "Microsoft Internet Explorer") {
        var trim_Version = version[1].replace(" ", "");
        if (trim_Version == "MSIE 7.0") {
            return { is: "ie", vs: 7 };
        } else if (trim_Version == "MSIE 8.0") {
            return { is: "ie", vs: 8 };
        } else if (trim_Version == "MSIE 9.0") {
            return { is: "ie", vs: 9 };
        } else {
            return { is: "ie", vs: trim_Version };
        }
    } else {
        return { is: browser, vs: version };
    }
}
