# Laravel Nested Categories

This project demonstrates how to implement nested categories (parent-child structure) in Laravel.

## Features
- Add unlimited levels of nested categories
- Self-referencing `parent_id` model
- RESTful API-ready

## Installation
```bash
git clone https://github.com/yourusername/laravel-nested-categories.git
cd laravel-nested-categories
composer install
cp .env.example .env
php artisan key:generate



## Default Admin User

After running `php artisan migrate --seed`, you can log in using:

- **Email:** admin@gmail.com  
- **Password:** 123456
