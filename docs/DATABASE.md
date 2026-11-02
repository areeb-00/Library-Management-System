# Database design

See `database/schema.sql` for the executable schema and `database/seed.sql` for demo data.

- `users` stores credentials and role.
- `students` and `librarians` are one-to-one role profiles.
- `categories` classifies `books`.
- `book_transactions` records issue/return lifecycle and processing staff.
- `fines` stores one fine per loan, with paid/unpaid state.
- `system_settings` stores configurable loan duration, limit and daily fine rate.

Inventory rule: `0 <= available_copies <= total_copies`. Issuing decrements available copies; returning increments it. Both changes occur in the same transaction as the loan status update.
