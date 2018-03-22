# 容器化环境自动构建
FROM ubuntu
LABEL version="0.1"
LABEL description="bt-search-docker"
MAINTAINER ecat

# 环境变量配置
ENV www_root /srv/www
ENV www_name bt-search
ENV git_repo "https://github.com/ecator/bt-search.git"

ENV php_ini /etc/php/7.0/fpm/php.ini
ENV nginx_ini /etc/nginx/nginx.conf
ENV nginx_bt /etc/nginx/sites-enabled/bt-search.conf

# 软件环境安装
RUN apt-get update \
	&& apt-get install -y git nginx=1.10.3* php=1:7.0* php-curl php-xml composer nodejs npm \
	&& rm -rf /var/lib/apt/lists/*

RUN npm install -g bower \
	&& mkdir $www_root

WORKDIR $www_root

RUN git clone $git_repo --depth 1

WORKDIR $www_name

# 包依赖安装
RUN composer install \
	&& nodejs /usr/local/bin/bower install --allow-root

RUN apt-get purge -y git composer nodejs npm \
	&& apt-get autoremove -y \
	&& apt-get clean \
	&& apt-get autoclean

# 解决权限问题
RUN	chmod -R 755 * \
	&& chown -R www-data:www-data /srv

# 复制配置文件
COPY php.ini $php_ini
COPY nginx.conf $nginx_ini
COPY bt-search.conf $nginx_bt

# 入口脚本
COPY run.sh /

RUN chmod 777 /run.sh

EXPOSE 80/tcp

CMD /run.sh