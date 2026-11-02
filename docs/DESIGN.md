# System Design and Workflows

## 1. System architecture

```mermaid
flowchart TD
  A[Administrator / Librarian / Student] --> B[Web Browser]
  B --> C[Presentation Layer: HTML, CSS, JavaScript, PHP Views]
  C <--> D[Application Layer: Front Controller, Auth, Services]
  D <--> E[(MySQL InnoDB Database)]
```

## 2. Authentication and navigation flow

```mermaid
flowchart TD
  S([Start]) --> L[Submit username and password]
  L --> V{Credentials valid and active?}
  V -- No --> X[Show generic sign-in error]
  X --> L
  V -- Yes --> R[Regenerate session ID and load role]
  R --> P[Show role-appropriate dashboard]
  P --> O[User signs out]
  O --> E([End])
```

## 3. Use-case matrix

| Use case | Administrator | Librarian | Student |
|---|---:|---:|---:|
| Sign in / sign out | Yes | Yes | Yes |
| Search catalogue | Yes | Yes | Yes |
| Add / edit books | Yes | Yes | No |
| Archive books | Yes | No | No |
| Manage categories | Yes | Yes | No |
| Create user accounts | Yes | No | No |
| Issue / return books | Yes | Yes | No |
| View own loan history | No | No | Yes |
| Manage fine payment state | Yes | Yes | No |
| View reports | Yes | Yes | No |
| Configure library policy | Yes | No | No |

## 4. Issue workflow

```mermaid
flowchart TD
  A[Staff selects student and book] --> B{Student active?}
  B -- No --> X[Reject issue]
  B -- Yes --> C{Loan limit available?}
  C -- No --> X
  C -- Yes --> D{Available copy exists?}
  D -- No --> X
  D -- Yes --> E[Begin database transaction]
  E --> F[Create issue transaction and due date]
  F --> G[Decrement available copies]
  G --> H[Commit transaction]
  H --> I[Show confirmation]
```

## 5. Return and fine workflow

```mermaid
flowchart TD
  A[Staff selects active loan] --> B[Lock transaction row]
  B --> C[Calculate days after due date]
  C --> D[Fine = overdue days x daily rate]
  D --> E[Mark loan returned]
  E --> F[Restore one available copy]
  F --> G{Fine greater than zero?}
  G -- Yes --> H[Create unpaid fine record]
  G -- No --> I[Commit transaction]
  H --> I
  I --> J[Show return result]
```

## 6. Data flow diagram — context / level 0

```mermaid
flowchart LR
  U[Admin, Librarian, Student] -->|Credentials, searches, forms| LMS((Library Management System))
  LMS -->|Dashboards, results, confirmations| U
  LMS <--> DB[(Library Database)]
```

## 7. Core business rules

- Only active students may borrow books.
- Only staff roles may issue or return books.
- A student may hold no more than the configured number of active loans.
- A book must have at least one available copy before issue.
- Issue and return operations update the loan and inventory in one transaction.
- Fine days begin the day after the due date; due date itself is not charged.
- Historical transactions are retained; books are archived rather than hard-deleted.
