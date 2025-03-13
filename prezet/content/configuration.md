---
title: Configuration Remedis
excerpt: Installation 
date: 2024-06-27
category: Getting Started
image: /prezet/img/ogimages/configuration.webp
---

## Prerequisites
Before setting up a cloned Laravel 11 project, ensure you have the following installed:

- PHP >= 8.2
- Composer
- MySQL (Database)
- Node.js & NPM (for frontend dependencies)
- Git (optional but recommended)

## Step 1: Install Dependencies
Run the following command to install PHP dependencies:

```sh
composer install
```

Install Node.js dependencies:

```sh
npm install
```

## Step 2: Configure Environment
Copy the `.env.example` file and rename it to `.env`:

```sh
cp .env.example .env
```

Generate the application key:

```sh
php artisan key:generate
```

## Step 3: Configure Database
Open `.env` and update the database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Then run the migrations:

```sh
php artisan migrate --seed
```

## Step 4: Build Frontend Assets
Compile frontend assets:

```sh
npm run build
```

## Step 5: Serve the Application
Start the Laravel development server:

```sh
php artisan serve
```

The application will be accessible at `http://127.0.0.1:8000`.

## Optional: Configure Vite for Hot Reloading
To enable hot module replacement for Vue or React, run:

```sh
npm run dev
```