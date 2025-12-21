# System Testing

## 1. Test Plan

### Testing Strategies
At the system planning stage, the first testing strategy that will be used is Unit Testing which is mainly for testing out the individual function of a main module. For example, the Stock Module should ensure that the quantity entered for a new batch can only be a positive integer; if a negative value is entered, an error message should be prompted to the Staff. Other than that, the testing strategies also will be using Integration Testing which is to check whether the system’s modules are able to communicate with each other. For example, checking whether when a Sale is processed, the Stock Module is able to receive the transaction details and automatically deduct the sold quantity from the correct batch using the FIFO logic. Lastly, we also will be using User Acceptance Test (UAT) which is to check whether the system is met and accepted by the user. For example, whether after a staff member tries the system and tries out functions like adding a new product, updating stock levels, and generating a Monthly Sales Report, the interface is easy to understand and the workflow is smooth.

### System Requirements

**Hardware Requirements**
*   Computers for staff to manage daily inventory tasks.
*   Local server to store and handle the database.
*   QR Code scanners to improve inventory tracking accuracy.
*   Network router or LAN setup to ensure stable communication between connected devices and enable real-time data updates.

**Software Requirements**
*   **Backend:** Laravel Framework (PHP-based) to manage application logic.
*   **Frontend:** jQuery, HTML, and CSS.
*   **Database:** MySQL management system.
*   **Local Hosting:** XAMPP to host and test the system locally.
*   **Development Environment:** Visual Studio Code.
*   **Web Browser:** Google Chrome for testing and executing the application.

---

## 2. Test Cases

### Test Case 1: User Login
**Functional Requirement:** 1. User Login & Access Control

| Test Case #: TC_001 | Test Case Name: Admin User Login |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Authentication Module |
| **Design By:** Developer | **Design Date:** 01/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify that a registered admin can successfully log in and access the dashboard. | |

**Pre-conditions:**
*   Database is seeded with a valid admin user (username: `admin`, password: `password`).
*   System is running on localhost.

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Navigate to Login Page | URL: `/login` | Login form is displayed. | Login form displayed. | **Pass** | |
| 2 | Enter Valid Credentials | User: `admin`<br>Pass: `password` | System redirects to Dashboard. | Redirected to Dashboard. | **Pass** | |
| 3 | Verify Access Rights | N/A | "Admin Dashboard" header is visible. | Header is visible. | **Pass** | |
| 4 | Attempt Invalid Login | User: `wrong`<br>Pass: `wrong` | Error message "Invalid credentials" displayed. | Error message shown. | **Pass** | |

**Post-conditions:**
*   User session is authenticated and stored.

<br>

### Test Case 2: Batch Management (Add & Edit)
**Functional Requirement:** 4. Inventory Management

| Test Case #: TC_002 | Test Case Name: Add and Edit Product Batch |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Stock Module |
| **Design By:** Developer | **Design Date:** 05/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify staff can add a new stock batch and edit its quantity. | |

**Pre-conditions:**
*   User is logged in as Staff/Admin.
*   At least one Product exists (e.g., "Coca Cola").

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Click "Add Stock" | Product: "Coca Cola"<br>Qty: 50 | New batch created. QR Code generated. | Batch #B001 created. | **Pass** | |
| 2 | Click "View Batches" | N/A | List of batches for product displayed. | List displayed. | **Pass** | |
| 3 | Click "Edit" on Batch | Batch #B001 | Edit form opens with current data. | Form opened. | **Pass** | |
| 4 | Update Quantity (Valid) | Qty: 60 | System saves and updates list to 60. | Updated to 60. | **Pass** | |
| 5 | Update Quantity (Invalid) | Qty: -10 | System prevents save & shows error. | Alert: "Quantity must be > 0". | **Pass** | Fixed in Bug #2 |

**Post-conditions:**
*   Inventory count reflects the new values. QR code file exists in storage.

<br>

### Test Case 3: FIFO Sales Deduction
**Functional Requirement:** 7. Sales Record

| Test Case #: TC_003 | Test Case Name: FIFO Stock Deduction |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Sales Module |
| **Design By:** Developer | **Design Date:** 10/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify that sales deduct stock from the oldest batch first (FIFO). | |

**Pre-conditions:**
*   Product "Milk" has: Batch A (Exp: 2025-01-01, Qty: 10), Batch B (Exp: 2025-02-01, Qty: 10).

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Add to Cart | Item: "Milk"<br>Qty: 15 | Item added to transaction list. | Added to list. | **Pass** | |
| 2 | Complete Checkout | Payment: Cash | Transaction saved. Stock deducted. | Transaction Success. | **Pass** | |
| 3 | Verify Batch A (Oldest) | N/A | Quantity should be 0. | Qty is 0. | **Pass** | Fully used. |
| 4 | Verify Batch B (Newest) | N/A | Quantity should be 5 (15-10). | Qty is 5. | **Pass** | |

**Post-conditions:**
*   Sales record created. Batch A marked as empty (QR potentially removed).

---

## 3. Test Results and Debugging Records

**Summary:**
Testing confirmed that the core modules (Login, Stock, Sales) are functioning according to requirements. The FIFO logic correctly prioritizes older stock. 

Two distinct bugs were identified during development. Below is the record of their diagnosis and resolution.

### Bug Record #1
| Field | Details |
| :--- | :--- |
| **Bug ID** | BUG-001 |
| **Module** | Stock Management |
| **The Issue** | Clicking "Edit" or "Delete" on the stock product list caused a system crash: `Call to undefined method StockController::edit()`. |
| **Root Cause** | The Stock Management page incorrectly included a product "Edit" button that attempted to call a non-existent `edit` method in `StockController`. Code has been refactored to remove this button as product editing is handled in the Products module. |
| **The Fix** | Removed the unnecessary "Edit" button from the Stock Management interface. |
| **Status** | **Resolved** |

### Bug Record #2
| Field | Details |
| :--- | :--- |
| **Bug ID** | BUG-002 |
| **Module** | Inventory / Stock |
| **The Issue** | The "Save Changes" button on the **Edit Batch** page was unresponsive. Users could not update stock levels. Browser console showed `TypeError: Cannot read properties of null`. |
| **Root Cause** | **DOM ID Mismatch**: The custom `x-input` component overwrites custom IDs with the input name. The JavaScript looked for `#edit_quantity`, but the element was restricted to `#quantity`. |
| **The Fix** | Updated the JavaScript in `edit-batch.blade.php` to target the correct element IDs (`#quantity` and `#expiry_date`) and implemented robust error handling for validation. |
| **Status** | **Resolved** |

### Bug Record #3
| Field | Details |
| :--- | :--- |
| **Bug ID** | BUG-003 |
| **Module** | General UI / Pagination |
| **The Issue** | Pagination links on list pages (Products, Sales, Stock) initially appeared broken (oversized SVGs). After enabling Bootstrap pagination, the layout was functional but lacked styling, appearing as a plain list that broke the application theme. |
| **Root Cause** | **Missing CSS Integration**: Enabling Bootstrap pagination views fixed the HTML structure (`.page-item`, `.page-link`), but the application's custom CSS file (`styles.css`) lacked the corresponding classes to style these Bootstrap elements, resulting in a raw, unstyled look. |
| **The Fix** | Added custom CSS rules in `styles.css` targeting `.pagination`, `.page-item`, and `.page-link`. These styles applied the application's color tokens (`--color-primary`) and border radii, ensuring the pagination matches the overall design language. |
| **Status** | **Resolved** |

### Bug Record #4
| Field | Details |
| :--- | :--- |
| **Bug ID** | BUG-004 |
| **Module** | Products & Categories |
| **The Issue** | Users were able to create multiple products or categories with the exact same name (e.g., creating "Electronics" category twice), leading to data ambiguity and confusion in dropdown selections. |
| **Root Cause** | **Missing Validation Constraints**: The backend validation logic only checked for required fields and data types but missed the `unique` constraint check against the database table. |
| **The Fix** | Updated `ProductController`, `StoreCategoryRequest`, and `UpdateCategoryRequest` to include the `unique` validation rule (e.g., `unique:products,product_name`). Also handled the edge case for updates by forcing the validation to ignore the current item's ID upon editing. |
| **Status** | **Resolved** |
### Bug Record #5
| Field | Details |
| :--- | :--- |
| **Bug ID** | BUG-005 |
| **Module** | User Management |
| **The Issue** | The "User Groups" filter on the User Management page did not include newly created groups if no users were currently assigned to them. Users could only filter by roles that were already "in use". |
| **Root Cause** | **Incorrect Data Source**: The filter dropdown was populated using a distinct query of the `roles` column from the `users` table (`User::distinct()->pluck('roles')`), effectively showing only "active" roles instead of all available groups. |
| **The Fix** | Updated the `users.index` view to iterate over the `$groups` collection (fetched from the `groups` table) instead of the `$roles` array. This ensures the filter dropdown always lists all defined user groups, regardless of whether users are assigned to them. |
| **Status** | **Resolved** |
