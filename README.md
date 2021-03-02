# Description:
This test task was done to demonstrate a typical REST API web application.
## Requirements
- Lumen v8.29.0 ( [Lumen documentation](https://lumen.laravel.com/docs) )
- PHP 7.4
- PostgresSQL 11.1.x
# Task:
Create the RESTFull API to share the companies information for the logged users. Use Stack: Lumen, PostgreSQL.
## Details:
Create DB migrations for the tables: users, companies, etc.
Suggest the DB structure. Fill the DB with the test data.
## Endpoints:
- https://domain.com/api/user/register
    - **method** POST
    - **fields:** first_name [string], last_name [string], email [string], password [string], phone [string]
- https://domain.com/api/user/sign-in
    - **method** POST
    - **fields:** email [string], password [string]
- https://domain.com/api/user/recover-password
    - **method** POST/PATCH
    - **fields:** email [string] // allow to update the password via email token
- https://domain.com/api/user/companies
    - **method** GET
    - **fields:** title [string], phone [string], description [string]
    - **Details:** show the companies, associated with the user (by the relation)
- https://domain.com/api/user/companies
    - **method** POST
    - **fields:** title [string], phone [string], description [string]\
    - **Details:** add the companies, associated with the user (by the relation)

# Deploy information
1. Clone thr project from Git repository `git clone `
2. Rename root file `.env.example` to `.env`
3. Add to `.env` file information about DataBase connection
4. Run `composer install`
5. Upload test data to DB from root file `dump.sql` 
   **OR run** migration `php artisan migrate` and tinker `php 
   artisan tinker` then for generate 
   test Users and Companies `Company::factory()->count(5)->create()`
6. Submit requests according to this documentation

# Usage

## .../api/user/register
- **Request Header:**
    ```sh
      POST /api/user/register HTTP/1.1
      Host: localhost:8030
      Content-Type: application/json
      Content-Length: 278
    ```
- **Request body Example:**
  ```sh
    {
        "data": {
            "type": "user",
            "attributes": {
                "first_name": "Admin",
                "last_name": "Admin",
                "email": "admin@admin.com",
                "password": "admin.com",
                "phone": "+38 044 100 0001"
            }
        }
    }
  ```
- **Response this request:**
    ```sh
      {
            "data": {
                "id": 39,
                "type": "user",
                "attributes": {
                    "first_name": "Admin",
                    "last_name": "Admin",
                    "email": "admin@admin.com",
                    "password": "admin.com",
                    "phone": "+38 044 100 0001"
                }
            }
        }
    ```
## .../api/user/sign-in
- **Request Header:**
  ```sh
    POST /api/user/sign-in HTTP/1.1
    Host: localhost:8030
    Content-Type: application/json
    Content-Length: 187
  ```
- **Request body Example:**
  ```sh
    {
        "data": {
            "type": "user",
            "attributes": {            
                "email":"admin@admin.com",
                "password":"admin.com"            
            }
        }
    }
  ```
- **Response this request:**
  ```sh
    {
        "data": {
            "id": 39,
            "type": "user",
            "attributes": {
                "email": "admin@admin.com",
                "apikey": "bWJZVVdNYndBMzJjNFNTVEd1N1JIQ1BDa2VqUEZKeTlaUFZQV0tWcg=="
            }
        }
    }
  ```
## .../api/user/recover-password
- **Request Header:**
  ```sh
    POST /api/user/recover-password HTTP/1.1
    Host: localhost:8030
    Content-Type: application/json
    Content-Length: 146
  ```
- **Request body Example:**
  ```sh
    {
        "data": {
            "type": "user",
            "attributes": {            
                "email":"admin@admin.com"        
            }
        }
    }
  ```
- **Response this request:**
  ```sh
    {
        "meta": {
            "message": [
                "We send token to your Email: admin@admin.com"
            ]
        },
        "data": {
            "type": "user",
            "id": 39,
            "attributes": {
                "email": "admin@admin.com",
                "token": "cdQUpyo7hO1XYpyojUntGG19pZyjausq7HdLRHHr6u79WBieOzFMBHCttUgcPT"
            }
        }
    }
  ```
## .../api/user/companies
- **Request Header:**
  ```sh
    GET /api/user/companies HTTP/1.1
    Host: localhost:8030
    X-API-Key: bWJZVVdNYndBMzJjNFNTVEd1N1JIQ1BDa2VqUEZKeTlaUFZQV0tWcg==
  ```
- **Response this request:**
  ```sh
    {
        "data": {
            "type": "user",
            "id": 39,
            "relationships": {
                "companies": [
                    {
                        "id": 44,
                        "title": "test title21",
                        "phone": "test phone2",
                        "description": "test description2",
                        "type": "company"
                    }
                ]
            }
        }
    }
  ```
## .../api/user/companies
- **Request Header:**
  ```sh
    POST /api/user/companies HTTP/1.1
    Host: localhost:8030
    X-API-Key: bWJZVVdNYndBMzJjNFNTVEd1N1JIQ1BDa2VqUEZKeTlaUFZQV0tWcg==
    Content-Type: application/json
    Content-Length: 370
  ```
- **Request body Example:**
  ```sh
    {
        "data":{    
            "type": "user",
            "relationships":{        
                "companies":{
                    "data":{
                        "type":"company",
                        "title":"test title21",
                        "phone":"test phone2",
                        "description":"test description2"
                    }
                }
            }
        }
    }
  ```
- **Response this request:**
  ```sh
    {
        "meta": {
            "message": "You add company: test title21"
        },
        "data": {
            "id": 39,
            "type": "user",
            "relationships": {
                "companies": {
                    "data": {
                        "title": "test title21",
                        "phone": "test phone2",
                        "description": "test description2",
                        "id": 44,
                        "type": "company"
                    }
                }
            }
        }
    }
  ```
