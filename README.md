1. Clone the repository
2. run `composer install`
3. run `php artisan key:generate`
4. run `cp .env.example .env`
5. fill .env file with your database credientials, change value of `API_KEY` with the key you   will use when you call the api
6. run `php artisan serve`
7. follow the api documenation through http://127.0.0.1:8000/api/documentation
