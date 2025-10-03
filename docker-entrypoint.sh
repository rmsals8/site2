#!/bin/bash
set -e

echo "=== WordPress Docker Entrypoint ==="

# Wait for database to be ready
if [ -n "$WORDPRESS_DB_HOST" ]; then
    # Extract host and port from WORDPRESS_DB_HOST
    DB_HOST=$(echo "$WORDPRESS_DB_HOST" | cut -d: -f1)
    DB_PORT=$(echo "$WORDPRESS_DB_HOST" | cut -d: -f2)
    
    # If no port specified, use default 3306
    if [ "$DB_HOST" = "$DB_PORT" ]; then
        DB_PORT="3306"
    fi
    
    echo "Waiting for database at $DB_HOST:$DB_PORT..."
    
    for i in {1..30}; do
        if mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" -u"$WORDPRESS_DB_USER" -p"$WORDPRESS_DB_PASSWORD" --silent 2>/dev/null; then
            echo "Database is ready!"
            break
        fi
        echo "Attempt $i/30: Database is unavailable - sleeping..."
        sleep 2
    done
fi

# Create wp-config.php with environment variables if it doesn't exist
if [ ! -f /var/www/html/wp-config.php ]; then
    echo "Creating wp-config.php from environment variables..."
    
    if [ -f /var/www/html/wp-config-sample.php ]; then
        cp /var/www/html/wp-config-sample.php /var/www/html/wp-config.php
        
        # Replace database settings
        sed -i "s/database_name_here/$WORDPRESS_DB_NAME/" /var/www/html/wp-config.php
        sed -i "s/username_here/$WORDPRESS_DB_USER/" /var/www/html/wp-config.php
        sed -i "s/password_here/$WORDPRESS_DB_PASSWORD/" /var/www/html/wp-config.php
        sed -i "s/localhost/$WORDPRESS_DB_HOST/" /var/www/html/wp-config.php
        
        # Add salt keys from WordPress API
        echo "Fetching WordPress salt keys..."
        SALT=$(curl -sS https://api.wordpress.org/secret-key/1.1/salt/ 2>/dev/null || echo "")
        
        if [ -n "$SALT" ]; then
            # Remove the placeholder lines and add real salt
            sed -i "/put your unique phrase here/d" /var/www/html/wp-config.php
            
            # Add the salt keys before the table prefix line
            sed -i "/\$table_prefix/i\\
$SALT
" /var/www/html/wp-config.php
        fi
        
        # Add extra configuration if provided
        if [ -n "$WORDPRESS_CONFIG_EXTRA" ]; then
            echo "$WORDPRESS_CONFIG_EXTRA" >> /var/www/html/wp-config.php
        fi
        
        echo "wp-config.php created successfully!"
        echo "Database config: Host=$WORDPRESS_DB_HOST, Name=$WORDPRESS_DB_NAME, User=$WORDPRESS_DB_USER"
    else
        echo "Warning: wp-config-sample.php not found!"
    fi
else
    echo "wp-config.php already exists, skipping creation"
fi

# Fix permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html
find /var/www/html -type d -exec chmod 755 {} \; 2>/dev/null || true
find /var/www/html -type f -exec chmod 644 {} \; 2>/dev/null || true
chmod -R 775 /var/www/html/wp-content 2>/dev/null || true

echo "=== Starting Apache ==="

# Execute the main command (apache2-foreground)
exec "$@"
