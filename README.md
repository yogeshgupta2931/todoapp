## About Todo App

TodoApp is a fun Laravel application that has the following features:

-   User Registration
-   User Login
-   User Todo Management
-   Admin Login
-   Admin Todo Dashboard

## How to Install

-   Pull the git repository locally
-   Rename example.env file to .env file
-   Configure database settings as per your prefrence
-   Run the following commands in the terminal (make sure you are inside your project directory before running these command )

```
{project_directory} > composer install
{project_directory} > npm install
{project_directory} > npm run dev
{project_directory} > php artisan migrate:fresh --seed;
{project_directory} > php artisan serve;
```

-   This should install all the dependencies and create database with seeders required for the project

### Admin and User Login

-   Password for all accounts is `password`
-   Email Id for accounts can be fetched from `users` table

### Tested on System with

-   git
-   php 8+
-   node and npm
-   composer
-   mysql
-   Laravel 8.x
