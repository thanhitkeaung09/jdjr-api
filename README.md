
# JDJR Api

JDJR is job searching mobile application and apply the job position according to the job wanted , this is the backend api using laravel and the frontend dashboard is with react js .



## Features

- User can login with email and social account ( google )
- Verification system with code from email login
- User can choose the favourite job type and apply the job position for their carrier . 
- User can share the application
- User can contact the admin directly for their question and receive the answer when the admin approved
- Realtime message with firebase and websocket.
- In Dashboard , there are multiple admins corresponding to the category they created.
- Create , Update , Delete and Upload file .
- Can check the user questions and revise.



## System Requirements
- PHP 8.0+
- MySQL 5.7+
- Apache or Nginx 
## Configuration
- env
- Mail Configuration

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=username //google user name
MAIL_PASSWORD=password // google app password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"


## Local Installation
- git clone
- composer install 
- npm install
- create an empty MySQL database
- cp .env.example .env
- php artisan tinker
- App\Models\ApplicationKey::factory(1)->create();
- use in the client side with app-id and app-secret generated from the above command
- php artisan serve


## Running Project
- http://127.0.0.1:8000
## PhpMyadmin
- localhost:8080
- Username : root
- Password : 
