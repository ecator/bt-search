<?php
/**
 * Created by PhpStorm.
 * User: qule
 * Date: 2017/4/6
 * Time: 21:20
 */
require_once __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";
//加载库文件
require_once __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."SearchBT.php";
require_once __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."HttpRequest.php";
SlightPHP::setDefaultPage("index");
SlightPHP::setDefaultZone("app");
SlightPHP::setDefaultEntry("index");
if (strpos($_SERVER["HTTP_HOST"],"local")===false){
    //服务器环境
    SlightPHP::setDebug(false);
}else{
    SlightPHP::setDebug(true);
}
SlightPHP::setAppDir("..".DIRECTORY_SEPARATOR);
STpl::$template_dir=__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."template";
STpl::$compile_dir=__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."runtime";
$run=SlightPHP::run();
//var_dump($run,SlightPHP::getDebug());
if($run===false && !SlightPHP::getDebug()){
    //服务器环境且没有找到相应页面
    header("location:/404.html");
}