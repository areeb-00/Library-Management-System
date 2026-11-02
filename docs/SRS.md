# Software Requirements Specification

## Purpose
A browser-based single-library system for cataloguing books, managing members, issuing/returning copies, calculating overdue fines and reviewing activity.

## Actors
- **Administrator:** manages users, books, categories and library policy; views reports.
- **Librarian:** manages operational issue/return tasks, categories and fine payment records.
- **Student:** searches the catalogue and views their own loans.

## Functional requirements
- FR-01 authenticate active users and maintain sessions.
- FR-02 enforce role-based access server-side.
- FR-03 create and archive book records; search title, author, ISBN and category.
- FR-04 create categories.
- FR-05 create student, librarian and administrator accounts.
- FR-06 issue a book only to an active student if a copy and loan allowance are available.
- FR-07 assign a configurable due date and retain transaction history.
- FR-08 process returns and restore inventory atomically.
- FR-09 calculate overdue fines using a configurable daily rate.
- FR-10 record fine payment state.
- FR-11 provide role-appropriate dashboards and operational reports.
- FR-12 allow administrators to configure loan limits, duration and fine rate.

## Non-functional requirements
- NFR-01 PHP 8.2+, MySQL 8+, modern browser.
- NFR-02 password hashes, prepared statements, CSRF checks and escaped output.
- NFR-03 InnoDB transactions and foreign keys protect data integrity.
- NFR-04 modular, readable code and documented setup.
- NFR-05 responsive layout for desktop and mobile.

## Business rules
Default policies: maximum 3 concurrent books, 14-day loan, ₹2 per overdue day. The due date itself is not charged: overdue days begin the day after the due date. These values can be changed by an administrator. Active loans cannot be deleted; book records are archived instead.

## Scope exclusions
Payment gateway, RFID/barcode hardware, mobile app, multi-branch synchronization and external ERP integration are not included in this initial build.
