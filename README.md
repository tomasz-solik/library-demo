# Library Demo API

Simple REST API for managing books in a library.

## Live Demo

API:
https://library-demo-ngh3.onrender.com/api/book/

API Documentation:
https://library-demo-ngh3.onrender.com/api/doc.json

## Repository

GitHub:
https://github.com/tomasz-solik/library-demo

Clone the project:

```bash
git clone https://github.com/tomasz-solik/library-demo.git
cd library-demo
```

## Run with Docker

Build and start the application:

```bash
docker compose up --build
```

The application will be available at:

```
http://localhost:8080
```

## Check the API

```bash
curl http://localhost:8080/api/book
```

CRUD demo API for managing books in a library.

- **Version:** 1.0.0
- **API Base Path:** `/api`

---

# Endpoints

## Get all books

Returns a list of all books.

### Request

```http
GET /api/book
```

### Responses

| Status | Description |
|--------|-------------|
| `200 OK` | List of books |
| `404 Not Found` | Resource not found |

---

## Create a book

Creates a new book in the library.

### Request

```http
POST /api/book
Content-Type: application/json
```

### Body

| Field | Type | Required | Example |
|-------|------|----------|---------|
| `serialNumber` | string | ✅ | `123456` |
| `title` | string | ✅ | `Clean Code` |
| `author` | string | ✅ | `Robert C. Martin` |

### Example

```json
{
  "serialNumber": "123456",
  "title": "Clean Code",
  "author": "Robert C. Martin"
}
```

### Responses

| Status | Description |
|--------|-------------|
| `200 OK` | Book created |
| `404 Not Found` | Resource not found |

---

## Get a single book

Returns details of a specific book.

### Request

```http
GET /api/book/{id}
```

### Path Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | ✅ | Book identifier |

### Responses

| Status | Description |
|--------|-------------|
| `200 OK` | Book details |
| `404 Not Found` | Book not found |

---

## Delete a book

Deletes a book from the library.

### Request

```http
DELETE /api/book/{id}
```

### Path Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | ✅ | Book identifier |

### Responses

| Status | Description |
|--------|-------------|
| `204 No Content` | Book deleted |
| `400 Bad Request` | Invalid request |
| `404 Not Found` | Book not found |

---

## Borrow a book

Marks a book as borrowed.

### Request

```http
POST /api/book/{id}/borrow
Content-Type: application/json
```

### Path Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | ✅ | Book identifier |

### Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `borrowerCardNumber` | string | ✅ | Library card number |

### Example

```json
{
  "borrowerCardNumber": "123456"
}
```

### Responses

| Status | Description |
|--------|-------------|
| `204 No Content` | Book successfully borrowed |
| `404 Not Found` | Book not found |

---

## Return a book

Returns a borrowed book.

### Request

```http
POST /api/book/{id}/return
```

### Path Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | ✅ | Book identifier |

### Responses

| Status | Description |
|--------|-------------|
| `200 OK` | Book returned |
| `404 Not Found` | Book not found |
| `409 Conflict` | Book is not currently borrowed |

---

# Status Codes

| Code | Meaning |
|------|---------|
| `200 OK` | Request completed successfully |
| `204 No Content` | Operation completed successfully with no response body |
| `400 Bad Request` | Invalid request data |
| `404 Not Found` | Resource not found |
| `409 Conflict` | Resource state conflict |

---

# Example Workflow

### 1. Create a book

```http
POST /api/book
```

```json
{
  "serialNumber": "123456",
  "title": "Clean Code",
  "author": "Robert C. Martin"
}
```

### 2. Get the created book

```http
GET /api/book/1
```

### 3. Borrow the book

```http
POST /api/book/1/borrow
```

```json
{
  "borrowerCardNumber": "123456"
}
```

### 4. Return the book

```http
POST /api/book/1/return
```

### 5. Delete the book

```http
DELETE /api/book/1
```
