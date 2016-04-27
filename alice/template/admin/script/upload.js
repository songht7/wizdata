var uploadfile = {
    select: function (id) {
        $("#fileupload").attr("data-value", id).click();
    },
    up: function (id, upurl) {
        //alert(obj);
        var formData = new FormData($("#uploadForm")[0]);
        $.ajax({
            type: "POST",
            data: formData,
            //url: "http://honstat.com/api/upload/image",						//传红图片上传接口
            url: upurl, //space图片上传接口
            //url: "./api.php",					//space 本地调试图片上传接口
            contentType: false,
            processData: false,
            dataType: "json"
        }).success(function (data) {
            if (data.error === 0) {
                $("#url" + id).attr("data-set", '1');
                $("#url" + id).attr("value", data.url);
                $("#img" + id).attr("src", data.url);
            } else {
                alert(data.error_info);
                console.log(data.error);
                console.log(data.error_info);
            }
        }).error(function (data) {
            //alert(data);
            alert(data);
            console.log(data);
        });
    }
};