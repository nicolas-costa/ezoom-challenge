# Tasks API

The Tasks API is a RESTful web service designed to manage tasks. It provides endpoints for creating, updating, retrieving, and deleting tasks.

## Features

- Create new tasks with titles, descriptions, dates, locations, and details.
- Update existing tasks.
- Retrieve details of individual tasks
- Delete unwanted tasks.

## Technologies Used

- PHP 8: A popular general-purpose scripting language that is especially suited to web development.
- Docker: Docker is a platform designed to help developers build, share, and run container applications.
- Laravel: A PHP framework for building web applications.
- MySQL: A relational database management system used for storing tasks data.
- Dompdf: A library for generating PDF reports from HTML templates.

## Installation

To install the TAsks API locally, follow these steps:

1. Clone the repository to your local machine.
2. Install Composer dependencies by running `composer install`.
3. Create a `.env` file by copying the `.env.example` file, and configure your database connection.
4. Generate an application key by running `php artisan key:generate`
5. Generate a jwt key by running `php artisan jwt:secret` and answer yes to the question
6. There is a container for the database, you can spin it up by running `docker-compose up -d`
7. Run database migrations and seeders using `php artisan migrate --seed`.
8. Start the development server with `php artisan serve`.
9. The API should now be accessible at `http://localhost:8000`.
10. You can use the user `user@ezoom.com` and password `123456` to authenticate and test the API

## API Documentation

For detailed information on available endpoints and how to use them, refer to the [API documentation](/docs/api.yaml).

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
