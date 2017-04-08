# bt-search
磁力链接搜索器

## nginx配置
因为nginx默认不支持PATH_INFO，所以需要做如下特殊配置

```
location ~ \.php {
    #fastcgi_pass remote_php_ip:9000;
    fastcgi_pass unix:/dev/shm/php-cgi.sock;
    fastcgi_index index.php;
    #PATH_INFO支持#
    fastcgi_split_path_info ^(.+\.php)(.*)$;
    fastcgi_param PATH_INFO $fastcgi_path_info;
    fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    include fastcgi.conf;
    }
location ~ .*\.(gif|jpg|jpeg|png|bmp|swf|flv|ico)$ {
    expires 30d;
    access_log off;
    }
location ~ .*\.(js|css|jsx)?$ {
    expires 7d;
    access_log off;
    }
####重写####
location / {
    if (!-e $request_filename){
        rewrite ^/(.*?)$ /index.php/app/index/$1 last;
    }
   }
```
