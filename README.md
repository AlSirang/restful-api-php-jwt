# RESTFUL API in PHP with JWT authentication

## How to configure project?

1. Clone the project
2. In root folder of your local project install required dependencies using `composer`. To install dependencies type:

   ```bash
   $ composer install
   ```

3. Regenerate the autoloader files based on the definitions in the composer.json file.

   ```bash
   $ composer dump-autoload
   ```

## Configuration Database

Update the database name, username and password.

```php
  "database" => [
    "host" => "127.0.0.1", # host
    "dbname" => "wefi_dashboard", # database name
    "user" => "phpmyadmin", # username
    "password" => "root" #password

  ],
```

### NOTE: To use the LoginController and enable user authentication, you need to create a users table in your database with the following columns:

```text
id (primary key)
username (unique)
password_hash
salt
```
