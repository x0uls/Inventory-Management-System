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

### Test Case 1: User Login & Access Control
**Functional Requirement:** 1. User Login & Access Control

| Test Case #: TC_001 | Test Case Name: User Authentication & RBAC |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Authentication Module |
| **Design By:** Developer | **Design Date:** 01/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify login validation, invalid credential handling, and role-based access redirection. | |

**Pre-conditions:**
*   Database seeded with Admin (`admin`/`password`) and Staff (`staff`/`password`) users.
*   System running on localhost.

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Navigate to Login Page | URL: `/login` | Login form displayed. | Form displayed. | **Pass** | |
| 2 | Enter Invalid Credentials | User: `wrong` | Error: "Invalid login credentials". | Error shown. | **Pass** | |
| 3 | Enter Valid Admin Credentials | User: `admin` | Redirect to Dashboard. Admin controls visible. | Redirected. Admin controls visible. | **Pass** | |
| 4 | Log out and Login as Staff | User: `staff` | Redirect to Dashboard. Restricted controls (View Only). | Redirected. "View Only" displayed on protected items. | **Pass** | **Resolved in Bug #7** (RBAC) |

**Post-conditions:**
*   User session stored securely.

<br>

### Test Case 2: User & User Group Management
**Functional Requirement:** 2. User & User Group Management

| Test Case #: TC_002 | Test Case Name: User CRUD & Group Validation |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** User Management |
| **Design By:** Developer | **Design Date:** 02/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify creating users, updating groups, and validation logic. | |

**Pre-conditions:**
*   Logged in as Admin.

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Create New Group | Name: "Managers" | Group saved. | Saved successfully. | **Pass** | |
| 2 | Filter Users by Group | Filter: "Managers" | List updates to show linked users. | List updated correctly. | **Pass** | **Resolved in Bug #5** |
| 3 | Edit User Details | Name: "New Name" | User record updated. | Updated. | **Pass** | **Resolved in Bug #6** |
| 4 | Change User Password | Pass: `newpass` | Password updated. | Password updated. | **Pass** | **Resolved in Bug #8** |

**Post-conditions:**
*   User and Group records updated in database.

<br>

### Test Case 3: Product Management
**Functional Requirement:** 3. Product Management

| Test Case #: TC_003 | Test Case Name: Product Creation & Validation |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Products Module |
| **Design By:** Developer | **Design Date:** 03/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify adding products, validating unique names, and updating details. | |

**Pre-conditions:**
*   Logged in as Admin. Categories and Suppliers exist.

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Add New Product | Name: "Apple" | Product saved. Details displayed. | Saved. | **Pass** | |
| 2 | Add Duplicate Product | Name: "Apple" | Error: "The product name has already been taken." | Error shown. | **Pass** | **Resolved in Bug #4** |
| 3 | Update Product | Price: 15.00 | Record updated. | Updated. | **Pass** | |
| 4 | Delete Product | N/A | Product removed. | Removed. | **Pass** | |

**Post-conditions:**
*   Product database table updated.

<br>

### Test Case 4: Inventory Management
**Functional Requirement:** 4. Inventory Management

| Test Case #: TC_004 | Test Case Name: ID Recycling & Batch/QR Tracking |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Stock Module |
| **Design By:** Developer | **Design Date:** 05/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify stock addition, ID recycling, and QR code generation. | |

**Pre-conditions:**
*   Logged in as Admin. Product "Apple" exists.

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Add Stock for Product | Qty: 50 | New batch created. QR Code generated. | Batch created. QR generated. | **Pass** | |
| 2 | Edit Batch Quantity | Qty: -5 | Error: "Quantity must be > 0". | Error shown. | **Pass** | **Resolved in Bug #2** |
| 3 | Delete Batch | N/A | Batch deleted. ID becomes available. | Deleted. | **Pass** | |
| 4 | Add New Batch (Reuse ID) | N/A | System reuses the deleted Batch ID (Gap filling). | ID reused (Recycling logic). | **Pass** | Feature ID Recycling |

**Post-conditions:**
*   Stock levels updated. QR code files managed.

<br>

### Test Case 5: Supplier Management
**Functional Requirement:** 5. Supplier Management

| Test Case #: TC_005 | Test Case Name: Supplier CRUD Operations |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Suppliers Module |
| **Design By:** Developer | **Design Date:** 06/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify adding, updating, and viewing suppliers. | |

**Pre-conditions:**
*   Logged in as Admin.

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Add New Supplier | Name: "Fresh Farms" | Supplier saved in database. | Saved. | **Pass** | |
| 2 | View Supplier List | N/A | List displays "Fresh Farms". | Displayed. | **Pass** | |
| 3 | Edit Contact Info | Phone: "555-0123" | Record updated. | Updated. | **Pass** | |
| 4 | Delete Supplier | N/A | Record removed. | Removed. | **Pass** | |

**Post-conditions:**
*   Supplier records updated.

<br>

### Test Case 6: Category Management
**Functional Requirement:** 6. Category Management

| Test Case #: TC_006 | Test Case Name: Category Classification & Safeguards |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Category Module |
| **Design By:** Developer | **Design Date:** 06/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify category creation and deletion integrity constraints. | |

**Pre-conditions:**
*   Logged in as Admin. Product "Apple" assigned to "Fruit" category.

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Create Category | Name: "Snacks" | Category saved. | Saved. | **Pass** | |
| 2 | Create Duplicate | Name: "Snacks" | Error: Name must be unique. | Error shown. | **Pass** | **Resolved in Bug #4** (Same logic as Products) |
| 3 | Delete "Fruit" Category | N/A | Error: "Cannot delete category associated with existing products." | Error shown. | **Pass** | Integrity Check |
| 4 | Delete "Snacks" (Empty) | N/A | Category deleted. | Deleted. | **Pass** | |

**Post-conditions:**
*   Category list maintained with integrity.

<br>

### Test Case 7: Sales Record
**Functional Requirement:** 7. Sales Record

| Test Case #: TC_007 | Test Case Name: Sales Processing & FIFO Logic |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Sales Module |
| **Design By:** Developer | **Design Date:** 10/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify sales transaction recording and FIFO stock deduction. | |

**Pre-conditions:**
*   Product "Milk" has Batch A (Qty: 10, Old) and Batch B (Qty: 10, New).

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Process Sale | Item: "Milk", Qty: 15 | Transaction recorded. Stock deducted. | Success. | **Pass** | |
| 2 | Verify Batch A | N/A | Quantity = 0 (Empty). | Qty is 0. | **Pass** | FIFO Logic |
| 3 | Verify Batch B | N/A | Quantity = 5 (15-10). | Qty is 5. | **Pass** | FIFO Logic |
| 4 | Check Output | N/A | Stock Level updated in database. | Updated. | **Pass** | |

**Post-conditions:**
*   Sales log stored. Inventory updated.

<br>

### Test Case 8: Report Generation
**Functional Requirement:** 8. Report Generation

| Test Case #: TC_008 | Test Case Name: Data Aggregation & Reporting |
| :--- | :--- |
| **System:** Inventory Management System | **Subsystem:** Reporting Module |
| **Design By:** Developer | **Design Date:** 12/12/2025 |
| **Executed By:** Tester | **Execution Date:** 19/12/2025 |
| **Short Description:** Verify generation of inventory and sales summary reports. | |

**Pre-conditions:**
*   Sales transactions and inventory data exist.

| Step | Action/Functions | Test Data | Expected System Response | Actual Response | Pass/Fail | Comments |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Generate Inventory Report | Filter: All | Shows total stock, restock history. | Report generated. | **Pass** | |
| 2 | Generate Sales Report | Date: Today | Shows total sales amount and quantity. | Report generated. | **Pass** | |
| 3 | Check Layout | N/A | Info is readable and styled correctly. | Layout correct. | **Pass** | **Resolved in Bug #3** (UI/CSS Fix) |

**Post-conditions:**
*   Reports displayed to user.

---

## 3. Test Results and Debugging Records

**Summary:**
Testing confirmed that the core modules (Login, Stock, Sales) are functioning according to requirements. The FIFO logic correctly prioritizes older stock. 

Eight distinct bugs were identified during development. Below is the record of their diagnosis and resolution.

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

### Bug Record #6
| Field | Details |
| :--- | :--- |
| **Bug ID** | BUG-006 |
| **Module** | User Management |
| **The Issue** | Editing a user resulted in a fatal SQL Error (`Column not found: Unknown column 'name:"Staff"'`). |
| **Root Cause** | **Object-to-String Conversion Error**: The validation rule used `$this->route('user')` directly, which returns a User object model. When concatenated into the validation string, it was converted to its JSON representation (e.g., `{"id":1, "name":"Staff"...}`), causing the SQL query parser to interpret JSON keys as column names. |
| **The Fix** | Updated `UpdateUserRequest.php` to explicitly access the ID property: `$this->route('user')->user_id`. This passes the integer ID (e.g., `1`) to the validation rule instead of the JSON string. |
| **Status** | **Resolved** |

### Bug Record #7
| Field | Details |
| :--- | :--- |
| **Bug ID** | BUG-007 |
| **Module** | User Access Control (RBAC) |
| **The Issue** | Staff members could view "Edit/Delete" buttons and potentially access restricted actions because the system failed to recognize their role. |
| **Root Cause** | **Case Sensitivity Mismatch**: The database stored roles with capitalization (e.g., "Staff") as they were copied from Group names, but the application code checked for strict lowercase equality (`=== 'staff'`),causing checks to fail. |
| **The Fix** | Implemented `strtolower()` normalization across all role checks in Controllers (`SupplierController`, `StockController`) and Views (`dashboard`, `suppliers`, `users`, `groups`). This ensures "Staff", "staff", and "STAFF" are all treated correctly as restricted users. |
| **Status** | **Resolved** |

### Bug Record #8
| Field | Details |
| :--- | :--- |
| **Bug ID** | BUG-008 |
| **Module** | User Management |
| **The Issue** | When attempting to update a user's password, the system returned a "password confirmation does not match" error, preventing the update. |
| **Root Cause** | **Missing Form Field**: The `UpdateUserRequest` backend validation rule includes `'confirmed'`, which expects a matching `password_confirmation` field in the POST request. However, the `users.edit` Blade view only contained the `password` input field, missing the required confirmation field. |
| **The Fix** | Added the `<input name="password_confirmation">` field to the `users/edit.blade.php` view. This ensures the confirmation value is sent to the ID, satisfying the validation rule. |
| **Status** | **Resolved** |
