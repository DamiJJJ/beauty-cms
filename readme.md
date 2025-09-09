# Beauty CMS

System Zarządzania Treścią (CMS) dla salonu piękności, umożliwiający zarządzanie klientami i wizytami. Projekt jest w fazie rozwoju.

## Główne Funkcjonalności

- **Dashboard:** Szybki podgląd na najważniejsze statystyki, w tym całkowitą liczbę klientów i listę ostatnio dodanych.
- **Moduł Klientów:** Pełna obsługa CRUD (Create, Read, Update, Delete) dla klientów. Możliwość dodawania, edytowania, usuwania i przeglądania szczegółów każdego klienta wraz z notatkami.
- **Wyszukiwanie:** Szybkie wyszukiwanie klientów po imieniu, nazwisku lub numerze telefonu.
- **Prosty interfejs:** Czysty i intuicyjny design oparty na czystym HTML, CSS i JavaScript.

## Technologia

- **Backend:** PHP (z użyciem wzorca Singleton do połączenia z bazą danych)
- **Frontend:** HTML, CSS, JavaScript (Vanilla JS)
- **Baza danych:** MySQL

## Jak uruchomić projekt

1.  Upewnij się, że masz zainstalowany serwer lokalny (np. XAMPP, WAMP).
2.  Skopiuj pliki projektu do katalogu serwera (np. `C:/xampp/htdocs/beauty-cms`).
3.  Utwórz bazę danych o nazwie `beauty_cms` w phpMyAdmin.
4.  W pliku `config/database.php` zaktualizuj dane logowania do bazy danych.
5.  Zaimportuj strukturę tabel. Przykład:
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
6.  Przejdź do `http://localhost/beauty-cms/` w swojej przeglądarce.
