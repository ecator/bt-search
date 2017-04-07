<?php
use lib\SearchBT;
use lib\HttpRequest;
class app_index extends STpl {
    //默认页面
    function PageIndex($inPath){
        $this->assign("title","bt搜～");
        $this->assign("keyword","");
        return $this->fetch("index.tpl");
    }
    //搜索请求页面
    function PageSearch($inPath){
//        var_dump($inPath);
        if (count($inPath)==4){
            //合法请求
            $this->assign("title","bt搜～".$inPath[3]);
            $this->assign("keyword",$inPath[3]);
            return $this->fetch("index.tpl");
        }else{
            //非法请求，跳转404页面
            header("location:/404.html");
        }
    }
    /*搜索请求API
    **POST请求，keyword为请求关键词，返回一个json数据
     * {
     *  "msg":"ok",
     *  "results":[
     *      {
     *          title:"",
     *          date:"",
     *          magnet:""
     *      }
     * ]
     * }
    */
    function PageGetResults(){
        /**
         * 搜索列表
         * https://www.torrentkitty.tv/search/WANZ-610/
         */
        //json返回对象
        header("content-type:application/json;charset=utf-8");
        $re=new stdClass();
        $re->msg="";
        $re->results=array();
        $keyword=isset($_POST['keyword'])?$_POST["keyword"]:"";
        if ($keyword==""){
            //无关键词参数
            $re->msg="no keyword";
        }else {
            //有关键词参数
            $re->msg="ok";
            $reArr=array_merge(SearchBT::torrentkitty($keyword),SearchBT::cldq($keyword));
            $re->results = $reArr;
        }
        echo json_encode($re);
    }
}
?>