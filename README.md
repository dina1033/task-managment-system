# Task Management API System

## Introduction

Task Management API is a RESTful backend application built with Laravel.

The system allows authenticated users to manage projects and tasks, receive overdue task notifications, and monitor project statistics through a dashboard.


## Technology Used

![Laravel](https://img.shields.io/badge/Laravel-13-red)
![PHP](https://img.shields.io/badge/PHP-8.4-blue)
![MySQL](https://img.shields.io/badge/MySQL-8-orange)
![Docker](https://img.shields.io/badge/Docker-Enabled-2496ED)

## Architecture
- Repository Pattern
    - Abstracts the data access layer from the rest of the application and provides a clean separation of concerns.
    - A repository is created for each model (Auth,Project,Task,Dashboard) which are responsible for fetching and updating the data.
    - Allows for easy switching of data sources or updating the data access logic without affecting the rest of the application.
- Service Layer
    - Contains the application's business logic.
    - Keeps controllers lightweight and focused on handling HTTP requests.
- Policies
    - Handles authorization to ensure users can only access their own resources.
- API Resources
    - Provides consistent JSON responses.

## Installation and Usage

### Running the Project

1. Clone the repository: `git clone`.
2. Start the Docker containers: `docker compose up -d --build`.
3. Install the project dependencies: `docker compose exec php composer install`.
4. Create the environment file: `docker compose exec php cp .env.example .env`.
5. Generate the application key: `docker compose exec php php artisan key:generate`.
6. Run the database migrations and seed the database: `docker compose exec php php artisan migrate:fresh --seed`.
7. (Optional) Generate the API documentation:: `docker compose exec php php artisan scribe:generate`.

### Running the Queue Worker

1. To process queued jobs (such as overdue task notifications), run: docker compose exec php php artisan queue:work.

### Running the Scheduler

1. To execute scheduled commands during development: run: docker compose exec php php artisan schedule:work


### Running the Test Cases

1. Run the complete test suite using: `docker compose exec php php artisan test`.

### API Documentation

1. The API documentation using Scribe will be available at: http://localhost:8060/docs.

### Postman Collection

1. A ready-to-use Postman collection is included with the project: Task_Management_API.postman_collection.json Import it into Postman to test all available endpoints.


