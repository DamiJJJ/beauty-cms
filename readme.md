# Beauty CMS

A simple Content Management System (CMS) designed for beauty salons to manage clients and appointments. The project is currently under development.

## Key Features

- **Dashboard:** A quick overview of key metrics, including the total number of clients and a list of recently added clients.
- **Client Management:** Full **CRUD** (Create, Read, Update, Delete) support for client records. This includes adding, editing, deleting, and viewing detailed client information and notes.
- **Intuitive Interface:** A clean and user-friendly design built with plain HTML, CSS, and JavaScript.

## Technologies

- **Backend:** PHP (utilizing the Singleton design pattern for database connection)
- **Frontend:** **HTML**, **CSS**, and **Vanilla JavaScript**.
- **Database: MySQL**

## Getting Started

1.  Make sure you have a local server environment (like **XAMPP** or **WAMP**) installed and running.
2.  Copy the project files to your server's root directory (e.g., `C:/xampp/htdocs/beauty-cms`).
3.  Create a new database named `beauty_cms` in phpMyAdmin.
4.  Update the database connection credentials in `config/database.php`.
5.  Import the required table structure into your new database. Use the following SQL example:
    ```sql
    CREATE TABLE clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        email VARCHAR(255),
        birth_date DATE,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ```
6.  Open your web browser and navigate to `http://localhost/beauty-cms/` to access the application.
