FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

WORKDIR /var/www

COPY . .

CMD ["php", "-S", "0.0.0.0:8000", "-t", "."]

# docker build -t php-env .

# (powershell) ->
# docker run -d -p 8000:8000 -v "${PWD}:/var/www" --name php-server php-env

# (CMD) - >
# docker run -d -p 8000:8000 -v "%cd%:/var/www" --name php-server php-env

# reinstalar pacotes do composer em caso de erro ou falta de dependências:
    # composer require kreait/firebase-php google/cloud-firestore -W
    # IMPORTANTE: executar comando na pasta packages