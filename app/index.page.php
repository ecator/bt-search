<?php
use lib\SearchBT;
use lib\HttpRequest;
class app_index extends STpl {
    //默认页面
    function PageIndex($inPath){
        $this->assign("title","bt搜～");
        $this->assign("keyword","");
        return $this->render("index.tpl");
    }
    //搜索请求页面
    function PageSearch($inPath){
//        var_dump($inPath);
        if (count($inPath)==4){
            //合法请求
            $this->assign("title","bt搜～".$inPath[3]);
            $this->assign("keyword",$inPath[3]);
            return $this->render("index.tpl");
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
            $torrentkitty=SearchBT::torrentkitty($keyword);
//            var_dump($torrentkitty);
            $cldq=SearchBT::cldq($keyword);
            $reArr=array_merge($torrentkitty,$cldq);
            $this->sortByKeyword(explode(" ",$keyword),$reArr);
            $re->results = $reArr;
        }
        echo json_encode($re);
    }

    //按照关键词的匹配程度排序
    private function sortByKeyword($keywords,&$arr){
        for ($i=0;$i<count($arr);$i++){
            for ($m=0;$m<count($arr)-$i-1;$m++){
                if (strlen($this->trimKeywords($keywords,$arr[$m]->title))>strlen($this->trimKeywords($keywords,$arr[$m+1]->title))){
                    $tmp=$arr[$m];
                    $arr[$m]=$arr[$m+1];
                    $arr[$m+1]=$tmp;
                }elseif (strlen($this->trimKeywords($keywords,$arr[$m]->title))==strlen($this->trimKeywords($keywords,$arr[$m+1]->title))){
                    //长度相等比较时间
                    if (strtotime($arr[$m]->date)<strtotime($arr[$m+1]->date)){
                        $tmp=$arr[$m];
                        $arr[$m]=$arr[$m+1];
                        $arr[$m+1]=$tmp;
                    }
                }
            }
        }
        return $arr;
    }

    //替换掉所有关键词
    private function trimKeywords($keywords,$str){
        for ($i=0;$i<count($keywords);$i++){
//            echo $keywords[$i];
            $str=str_ireplace($keywords[$i],"",$str);
        }
        return $str;
    }
}
?>