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
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        /**
         * 由于腾讯服务器 liburl有一个bug，需要手动指定cipher
         * 参考http://stackoverflow.com/questions/31107851/how-to-fix-curl-35-cannot-communicate-securely-with-peer-no-common-encryptio
         */
        if(strpos($_SERVER["HTTP_HOST"],"local")===false){
            //树莓派服务器兼容，树莓派运行需要注释掉下面一句
            //curl_setopt($ch,CURLOPT_SSL_CIPHER_LIST,"ecdhe_ecdsa_aes_128_sha");
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $cacert=dirname(__FILE__).DIRECTORY_SEPARATOR."cacert.pem";
        curl_setopt($ch,CURLOPT_CAINFO,$cacert);
        $re=curl_exec($ch);
        $err=curl_error($ch);
        curl_close($ch);
        return $re?$re:$err;
    }

}