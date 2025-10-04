FROM wordpress:latest

# Install required packages (including mysql-client for healthcheck)
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    vim \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Set upload limits
RUN echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini

# Clear /var/www/html completely first
RUN rm -rf /var/www/html/*

# Download and extract WordPress core files at build time
RUN curl -sSL https://wordpress.org/latest.tar.gz | tar -xz --strip-components=1 -C /var/www/html

# Verify index.php exists
RUN ls -la /var/www/html/index.php

# Copy wp-content directory (this will overwrite the default wp-content)
COPY wp-content/ /var/www/html/wp-content/

# Create wp-config.php at build time with hardcoded values
RUN cp /var/www/html/wp-config-sample.php /var/www/html/wp-config.php && \
    sed -i "s/database_name_here/blog4/" /var/www/html/wp-config.php && \
    sed -i "s/username_here/rmsals/" /var/www/html/wp-config.php && \
    sed -i "s/password_here/1q2w3e/" /var/www/html/wp-config.php && \
    sed -i "s/localhost/svc.sel4.cloudtype.app:30333/" /var/www/html/wp-config.php && \
    sed -i "s/\$table_prefix = 'wp_';/\$table_prefix = 'wp_';/" /var/www/html/wp-config.php && \
    echo "" >> /var/www/html/wp-config.php && \
    echo "/* SSL and URL Settings */" >> /var/www/html/wp-config.php && \
    echo "if (isset(\$_SERVER['HTTP_X_FORWARDED_PROTO']) && \$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {" >> /var/www/html/wp-config.php && \
    echo "    \$_SERVER['HTTPS'] = 'on';" >> /var/www/html/wp-config.php && \
    echo "}" >> /var/www/html/wp-config.php && \
    echo "define('WP_HOME', 'https://port-0-site2-m9aydkxq51acab43.sel4.cloudtype.app');" >> /var/www/html/wp-config.php && \
    echo "define('WP_SITEURL', 'https://port-0-site2-m9aydkxq51acab43.sel4.cloudtype.app');" >> /var/www/html/wp-config.php && \
    echo "define('FORCE_SSL_ADMIN', true);" >> /var/www/html/wp-config.php

# Set ownership and permissions
RUN chown -R www-data:www-data /var/www/html
RUN find /var/www/html -type d -exec chmod 755 {} \;
RUN find /var/www/html -type f -exec chmod 644 {} \;
RUN chmod -R 775 /var/www/html/wp-content

# Fix wp-admin directory permissions and ownership
RUN chown -R www-data:www-data /var/www/html/wp-admin && \
    chmod -R 755 /var/www/html/wp-admin && \
    find /var/www/html/wp-admin -name "*.php" -exec chmod 644 {} \;

# Create .htaccess file for WordPress
RUN echo "# BEGIN WordPress" > /var/www/html/.htaccess && \
    echo "<IfModule mod_rewrite.c>" >> /var/www/html/.htaccess && \
    echo "RewriteEngine On" >> /var/www/html/.htaccess && \
    echo "RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]" >> /var/www/html/.htaccess && \
    echo "RewriteBase /" >> /var/www/html/.htaccess && \
    echo "RewriteRule ^index\.php$ - [L]" >> /var/www/html/.htaccess && \
    echo "RewriteCond %{REQUEST_FILENAME} !-f" >> /var/www/html/.htaccess && \
    echo "RewriteCond %{REQUEST_FILENAME} !-d" >> /var/www/html/.htaccess && \
    echo "RewriteRule . /index.php [L]" >> /var/www/html/.htaccess && \
    echo "</IfModule>" >> /var/www/html/.htaccess && \
    echo "# END WordPress" >> /var/www/html/.htaccess

# Simple Apache configuration - just change port to 8080
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/g' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/Require all denied/Require all granted/' /etc/apache2/apache2.conf && \
    sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/ s/Require all denied/Require all granted/' /etc/apache2/apache2.conf && \
    echo "<Directory /var/www/html/wp-admin>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    Options Indexes FollowSymLinks" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

# Enable rewrite module
RUN a2enmod rewrite

# Set ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose new application port
EXPOSE 8080