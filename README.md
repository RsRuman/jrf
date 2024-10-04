## Installation guides
Please Follow the guideline to set up locally.

- Laravel 11 (php 8.3/8.2)

### Installation process after cloning from git

1. composer install
2. cp .env.example .env
3. run php artisan passport:client --personal and set value .env
   `PASSPORT_PERSONAL_ACCESS_CLIENT_ID
    PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET `
4. php artisan key:generate
5. set database mysql and update related things in .env (for example your database name, password)
6. php artisan migrate

Note: I also added postmen api collection in project directory name as JRF.postman_collection.json, Please check.
If you need any information, please let me know.
