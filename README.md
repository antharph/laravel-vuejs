# laravel-vuejs

# Development Environment
- PHP 7.3.11
- Node v12.13.0
- NPM 6.12.0


# Setup & Testing
1. cd backend-exam/
2. composer install
3. php artisan migrate
4. php artisan serve
5. Update front-end-vuejs\src\store\index.js getters.baseUrl ex. http://127.0.0.1:8000
6. Inside front-end-vuejs directory, run 'npm install' then 'npm run serve'
7. Visit the resulting url like http://localhost:8080/
8. Click on Register
9. Click on Login link and login using your email and password
10. Click on Add Post. Add data and submit. It should redirect to home page where you'll see your post. If it doesn't please reload and enter data again :)
