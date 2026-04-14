# Pharmacy Management System

A minimalist, highly functional web application built to serve as a foundational system for managing pharmacy inventory. It operates on a straightforward 2-tier architecture (HTML/JS Frontend -> PHP Middleware -> MySQL Database).

## Database Schema & Implementation

The application relies on a strictly structured schema optimized natively for a standard **MySQL** relational database environment, making use of raw PHP data bridging.

### 1. `medicines` Table Schema
The core persistent data store for the inventory. The `id` column is configured securely with `AUTO_INCREMENT` to resolve identity constraints internally, avoiding the need for discrete sequences.

```sql
CREATE TABLE medicines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2),
    quantity INT
);
```

#### Architecture Detail

| Column Name | Data Type       | Constraints      | Description                          |
|-------------|-----------------|------------------|--------------------------------------|
| `id`        | `INT`           | `PRIMARY KEY`    | Unique identifier (Auto-Incremented) |
| `name`      | `VARCHAR(100)`  |                  | Name of the medicine/drug            |
| `price`     | `DECIMAL(10,2)` |                  | Cost per unit                        |
| `quantity`  | `INT`           |                  | Current stock availability           |

#### Example Output Record View

| id | name         | price  | quantity |
|----|--------------|--------|----------|
| 1  | Paracetamol  | 5.99   | 150      |
| 2  | Ibuprofen    | 8.50   | 20       |
| 3  | Amoxicillin  | 12.00  | 5        |


## Component Configuration

- **Encapsulated Execution**: The `api.php` file accepts discrete CRUD execution strings over native `application/x-www-form-urlencoded` POST bridges and formats output directly into normalized, stylized structural HTML block outputs.
- **Frontend State**: The `index.html` UI interfaces dynamically with the backend by parsing generated component structures seamlessly into the viewport box to ensure lightweight, instantaneous data manipulation without page reloads.

## Target Environment Setup

1. **Database Configuration**: Define your backend authentication credentials dynamically within `api.php`:
   ```php
   $connect = mysqli_connect("localhost", "YOUR_USERNAME", "YOUR_PASSWORD", "YOUR_DB_NAME");
   ```
2. **Directory Proximity**: Both `api.php` and `index.html` must be placed parallel to each other inside your root web directory frame (`public_html`) to secure synchronous routing endpoints.
3. **Database Initialization**: Launch the application locally and simply click `"Create Tables"` to generate schemas, executing `"Populate Dummy Data"` to boot the initial framework dependencies.