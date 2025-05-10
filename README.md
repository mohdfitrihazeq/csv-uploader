**CSV Uploader**

A simple Laravel-based web application for uploading and validating CSV files before saving them into the database. This system ensures each uploaded file is processed, and duplicates or failed records are tracked with reasons.

**Features**

Upload and validate .csv files

Check for duplicate records using hash values

Store successful uploads and log failures with reasons

Track status of each file (Pending, Success, Failed)

Display upload history and results


**💪 Tech Stack**

Laravel 10+

PHP 8+

MySQL / MariaDB

Bootstrap (optional for frontend)

jQuery / JavaScript


**🚀 How to Run**

**Clone the repository**

git clone https://github.com/mohdfitrihazeq/csv-uploader.git
cd csv-uploader


**1. Install dependencies**

composer install


**2. Create .env file**

cp .env.example .env


**3. Set your database configuration in .env:**

DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password


**4. Generate application key**

php artisan key:generate


**5. Run migrations**

php artisan migrate


**6. Run the app**

php artisan serve

Visit: http://localhost:8000


📁 File Structure

app/Models/Upload.php — Eloquent model for file uploads

app/Http/Controllers/UploadController.php — Handles upload logic

resources/views/ — Blade templates for frontend

database/migrations/ — Table schema for uploads


📝 License

This project is open-sourced under the MIT License.
