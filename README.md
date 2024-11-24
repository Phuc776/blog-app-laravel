## Blog-App

A simple Laravel-based blog application.

### **Installation Steps**

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/Phuc776/blog-app-laravel.git
   cd blog-app
   
2. **Install Dependencies:**
    ```
    composer install

3. **Configure Environment:**

Copy the .env.example file:

    cp .env.example .env    # On Windows: copy .env.example .env
Update the .env file with your database details:

*env*

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=blog-app
    DB_USERNAME=root
    DB_PASSWORD=your_password
4. **Generate Application Key:**
    ```
    php artisan key:generate

5. **Run Migrations:**
    ```
    php artisan migrate
    php artisan db:seed --class=class RolesSeeder

6. **Start the Development Server:**
    ```
    php artisan serve

7. **Access the Application:**

Visit http://127.0.0.1:8000 in your web browser.