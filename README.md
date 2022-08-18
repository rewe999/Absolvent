# Currency Conversion

This project was made as an recruitment task for Absolvent.pl
This is a simple application (API) to convert exchange rates.

The application converts currencies on the based on the current exchange rate at the beginning of the day.

## Installation
### 1. Create `.env` file based on `.env.example`:
Linux:
```shell script
cp .env.example .env
```
Windows:
```shell script
copy .env.example .env
```
### 2. Run containers:
```shell script
docker-compose up -d
```
or
```shell script
./vendor/bin/sail up
```

### 3. Enter the container:
```shell script
docker exec -it (docker_id) /bin/bash
```

### 4. Fetch dependencies:
```shell script
composer install
```

### 5. Generate application key:
```shell script
 php artisan key:generate
```

### 6. Run migrations:
```shell script
 php artisan migrate
```

### 7. Generate API docs:
```shell script
 php artisan l5-swagger:generate
```

### 8. Now you can access the app here:
http://localhost/

Swagger documentation is available at the link:

http://localhost/api/documentation

## Author:
- [Patryk Zym](https://github.com/rewe999/)
