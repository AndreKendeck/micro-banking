# 🏧 Micro Banking System

This Laravel Application is an approach on how to handle Banking Transactions with daily reporting and giving users a rolling balance for a certain period

### Tech Stack
![php](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![mariadb](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![vue](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D)
![tailwind](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![npm](https://img.shields.io/badge/npm-CB3837?style=for-the-badge&logo=npm&logoColor=white)

### Requirments

- PHP 8.1 or higher
- MariaDB 10

### Packages Used

 - [PHP Money](https://github.com/cknow/laravel-money): This package is **vital** makes it easier to manipulate Currencies in PHP, using floating point numbers or decimals will lead to inconsistencies, especially hanlding cents. `0.3 + 0.6 = 0.90000001` which can cause massive issues when working with large amounts

### Installation

1. Clone this repo `git clone https://github.com/AndreKendeck/micro-banking.git`
2. Run `composer install -vvv` 
3. Add create a database in your local machine and add your database credientials to the .env file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<your database name>
DB_USERNAME=<database username>
DB_PASSWORD=<database password>
```
4. Optionally, if you use mailtrap you can add your creditials in the .env file
```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<mailtrap apikey>
MAIL_PASSWORD=<mailtrap apisecret>
MAIL_ENCRYPTION=tls
```

5. Build the Javascript Assest `npm run build`

6. Finally Run the migration and seeds using `php artisan migrate --seed` 

7. You can also for local development create your own user using `php artisan user:create` and follow the prompts 

8. If you are on Macbook you can make use of Laravel Valet to access your the application alternatively you run `php artisan serve` to locally access your application 

9. (Optionally) To run tests you can run `php artisan test`

## Console Commands
if you want to seed a specific account with transaction using you can do so using the console command
`php artisan transactions:seed <account_number> --count=<number_of_transactions>`

## Development
- [Naming Conventions / Standards ](https://xqsit.github.io/laravel-coding-guidelines/docs/naming-conventions/)