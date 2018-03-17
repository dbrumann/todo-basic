Basic todo-app
==============

[![Build Status](https://travis-ci.org/dbrumann/todo-basic.svg?branch=master)](https://travis-ci.org/dbrumann/todo-basic)

Basic todo app with minimal templating.

Installation (development)
--------------------------

1. Clone repository:

    ```
    git clone git@github.com:dbrumann/todo-basic.git todo
    ```

2. Install dependencies

    ```
    composer install
    ```

3. Configure database (e.g. using SQLite)

    - Set DSN for Doctrine:
        
        ```
        # edit .env-file in your project directory:

        DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db
        ```
    
    - Create/Update schema

        ```
        bin/console doctrine:schema:update --force
        ```

4. Build assets

    - Install frontend dependencies

        ```
        yarn install
        ```

    - Build assets

        ```
        yarn run encore dev
        ```

5. Run development server

    ```
    php -S localhost:8000 -t public/
    ```

Run tests
---------

```
bin/phpunit
```

Running the tests will require sqlite3 being installed on your system and the 
corresponding PHP-extension to be enabled.
