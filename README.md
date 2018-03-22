# bt-search
磁力链接搜索器


## 环境准备

```
apt install nginx php php-curl php-gd php-xml composer nodejs npm
npm install -g bower
composer install
bower install
```

## php配置
以下配置根据实际需要做调整
```
extension_dir = "/usr/lib/php/xxxxx"
cgi.fix_pathinfo = 0

# 建议打开错误调试
error_reporting = E_ALL
display_errors = On
```


## nginx配置
因为nginx默认不支持PATH_INFO，所以需要做如下特殊配置

```
location ~ \.php {
    #fastcgi_pass remote_php_ip:9000;
    fastcgi_pass unix:/run/php/php7.0-fpm.sock;
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
> 重写需在 `location ~ \.php` 之后

# docker
容器版本，开箱即用，不需要繁琐的配置
## 容器构建
切换到docker目录后执行
```
docker build -t ecat/bt-search-docker .
```
运行容器
```
docker run -d --name bt-search-docker -p 127.0.0.1:81:80 ecat/bt-search-docker
```
> 可在宿主机用nginx添加一个指向docker容器的反向代理向外提供服务