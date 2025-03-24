🚀 Laravel 12 API
A RESTful API built with Laravel 12, featuring factories, seeders, and Postman for testing.

Features
✅ Data generation using factories & seeders
✅ CRUD operations for managing resources
✅ Structured API responses
✅ Postman collection for easy testing

Installation
Clone the repo:

bash
Copy
Edit
git clone https://github.com/yourusername/your-repo.git
cd your-repo
Install dependencies:

bash
Copy
Edit
composer install
Set up environment variables:

bash
Copy
Edit
cp .env.example .env
php artisan key:generate
Run migrations & seed database:

bash
Copy
Edit
php artisan migrate --seed
Start the server:

bash
Copy
Edit
php artisan serve
API Documentation
📌 Import the Postman collection included in the repo to test endpoints easily.
