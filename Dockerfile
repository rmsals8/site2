FROM wordpress:latest

# Apache 모듈 활성화
RUN a2enmod rewrite

# Apache 포트 8080 설정
RUN echo "Listen 8080" > /etc/apache2/ports.conf
RUN echo "<VirtualHost *:8080>" > /etc/apache2/sites-available/000-default.conf
RUN echo "    ServerAdmin webmaster@localhost" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    DocumentRoot /var/www/html" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    <Directory /var/www/html>" >> /etc/apache2/sites-available/000-default.conf
RUN echo "        Options Indexes FollowSymLinks" >> /etc/apache2/sites-available/000-default.conf
RUN echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf
RUN echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    ErrorLog \${APACHE_LOG_DIR}/error.log" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    CustomLog \${APACHE_LOG_DIR}/access.log combined" >> /etc/apache2/sites-available/000-default.conf
RUN echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf

# PHP 설정 최적화
RUN { \
    echo 'upload_max_filesize = 64M'; \
    echo 'post_max_size = 64M'; \
    echo 'memory_limit = 256M'; \
    echo 'max_execution_time = 300'; \
    echo 'max_input_vars = 3000'; \
    echo 'file_uploads = On'; \
} > /usr/local/etc/php/conf.d/uploads.ini

# WordPress 파일 권한 설정
RUN chown -R www-data:www-data /var/www/html

# wp-content 폴더 복사 (테마, 플러그인 등)
COPY wp-content/ /var/www/html/wp-content/

# WordPress 파일 권한 설정
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/wp-content

# 포트 8080 노출
EXPOSE 8080

# 환경변수 설정
ENV APACHE_PORT=8080

# Apache 시작
CMD ["apache2-foreground"]
