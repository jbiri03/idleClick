FROM php:8.3-cli

WORKDIR /app


RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY . .

ENV PORT=10000

CMD sh -c "php -S 0.0.0.0:${PORT} -t /app"