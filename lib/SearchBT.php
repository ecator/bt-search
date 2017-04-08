<?php

/**
 * Created by PhpStorm.
 * User: qule
 * Date: 2017/4/7
 * Time: 21:06
 */
namespace lib;
use \phpQuery as phpQuery;
use lib\HttpRequest;
class SearchBT
{
    static private $torrentkitty = "https://www.torrentkitty.tv/search/";
    static private $cldq="http://cilidaquan.me/cldq/";

    //从torrentkitty搜索
    static function torrentkitty($keyword){
        $url=self::$torrentkitty.urlencode($keyword);
        $dom=HttpRequest::request("get",$url);
        $dom=phpQuery::newDocumentHTML($dom);
        phpQuery::selectDocument($dom);
        $items=array();
        foreach (pq("#archiveResult")->find("tr") as $tr){
            $magnet=pq($tr)->find("a[rel=magnet]")->eq(0)->attr("href");
            if ($magnet){
                //有结果
                $item=new \stdClass();
                $item->title=pq($tr)->find(".name")->eq(0)->text();
                $item->date=pq($tr)->find(".date")->eq(0)->text();
                $item->magnet=$magnet;
                $items[]=$item;
            }
        }
        return $items;
    }

    //从磁力大全搜索
    static function cldq($keyword){
        $url=self::$cldq.urlencode($keyword)."/1-0-0.html";
        $re=HttpRequest::request("get",$url);
        $re=str_replace(["\r","\n","<b>","</b>"],"",$re);
        $pattern="/<dl class='item'>.*?target='_blank'>(.*?)<.*?收录时间:(.*?)<.*?(magnet:.*?)' >/";
        preg_match_all($pattern,$re,$matches);
//        var_dump($matches);
        $items=array();
        for ($i=0;$i<count($matches[0]);$i++){
            $item=new \stdClass();
            $item->title=$matches[1][$i];
            $item->date=$matches[2][$i];
            $item->magnet=$matches[3][$i];
            //过滤掉空白标题和无磁力链接项
            if ($item->title && $item->magnet){
                $items[]=$item;
            }
        }
        return $items;
    }
}