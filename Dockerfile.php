FROM php:7.2-cli

COPY ./ /app
WORKDIR /app

RUN apt-get update && apt-get install -y default-mysql-client
RUN docker-php-ext-install mysqli
# RUN docker-php-ext-enable mysqli

CMD [ "php", "user_upload.php" ]