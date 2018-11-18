FROM php:7.1-cli

WORKDIR /app
ADD . .

ENTRYPOINT ["php", "run.php", "namespace:refactor"]
CMD ["--dry-run"]
