# Architecture

The application uses a lightweight three-tier design:

1. **Presentation:** `public/index.php`, `views/layout.php`, CSS and JavaScript. The browser never connects directly to MySQL.
2. **Application:** `app/core/Auth.php`, `app/services/LibraryService.php`, request validation and role checks in the front controller.
3. **Data:** MySQL/InnoDB accessed through a single PDO factory in `app/config/database.php`.

`LibraryService::issue()` and `returnBook()` use transactions and row-level locks to keep copy counts and loan records consistent. Role authorization is enforced on the server for each protected action.

## Request lifecycle

Browser → public front controller → session/authentication → role authorization → service/database → escaped HTML response.
