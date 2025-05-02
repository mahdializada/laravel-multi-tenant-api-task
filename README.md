# Multi-Tenant API with Laravel

A RESTful multi-tenant API using Laravel, PostgreSQL, and Laravel Sanctum for authentication.

## Features

- 🏢 Multi-database tenancy (separate DB per tenant)
- 🔒 Sanctum token authentication
- 🚀 RESTful JSON API endpoints
- 🛡️ Middleware-protected routes

## Requirements

- PHP 8.1+
- PostgreSQL 12+
- Composer 2.0+
- Laravel 10+

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourrepo/laravel-multi-tenant.git
   cd laravel-multi-tenant



2. User Registration:

    POST /api/v1/tenant-user-register

Headers:

    X-Tenant: [tenant_id]

    Content-Type: application/json

Request:

```json
    {
        "name": "John Doe",
        "email": "john@acme.com",
        "password": "UserPass123",
        "password_confirmation": "UserPass123"
    }
```
3. User Login:

    POST /api/v1/tenant-user-login

Headers:
    X-Tenant: [tenant_id]
Request:

```json
{
  "email": "john@acme.com",
  "password": "UserPass123"
}
```
Response:

```json
{
  "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz",
  "user": {
    "id": 1,
    "name": "John Doe"
  }
}
```
4. Get Authenticated User
    GET /api/v1/account

Headers:

    Authorization: Bearer [token]

    X-Tenant: [tenant_id]

