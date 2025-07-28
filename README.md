# Admin Settings Manager

This Laravel module provides a simple CRUD (Create, Read, Update, Delete) interface for managing basic website configuration settings. It enables administrators to easily control and update core settings used throughout the application.

---

## Features

- Manage **Website Name**
- Set **Contact Email**
- Configure **Per Page Record Limit**
- Define **Date Format**
- Admin-friendly CRUD interface
- Validations and error handling

---

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

---

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-role-permission.git"
    }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/settings:@dev
    ```

### 3. **Publish assets:**
    ```bash
    php artisan settings:publish --force
    ```
---

## Usage

1. **Create**: Add new website settings such as name, contact email, record limit, and date format.
2. **Read**: View all current settings in a user-friendly admin panel.
3. **Update**: Edit existing website configuration settings.
4. **Delete**: Remove settings that are no longer required.

### Admin Panel Routes

| Method | Endpoint            | Description                        |
|--------|---------------------|------------------------------------|
| GET    | `/settings`         | List all website settings          |
| POST   | `/settings`         | Create new website setting         |
| GET    | `/settings/{id}`    | Get details of a specific setting  |
| PUT    | `/settings/{id}`    | Update a website setting           |
| DELETE | `/settings/{id}`    | Delete a website setting           |

---

## Protecting Admin Routes

Protect your admin settings routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // Admin settings routes here
});
```
---

## Database Table

- `settings` - Stores setitngs information
---

## Configuration

Edit the `config/settings.php` file to customize module settings.

---

## License

This package is open-sourced software licensed under the MIT license.
