# PHP Invest

```
cp .env .env.local
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
symfony server:start
```
