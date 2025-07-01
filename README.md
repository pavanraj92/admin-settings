# Website Basic Info Module

This Laravel module provides a simple CRUD (Create, Read, Update, Delete) interface for managing basic website configuration settings. It is designed to help admins easily control and update core settings used throughout the application.

---

## 🔧 Features

-   Manage **Website Name**
-   Set **Contact Email**
-   Configure **Per Page Record Limit**
-   Define **Date Format**
-   Admin-friendly CRUD interface
-   Validations and error handling

---

## 🗂️ Fields

| Field Name      | Description                         |
| --------------- | ----------------------------------- |
| website_name    | Name of the website                 |
| contact_email   | Email used for general contact      |
| per_page_record | Number of records per pagination    |
| date_format     | Format in which dates are displayed |

---

## Installation

1. Add the package to your Laravel project:
    ```bash
    composer require admin/settings
    ```
2. Publish the config and migration files:
    ```bash
    php artisan vendor:publish --provider="Admin\Settings\SettingsServiceProvider"
    ```
3. Run migrations:
    ```bash
    php artisan migrate
    ```

---

## Usage

Protect your admin routes using the provided middleware:

```php
Route::middleware(['admin.settings'])->group(function () {
     // Admin settings routes here
});
```

---

## Configuration

Edit the `config/settings.php` file to customize module settings.

---

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
