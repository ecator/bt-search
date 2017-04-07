<?php
/**
 * Created by PhpStorm.
 * User: qule
 * Date: 2017/3/6
 * Time: 12:51
 * 自己写的一个简单HTTP请求类，支持get和post
 */

namespace lib;


class HttpRequest
{
    static function request($method='get',$url,$param=[]){
        $query='';
        if (is_array($param)){
            foreach ($param as $key=>$val){
                if (!$query){
                    $query=$key."=".urlencode($val);
                }else{
                    $query.="&".$key."=".urlencode($val);
                }
            }
        }
        $ch=curl_init();
        switch ($method){
            case 'get':
                if ($query==''){
                    curl_setopt($ch,CURLOPT_URL,$url);
                }else{
                    curl_setopt($ch,CURLOPT_URL,$url."?".$query);
                }
                break;
            case 'post':
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_POST,true);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$query);
                break;
            default:
                curl_setopt($ch,CURLOPT_URL,$url);
        }
        $headers = array();
        $headers[] = 'Accept: text/html';
        $headers[] = 'referer:https://www.inoreader.com/article/3a9c6e7f184fd64b-';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
        $headers[] = 'X-MicrosoftAjax: Delta=true';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $re=curl_exec($ch);
        curl_close($ch);
        return $re;
    }

}