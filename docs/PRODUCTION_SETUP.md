```
mkdir releases
cd releases
git clone git@github.com:pipan/ovaldo-api-php.git 1
cd 1
composer install --no-dev -o
cd ../../

cp releases/1/.env.example releases/1/.env
cp releases/1/.env.example .env

ln -s releases/1 current

ln -s current/artisan artisan

php artisan key:generate --ansi
echo "Installation successful"

```