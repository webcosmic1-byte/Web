# Use an official PHP image with Apache
FROM php:8.2-apache

# 1. Install system dependencies and sendmail for PHP mail() support
RUN apt-get update && apt-get install -y \
    sendmail \
    libpng-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 3. Copy your website files
COPY . /var/www/html/

# 4. Set permissions
# Ensure www-data (Apache) owns the files
RUN chown -R www-data:www-data /var/www/html

# 5. CRITICAL: Give Apache permission to write/clear answers.txt
# This allows the 10-minute script to empty the file after mailing
RUN chmod 666 /var/www/html/answers.txt

# 6. Configure PHP to use sendmail
RUN echo "sendmail_path=/usr/sbin/sendmail -t -i" >> /usr/local/etc/php/conf.d/sendmail.ini

# Tell Docker to listen on port 80
EXPOSE 80

# Start sendmail and Apache together
CMD ["sh", "-c", "service sendmail start; apache2-foreground"]
