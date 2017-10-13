<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>KF富文本编辑器</title>
<script type="text/javascript" src="jquery.min.js">
</script>
<script type="text/javascript">
$(function(){
    $d = $("#editor")[0].contentWindow.document; // IE、FF都兼容
    $d.designMode="on";
    $d.contentEditable= true;
    $d.open();
    $d.close();
    $("body", $d).append("<div>A</div><div>B</div><div>C</div>");
    
    $('#insert_img').click(function(){
        // 在iframe中插入一张图片                                    
        var img = '<img src="' + $('#path').val() +'" />';
        $("body", $d).append(img);
    });
    
    $('#preview').click(function(){
        // 获取iframe的body内容，用于显示或者插入到数据库
        alert($('#editor').contents().find('body').html());
        $('#preview_area').html($('#editor').contents().find('body').html());

    });
});

</script>

</head>

<body>


<p><iframe id="editor" width="600px" height="200px" style="border:solid 1px;"></iframe></p>
<input type="text" id="path" value="http://www.google.com.hk/intl/zh-CN/images/logo_cn.png"/>
<input type="button" id="insert_img" value="插入图片" />
<input type="button" id="preview" value="预览" />

<p style="border: 1px dashed #ccc;" id="preview_area"></p>

</body>
</html> 