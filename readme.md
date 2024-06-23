# AuthWithJwt 

This project sets up two Laravel microservices (`auth-service` and `api-consumer`) using Docker. It includes a MariaDB database for the `auth-service` and utilizes JWT for authentication.

## Prerequisites

Make sure you have the following software installed:

- Docker
- Docker Compose

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/awstalib/AuthWithJwt
   cd AuthWithJwt
   ```
2. Build and start the Docker containers:

```bash   docker-compose up --build ```

The auth-service will run the app:setup command during startup to migrate the database and run seeders.

Services

MariaDB: A MariaDB database server running on port 3307.
auth-service: A Laravel service handling authentication, running on port 8080.
api-consumer: A Laravel service consuming the API, running on port 8081.

# Usage

Automaticly create user with this cred : username=aws,password=12345678

http://localhost:8080/login

# Custom Laravel Commands

SetupCommand
The SetupCommand handles the initial setup of the auth-service application, including generating a free user. It logs messages to the console to provide feedback during the setup process.

```bash
 php artisan app:setup
 ```