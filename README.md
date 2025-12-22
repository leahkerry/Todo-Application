# To-do Application
Deployed on Railway:  
https://todo-application-production-09ad.up.railway.app/

## Design and approach
### Frontend: Vue.js
Vue.js: Javascript framework with built in tools for state management and routing. Simple and easy to integrate: perfect for small/medium applications like this one.  
Used inline styling with TailWindCSS for less code and simpler files. 

### Backend: Laravel  
PHP framework that gives complete ecosystem for web devs. Services database, frontend, and backend.   
Provides functions to sanitize inputs, deal with HTTP response codes.  

### Database: SQLite
Pros: Lightweight, simple setup, stored in single file, works because app does not have too many requests  
Downsides: Does not support multiple users  

### Testing
1. Created and ran automated API tests in /tests/Feature/TodoApiTest.php to ensure Todo database performed correct CRUD operations
2. Validated functionality by testing on localhost
3. Used curl to test HTTP status codes on command line
4. Tested on deployed application with Debug mode set to true. 

### Deployed application: Railway
Railway runs its own build commands, has verbose logging, and has flexible options to set production environment variables. It creates a build output quickly, for no cost, and without too much setup. 



## Startup intructions
1. Ensure you have PHP version >=8.4 and composer installed  

2. Clone the repository  
```
git clone https://github.com/leahkerry/Todo-Application
cd Todo-Application
```
3. Install PHP dependencies with composer  
`composer install`
  
4. Configure the environment  
`cp .env.example .env`  
Then generate an application key: `php artisan key:generate`  
In `.env` file, set APP_KEY to the generated value  
  
5. Run database migrations  
`php artisan migrate`  

6. Install node dependencies  
`npm install`  

7. In two terminals at the same time, run:  
```
php artisan serve
npm run dev
```
You should now be able to see the application in your web browser at:   
http://127.0.0.1:8000