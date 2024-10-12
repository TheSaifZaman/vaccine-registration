# Project Setup Guide

This guide will walk you through the process of cloning the repository and setting up the project with the required
environment variables.

## Prerequisites

- Git installed on the system
- PHP (>=8.1)
- Composer
- MySQL

## Steps

1. **Clone the repository**

   Open the terminal and run the following command for `ssh`:

   ```
   git clone git@github.com:TheSaifZaman/vaccine-registration.git
   ```

2. **Navigate to the project directory**

   ```
   cd vaccine-registration
   ```

3. **Install dependencies**

   Run the following command to install the project dependencies:

   ```
   composer install
   ```

4. **Create the .env file**

   Copy the `.env.example` file to create a new `.env` file:

   ```
   cp .env.example .env
   ```

5. **Update the .env file**

   Open the `.env` file in a text editor and update the following variables:

   ```
    APP_NAME= #with Appropriate Value
    APP_API_URL= #with Appropriate Value Example: localhost:8000/api
    DB_CONNECTION= #with Appropriate Value
    DB_HOST= #with Appropriate Value
    DB_PORT= #with Appropriate Value
    DB_DATABASE=vaccine_registration
    DB_USERNAME= #with Appropriate Value
    DB_PASSWORD= #with Appropriate Value
    QUEUE_CONNECTION=database
    MAIL_MAILER=smtp
    MAIL_HOST= #with Appropriate Value
    MAIL_PORT= #with Appropriate Value
    MAIL_USERNAME= #with Appropriate Value
    MAIL_PASSWORD= #with Appropriate Value
    MAIL_ENCRYPTION= #with Appropriate Value
   ```

   Make sure to fill in the empty fields with the specific configuration details.

6. **Generate the application key**

   Run the following command to generate the Laravel application key:

   ```
   php artisan key:generate
   ```

7. **Run database migrations and seeder**

   Set up the database by running the migrations and Seeder:

   ```
   php artisan migrate:fresh --seed
   ```

8. **Start the development server**

   Launch the Laravel development server:

   ```
    php artisan serve
   ```
   
9. **Start the queue worker** (if using queues)

   In a new terminal window, run:

   ```
    php artisan queue:work
   ```

- Make sure the MySQL server is running and the `vaccine_registration` database is created before running migrations.
- Configure the mail settings in the `.env` file to enable email functionality.

Now the project should be set up and running with the specified environment variables. Access the application by visiting `The Served Web Link` in the web browser.

## Notes:

- If I get enough time, I will use Full Text Search, Front and Backend Caching for faster Search Result
- For sending SMS along email notification, I would like to implement abstract factory design pattern.

---
- I should've added the API Documentation
- I should've added Test Cases.
