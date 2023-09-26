# Getting Started with Laravel Comments App

## Installation

**Clone the Repository**

First, clone this repository to your local machine using the following command:

### ` git clone <repository-url> `

**Navigate to the Project Directory**

Change your current directory to the project's root folder. This step is important because it ensures that you are working within the correct project context. You can use the following command: 

### ` cd <project-path> `

**Install Dependencies**

Install the necessary PHP and Laravel dependencies by running the following command:

### ` composer install `

This command will download and install all the required packages and libraries specified in the project's composer.json file.

**Setup .env File**

Create a .env file in the root directory of your Laravel project. You can use the provided .env.example as a template.
Configure database-related variables like DB_DATABASE , DB_HOST, DB_PORT, DB_USERNAME, and DB_PASSWORD to match your database setup.
For security reasons, generate a random 64-character string to use as your JWT_SECRET. You can use Laravel's built-in php artisan command to generate this string:

### ` php artisan jwt:secret `

## Start the Development Server

To start the development server and run the Laravel application locally, use the following command:

### ` php artisan serve `

This will launch the Laravel development server, and you can access your application at http://localhost:8000 in your web browser. The development server will automatically reload the app as you make changes to the code.


## Contributing
Contributions to this project are welcome! If you'd like to contribute, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes and commit them.
4. Push your changes to your fork.
5. Create a pull request to the original repository.
