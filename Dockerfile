FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

WORKDIR /var/www

COPY . .

CMD ["php", "-S", "0.0.0.0:8000", "-t", "."]

# docker build -t php-firestore .
# docker run -d -p 8000:8000 -v "${PWD}:/var/www" --name php-server php-firestore (powershell)
# docker run -d -p 8000:8000 -v "%cd%:/var/www" --name php-server php-firestore (CMD)

# reinstall packages :
    # composer require kreait/firebase-php google/cloud-firestore -W

    
# ----- // ----- IGNORE --- // -----

    
# FROM php:8.2-cli

# RUN apt-get update && apt-get install -y \
#     unzip \
#     libzip-dev \
#     zip \
#     git \
#     && docker-php-ext-install zip

# RUN pecl install grpc protobuf \
#     && docker-php-ext-enable grpc protobuf

# WORKDIR /var/www

# COPY . .

# CMD ["php", "-S", "0.0.0.0:8000", "-t", "."]