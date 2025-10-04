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

# Set ownership and permissions
RUN chown -R www-data:www-data /var/www/html
RUN find /var/www/html -type d -exec chmod 755 {} \;
RUN find /var/www/html -type f -exec chmod 644 {} \;
RUN chmod -R 775 /var/www/html/wp-content

# Change Apache listen port from 80 ➜ 8080
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/default-ssl.conf

# Fix Apache Directory permissions
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/Require all denied/Require all granted/' /etc/apache2/apache2.conf && \
    sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/ s/Require all denied/Require all granted/' /etc/apache2/apache2.conf

# Enable Apache modules
RUN a2enmod rewrite

# Suppress Apache warning
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf && a2enconf servername

# Expose new application port
EXPOSE 8080

# Override CMD to create wp-config.php and start Apache
CMD bash -c ' \
if [ ! -f /var/www/html/wp-config.php ]; then \
    echo "Creating wp-config.php..."; \
    cp /var/www/html/wp-config-sample.php /var/www/html/wp-config.php && \
    sed -i "s/database_name_here/${WORDPRESS_DB_NAME}/" /var/www/html/wp-config.php && \
    sed -i "s/username_here/${WORDPRESS_DB_USER}/" /var/www/html/wp-config.php && \
    sed -i "s/password_here/${WORDPRESS_DB_PASSWORD}/" /var/www/html/wp-config.php && \
    sed -i "s/localhost/${WORDPRESS_DB_HOST}/" /var/www/html/wp-config.php && \
    SALT=$(curl -sS https://api.wordpress.org/secret-key/1.1/salt/) && \
    printf "%s\n" "g/put your unique phrase here/d" "a" "$SALT" "." "w" | ed -s /var/www/html/wp-config.php && \
    chown www-data:www-data /var/www/html/wp-config.php; \
fi && \
apache2-foreground'
