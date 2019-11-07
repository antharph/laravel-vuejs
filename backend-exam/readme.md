Specs:

- https://laravel.com/docs/6.x
- update .env file's DB_HOST, DB_DATABASE, DB_USERNAME and DB_PASSWORD
- Run 'php artisan migrate'
- For PATCH and DELETE request, the request should be POST but add "_method" parameter like {"title": "new title","_method": "PATCH"} that's how Laravel works

Note: I have problem running the front-end so I'm using Postman to develop the back-end
