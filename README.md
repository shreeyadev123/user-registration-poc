# Laravel User Sync - Proof of Concept
syncing user data from a main Laravel application to a secondary Laravel microservice using API calls, Redis queues.

Project structure
├── .gitignore
├── docker-compose.yml
├── src           # Main Laravel app (user registration, email, etc.)
└── src-user-sync  # sync service with separate db

Project setup instruction
- clone repo
- create .env files
- update cacge driver to redis
- add maitrap creds for sending email in env and sync db creds
- start docker container docker-compose up --build -d
- Run migrations
    - # Main app  docker exec -it laravel-app php artisan migrate
    - # Sync service docker exec -it sync-app php artisan migrate
- Run queue workers
    - docker exec -it laravel-app php artisan queue:work redis
- Run register api in postman
  curl --location 'http://localhost:8000/api/register' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data-raw '{
  "email": "jazz@example.com",
  "firstName": "Jazz",
  "lastName": "Gill",
  "password": "dsdfqwee"
}'
