# phptestapi

## Project File Structure

```
    /phptestapi
    ├── /app
    │   ├── /Controllers            # Controllers to handle requests
    │   │   ├── UserController.php
    │   │   └── FileController.php
    │   ├── /Models                 # Database models
    │   │   ├── User.php
    │   │   └── File.php
    │   ├── /Helpers                # Reusable helper classes
    │   │   ├── Response.php        # JSON response handler
    │   │   └── Auth.php            # Authentication helper (e.g., token generation, validation)
    │   ├── /Middlewares            # Middleware classes
    │   │   ├── AuthMiddleware.php   # Token authorization middleware
    │   │   └── ValidationMiddleware.php # Input validation and sanitization
    │   ├── /Schemas                # Schema and migration handlers
    │   │   ├── Schema.php          # Schema builder for migrations
    │   │   └── MigrationInterface.php # Interface for migration classes
    │   └── /Services               # Services for business logic
    │       ├── UserService.php
    │       └── FileService.php
    ├── /bootstrap
    │   └── bootstrap.php           # Bootstrap file for initialization and autoloading
    ├── /config
    │   └── database.php            # Database configuration file
    ├── /cli
    │   ├── run_migrations.php      # Command-line script to run migrations
    │   └── seed_database.php       # Command-line script to seed database (optional)
    ├── /migrations                 # Migration files
    │   ├── 2024_11_05_134629_create_users_table.php
    │   └── 2024_11_05_135537_create_files_table.php
    ├── /public                     # Publicly accessible files
    │   └── index.php               # Entry point for the application
    ├── /routes
    │   └── api.php                 # Route definitions for API endpoints
    ├── /storage                    # Storage for logs, uploaded files, etc.
    │   └── /logs
    │       └── app.log             # Application log file
    ├── /tests                      # Test files
    │   └── ExampleTest.php
    ├── composer.json               # Composer dependencies
    └── helpers.php                 # Global helper functions

```

## Folder and File Descriptions

- /app: Contains the core application logic.

  - Controllers: Manage incoming requests and call services or models as needed.
  - Models: Represent database tables and encapsulate database interactions.
  - Helpers: Include reusable utilities like Response for JSON handling and Auth for authentication.
  - Middlewares: Define middleware classes for authorization, validation, etc.
  - Schemas: Store classes related to schema migrations, including Schema for creating and dropping tables and interfaces for migrations.
  - Services: Handle the business logic for each resource, providing a layer between controllers and models.

- /bootstrap: Contains the bootstrap.php file, where the project initializes configurations, autoloading, and dependencies.

- /config: Holds configuration files like database.php, where database connection settings are defined.

- /cli: Contains command-line scripts for running migrations, seeding data, and any other CLI-related tasks.

- /migrations: Holds individual migration files. Each migration file contains an up and down method for schema changes.

- /public: Contains index.php, the main entry point for HTTP requests. This file is publicly accessible and routes requests to the appropriate controller.

- /routes: Defines API routes, typically grouped in api.php for organizing route definitions.

- /storage: Houses files for logging and other temporary data. Inside, logs stores log files.

- /tests: Contains test files for application testing.

- composer.json: Manages project dependencies with Composer.

- helpers.php: A global file for any helper functions (e.g., the response() function for Response).

This structure provides clear separation of concerns, making the project maintainable, scalable, and easy to navigate as it grows.

## Run Project

$ php -S localhost:8000
