FROM wordpress:latest

# Install required packages
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    vim \
    && rm -rf /var/lib/apt/lists/*

# Set upload limits
RUN echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini

# Create uploads directory
RUN mkdir -p /var/www/html/wp-content/uploads && \
    chown -R www-data:www-data /var/www/html/wp-content/uploads

# --- Ensure WordPress core exists (some runtimes mount empty volume) ---
RUN curl -sSL https://wordpress.org/latest.tar.gz | tar -xz --strip-components=1 -C /var/www/html

# --- Copy wp-content directory ---
COPY wp-content/    /var/www/html/wp-content/

# --- Set ownership and permissions ---
RUN chown -R www-data:www-data /var/www/html
RUN find /var/www/html -type d -exec chmod 755 {} \;
RUN find /var/www/html -type f -exec chmod 644 {} \;
RUN chmod -R 775 /var/www/html/wp-content

# Change Apache listen port from 80 ➜ 8080 to avoid privileged port requirement in non-root containers (e.g., Cloudtype)
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/default-ssl.conf

# Suppress "Could not reliably determine the server's fully qualified domain name" warning
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf && a2enconf servername

# Expose new application port
EXPOSE 8080