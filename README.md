# To-do Application
Deployed on Railway: 
https://todo-application-production-09ad.up.railway.app/

## Design and approach
### Frontend: Vue.js

### Backend: Laravel
Handles communication between frontend and database. 
Provides functions to sanitize inputs. 

### Database: SQLite
Pros: Lightweight, simple setup, stored in single file, works because app does not have too many requests
Downsides: Does not support multiple users

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