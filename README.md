# Admin Settings Manager

This Laravel module provides a simple CRUD (Create, Read, Update, Delete) interface for managing basic website configuration settings. It enables administrators to easily control and update core settings used throughout the application.

---

## 🔧 Features

- Manage **Website Name**
- Set **Contact Email**
- Configure **Per Page Record Limit**
- Define **Date Format**
- Admin-friendly CRUD interface
- Validations and error handling

---

## Usage

1. **Create**: Add new website settings such as name, contact email, record limit, and date format.
2. **Read**: View all current settings in a user-friendly admin panel.
3. **Update**: Edit existing website configuration settings.
4. **Delete**: Remove settings that are no longer required.

| Method | Endpoint            | Description                        |
|--------|---------------------|------------------------------------|
| GET    | `/settings`         | List all website settings          |
| POST   | `/settings`         | Create new website setting         |
| GET    | `/settings/{id}`    | Get details of a specific setting  |
| PUT    | `/settings/{id}`    | Update a website setting           |
| DELETE | `/settings/{id}`    | Delete a website setting           |

---

## Requirements

- PHP 8.2+
- Laravel Framework

---

## Installation

1. Add the package to your Laravel project:
    ```bash
    composer require admin/settings:@dev
    ```
2. Publish the config and migration files:
    ```bash
    php artisan settings:publish --force
    ```
3. Run migrations:
    ```bash
    php artisan migrate
    ```

---

## Configuration

Edit the `config/settings.php` file to customize module settings.

---

## Protecting Admin Routes

Protect your admin settings routes using the provided middleware:

```php
Route::middleware(['admin.settings'])->group(function () {
    // Admin settings routes here
});
```

---

## License

This package is open-sourced software licensed under the MIT license.
