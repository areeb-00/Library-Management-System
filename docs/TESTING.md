# Testing plan

## Automated smoke checks

Run `php tests/smoke_test.php`. These checks cover output escaping, money formatting, token generation and date arithmetic.

## Manual integration test matrix

| ID | Scenario | Expected result |
|---|---|---|
| TC-01 | Sign in with each seeded account | Correct role dashboard appears |
| TC-02 | Wrong password | Generic authentication error; no session |
| TC-03 | Student opens admin URL/action | HTTP 403 / action rejected |
| TC-04 | Admin adds a book | Book appears in catalogue |
| TC-05 | Librarian issues an available book | Loan created; available count decreases by one |
| TC-06 | Issue unavailable book | Rejected; no inventory change |
| TC-07 | Return active loan | Loan marked returned; available count increases by one |
| TC-08 | Return after due date | Fine = overdue days × configured daily rate |
| TC-09 | Attempt to exceed borrowing limit | Issue rejected |
| TC-10 | SQL injection string in search | Treated as data through bound parameters |
| TC-11 | Missing/invalid CSRF token | Request rejected with 419 |
| TC-12 | Archive book with active loan | Rejected |

The automated smoke test does not replace running the integration tests against a configured MySQL server. Record actual outcomes before release.
