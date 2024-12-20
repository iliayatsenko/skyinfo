# Skyinfo

This is a test web application, developed for educational purposes, which can notify about configured wether conditions via email or/and SMS.

## Installation

1. Clone the repository
2. Copy `.env.example` to `.env`
3. Set required environment variables in `.env` file:
   ```
   DB_HOST=mariadb
   DB_USERNAME=sail
   DB_PASSWORD=password
   REDIS_HOST=redis
   MAIL_MAILER=smtp
   MAIL_HOST=host.docker.internal
   MAIL_PORT=1025
   
   # Next vars are optional if you're going to only run tests
   WEATHER_API_KEY={your weatherapi.com API key}
   VONAGE_KEY={your Vonage API key}
   VONAGE_SECRET={your Vonage API secret}
   VONAGE_SMS_FROM={your Vonage phone number (random)}
   ```
3. Install dependencies: `composer install`
3. Run containers: `./vendor/bin/sail up -d`
4. Execute migrations: `./vendor/bin/sail artisan migrate`
5. Install npm dependencies: `./vendor/bin/sail npm install`
6. Build assets: `./vendor/bin/sail npm run build`
7. Run tests: `./vendor/bin/sail test`

## Usage
1. Go to http://localhost:80
2. Register a new user
3. Create a new Skymonitor (i.e. notifications configuration)
4. Run the command `./vendor/bin/sail artisan app:process-skymonitors` to process Skymonitors
5. Run the queue worker `./vendor/bin/sail artisan queue:work -v` to process notifications
