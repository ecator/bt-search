/**
 * Created by qule on 2017/4/7.
 */
$(document).ready(function () {
    //查找框按下回车
    $("#keyword").keydown(function () {
        // console.log(event);
        if (event.keyCode==13){
            //按下回车
            $("#search").click();
        }
    });

    //按下查找按钮
    $("#search").click(function () {
        var keyword=$("#keyword").val().trim();
        if (keyword){
            //有关键词
            location="/search/"+encodeURI(keyword);
        }else {
            //无关键词
            alert("请输入查找关键词");
            $("#keyword").focus();
        }
    });
});