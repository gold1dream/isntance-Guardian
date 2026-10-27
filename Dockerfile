FROM php:8.4-cli

# Instala dependências do sistema e o Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia os arquivos do projeto
COPY . .

# Instala as dependências via Composer se houver composer.json
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader; fi

EXPOSE 10000

# Sobe o servidor embutido do PHP usando a porta dinâmica do Render (ou 10000 como padrão)
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t pages"]