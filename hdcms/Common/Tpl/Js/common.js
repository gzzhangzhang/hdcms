/**
 * 缩略图上传
 * @param obj [input:type=file]表单对象
 * @param action form表单提交的action
 * @param target iframe元素
 * @param _input 记录图片地址的input表单
 * @param picdiv 显示缩略图的div元素
 */
function img_upload(obj, action, target, _input, picdiv) {
    var form = $(obj).parents("form");//表单
    $action = form.attr("action");//原action
    form.attr("action", action + "&name=" + _input + "&div=" + picdiv);
    form.attr("target", target);//更改上传地址为，iframe
    form.submit();//上传
    form.attr("action", $action);//将原来的action还原回来
    form.removeAttr("target");//清除target
    var _div = $("." + picdiv);//放置图片的div
    _div.append('图片上传中...');
}
/**
 * 选择颜色
 * @param obj 颜色选择对象，按钮对象
 * @param _input 颜色name=color表单
 */
function select_color(obj, _input) {
    if ($("div.colors").length == 0) {
        var _div = "<div class='colors' style='width:80px;height:160px;position: absolute;z-index:999;'>";//颜色块
        var colors = ["#f00f00", "#272964", "#4C4952", "#74C0C0", "#3B111B", "#147ABC", "#666B7F", "#A95026", "#7F8150"
            , "#F09A21", "#7587AD", "#231012", "#DE745C", "#ED2F8D", "#B57E3E", "#002D7E", "#F27F00", "#B74589"
        ];
        for (var i = 0; i < 16; i++) {
            _div += "<div color='" + colors[i] + "' style='background:" + colors[i] + ";width:20px;height:20px;float:left;cursor:pointer;'></div>"
        }
        _div += "</div>";
        $("body").append(_div);
        $(".colors").css({top: $(obj).offset().top + 30, left: $(obj).offset().left});
    }
    $("div.colors").show();
    $("div.colors div").click(function () {
        $("div.colors").hide();
        var _c = $(this).attr("color");
        $("[name='" + _input + "']").val(_c);
        $("[name='title']").css({color: _c});
    })
}

/** 图片上传***/
function selectImage(obj) {
    var inputlab = $(obj).prev().attr("lab");
    window.open(METH + "&action=uploadImage&lab=" + inputlab, 'newwindow', 'height=400,width=650,top=100,left=200,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
}
/**
 在弹出窗体中选择上传图片，修改父级input
 */
function updateImageInput(obj) {
    var path = $(obj).attr("path");
    var inputLab = $(obj).attr("inputlab");
    $(opener.document).find("input[lab='" + inputLab + "']").val(path);
    window.close();

}