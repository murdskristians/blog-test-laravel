# To launch the app you need to:

Step 1: Clone the Repository

1. Open a terminal or command prompt.
2. Navigate to the directory where you want to clone the project.
3. Clone the project from github.

Step 2: Navigate to the Project Directory

1. Change into the project directory:
cd your-repository

Step 3: Install Dependencies

1. Ensure you have Composer installed.
2. Run the following command to install the PHP: 
composer install
3. Ensure you have Node.js and npm installed.
4. Run the following command to install the Node.js dependencies:
npm install

Step 4: Set Up Environment Configuration

1. Copy the .env.example file to create a new .env file:
cp .env.example .env
2. Open the .env file in a text editor and update the following configurations as needed:
In my personal case i did use SQLite, so i configures like this:

DB_CONNECTION=sqlite

DB_DATABASE=C:/Users/Pc/Desktop/WORK/blog-test-laravel/database/database.sqlite

//DB_HOST=127.0.0.1
//DB_PORT=3306
//DB_DATABASE=laravel
//DB_USERNAME=root
//DB_PASSWORD=


Step 5: Generate Application Key

1. Run the following command to generate the application key:
php artisan key:generate

Step 6: Set Up the Database

1. Ensure your database server is running.
2. Run the following command to migrate the database:
php artisan migrate

Step 7: Seed the Database (Optional)

1. Run the following command:
php artisan db:seed

Step 8: Compile Assets

1. Run the following command to compile the frontend assets:
npm run dev

Step 9: Start the Development Server

1. Run the following command to start the Laravel development server:
php artisan serve

2. Open your web browser and navigate to the URL provided in the terminal (usually http://127.0.0.1:8000).

Step 10: Enjoy the code!
