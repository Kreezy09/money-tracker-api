# Money Tracker API

A simple Money Tracker REST API built with PHP Laravel. This backend-only application allows users to manage multiple wallets (accounts) and track income/expense transactions.

## Features

- Create user accounts (no authentication required)
- Create one or more wallets per user
- Add income and expense transactions to wallets
- View user profiles with all wallets, individual balances, and total balance
- View individual wallets with balance and full transaction history
- Input validation (required fields, positive amounts, valid transaction types)

## Requirements

- PHP 8.2+
- Composer
- SQLite (default) or MySQL/PostgreSQL

## Setup

```bash
# Clone the repository
git clone https://github.com/Kreezy09/money-tracker-api.git
cd money-tracker-api

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create the SQLite database file
touch database/database.sqlite

# Run migrations
php artisan migrate

# Start the development server
php artisan serve
```

The API will be available at `http://127.0.0.1:8000/api`.

## API Endpoints

### Users

#### Create a User

```
POST /api/users
```

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com"
}
```

**Response (201):**

```json
{
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2026-02-24T12:00:00.000000Z",
        "updated_at": "2026-02-24T12:00:00.000000Z"
    }
}
```

#### View User Profile

```
GET /api/users/{id}
```

**Response (200):**

```json
{
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "wallets": [
            {
                "id": 1,
                "user_id": 1,
                "name": "Personal Savings",
                "description": "My personal account",
                "balance": 3800,
                "created_at": "2026-02-24T12:00:00.000000Z",
                "updated_at": "2026-02-24T12:00:00.000000Z"
            }
        ],
        "total_balance": 3800,
        "created_at": "2026-02-24T12:00:00.000000Z",
        "updated_at": "2026-02-24T12:00:00.000000Z"
    }
}
```

---

### Wallets

#### Create a Wallet

```
POST /api/wallets
```

**Request Body:**

```json
{
    "user_id": 1,
    "name": "Business Account",
    "description": "Freelance business"
}
```

**Response (201):**

```json
{
    "data": {
        "id": 2,
        "user_id": 1,
        "name": "Business Account",
        "description": "Freelance business",
        "balance": 0,
        "created_at": "2026-02-24T12:00:00.000000Z",
        "updated_at": "2026-02-24T12:00:00.000000Z"
    }
}
```

#### View a Wallet

```
GET /api/wallets/{id}
```

**Response (200):**

```json
{
    "data": {
        "id": 1,
        "user_id": 1,
        "name": "Personal Savings",
        "description": "My personal account",
        "balance": 3800,
        "transactions": [
            {
                "id": 1,
                "wallet_id": 1,
                "type": "income",
                "amount": 5000,
                "description": "Salary",
                "created_at": "2026-02-24T12:00:00.000000Z",
                "updated_at": "2026-02-24T12:00:00.000000Z"
            },
            {
                "id": 2,
                "wallet_id": 1,
                "type": "expense",
                "amount": 1200,
                "description": "Rent",
                "created_at": "2026-02-24T12:00:00.000000Z",
                "updated_at": "2026-02-24T12:00:00.000000Z"
            }
        ],
        "created_at": "2026-02-24T12:00:00.000000Z",
        "updated_at": "2026-02-24T12:00:00.000000Z"
    }
}
```

---

### Transactions

#### Add a Transaction

```
POST /api/transactions
```

**Request Body:**

```json
{
    "wallet_id": 1,
    "type": "income",
    "amount": 5000.0,
    "description": "Salary"
}
```

**Response (201):**

```json
{
    "data": {
        "id": 1,
        "wallet_id": 1,
        "type": "income",
        "amount": 5000,
        "description": "Salary",
        "created_at": "2026-02-24T12:00:00.000000Z",
        "updated_at": "2026-02-24T12:00:00.000000Z"
    }
}
```

## Validation Rules

| Field       | Rules                                   |
| ----------- | --------------------------------------- |
| name        | Required, string, max 255 characters    |
| email       | Required, valid email, unique           |
| user_id     | Required, must exist in users table     |
| wallet_id   | Required, must exist in wallets table   |
| type        | Required, must be `income` or `expense` |
| amount      | Required, numeric, greater than 0       |
| description | Optional, string, max 255 characters    |

Validation errors return a `422` response with error details.

## Balance Calculation

- **Income** transactions add to the wallet balance
- **Expense** transactions subtract from the wallet balance
- Balances are calculated dynamically from transactions (not stored as a column)
- A user's **total balance** is the sum of all their wallet balances

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── UserController.php        # User store and show
│   │   ├── WalletController.php      # Wallet store and show
│   │   └── TransactionController.php # Transaction store
│   └── Resources/
│       ├── UserResource.php          # User JSON formatting
│       ├── WalletResource.php        # Wallet JSON formatting
│       └── TransactionResource.php   # Transaction JSON formatting
├── Models/
│   ├── User.php                      # User model with wallet relationship
│   ├── Wallet.php                    # Wallet model with balance accessor
│   └── Transaction.php              # Transaction model
database/
└── migrations/
    ├── create_users_table.php
    ├── create_wallets_table.php
    └── create_transactions_table.php
routes/
└── api.php                           # All API route definitions
```
