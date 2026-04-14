# Pharmacy Management System - Technical Architecture

This application manages pharmacy inventory via a 2-tier architecture (HTML/JS Frontend -> PHP Middleware -> Oracle 11g Database). The primary focus of this documentation is the database schema mapping and Oracle implementation.

## Database Schemas & Implementation

Oracle 11g does not natively support `AUTO_INCREMENT` flags on table definitions. Instead, it relies on a discrete Sequence and a Before-Insert Trigger system to populate Primary Keys. 

### 1. `medicines` Table Schema
The core persistent data store for the inventory.
```sql
CREATE TABLE medicines (
    id NUMBER PRIMARY KEY,
    name VARCHAR2(100),
    price NUMBER(10,2),
    quantity NUMBER
);
```

#### Architecture Detail

| Column Name | Data Type       | Constraints      | Description                          |
|-------------|-----------------|------------------|--------------------------------------|
| `id`        | `NUMBER`        | `PRIMARY KEY`    | Unique identifier (Auto-Incremented via Trigger) |
| `name`      | `VARCHAR2(100)` |                  | Name of the medicine/drug            |
| `price`     | `NUMBER(10,2)`  |                  | Cost per unit (allowing decimals)    |
| `quantity`  | `NUMBER`        |                  | Current stock availability           |

#### Example Output Record View

| id | name         | price  | quantity |
|----|--------------|--------|----------|
| 1  | Paracetamol  | 5.99   | 150      |
| 2  | Ibuprofen    | 8.50   | 20       |
| 3  | Amoxicillin  | 12.00  | 5        |


### 2. Oracle Sequence Iteration
An independent numerical generator used to govern the contiguous IDs incrementing precisely from `1`.
```sql
CREATE SEQUENCE med_seq START WITH 1 INCREMENT BY 1;
```

### 3. Oracle Trigger Implementation
A `BEFORE INSERT` trigger actively intercepts new entries attempting to write to `medicines`, querying the `med_seq` and injecting the next numerical value directly into the row's `id` index.
```sql
CREATE OR REPLACE TRIGGER med_trig 
BEFORE INSERT ON medicines 
FOR EACH ROW 
BEGIN 
  SELECT med_seq.NEXTVAL INTO :new.id FROM dual; 
END;
```

*Note: Because of this trigger composition, raw insertion queries can freely omit the `id` column entirely.*


## Component Security

- **Prepared Statements Mitigation (`oci_bind_by_name`)**: The `api.php` controller strictly isolates database commands from user inputs using Oracle bind variables (e.g., `:qty`, `:id`). This permanently eliminates SQL Injection vulnerabilities because the driver processes parameters strictly as compiled data types, stripping malicious syntactical executions.
- **Encapsulated Endpoints**: The frontend pushes static payload dictionaries rather than raw functional SQL queries across the network boundaries.

## Target Environment Setup
1. Define your TMU Oracle login configuration within `api.php`:
   ```php
   $conn = @oci_connect('YOUR_USER', 'YOUR_PASS', '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(Host=oracle.scs.ryerson.ca)(Port=1521))(CONNECT_DATA=(SID=orcl)))');
   ```
2. Both `api.php` and `index.html` must be placed in a parallel directory context within your `public_html` hierarchy to prevent fetching CORS deviations.
3. Access the file publicly and click "Create Tables" to initialize the backend schemas.