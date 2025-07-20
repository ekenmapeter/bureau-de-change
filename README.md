# Bureau de Change Management System

This web application is a comprehensive management system for Bureau de Change (BDC) operations. It provides role-based access for administrators, managers, and cashiers, each with a tailored dashboard to monitor and manage daily activities efficiently.

## Core Features

-   **Role-Based Access Control:**
    -   **Admin:** Full system oversight, including user management, all transaction monitoring, and system-wide reporting.
    -   **Manager:** Team and branch oversight, with access to team members' performance and transactions.
    -   **Cashier:** Focused view for daily operations, including recording their own transactions and managing their cash balances.

-   **Dynamic Dashboards:** Each role has a unique dashboard presenting key metrics:
    -   Today's Opening Balances for all currencies.
    -   Real-time profit calculation.
    -   Summaries of total sales and purchases.
    -   Lists of recent transactions, cash deposits, and staff activities.

-   **Daily Opening Balance:**
    -   A mandatory feature requiring all staff to declare their opening cash balance for each currency at the start of the day.
    -   Prevents transactions until the opening balance is set, ensuring accountability.
    -   Full CRUD (Create, Read, Update, Delete) functionality for managing opening balances.

-   **Transaction Management:**
    -   Streamlined forms for recording currency purchase and sale transactions.
    -   All transactions are logged with user details, currency type, and timestamps.
    -   Comprehensive transaction tables on each dashboard for easy review.

-   **Cash Deposit Tracking:**
    -   Functionality for users to record cash deposits made during the day.

-   **Activity Logging:**
    -   Keeps a log of important actions performed by users for audit and review purposes.

## Technology Stack

-   **Backend:** Laravel PHP Framework
-   **Frontend:** Blade templates with Tailwind CSS
