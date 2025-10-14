# Chaos HTTP

**chaoswd/http** is a lightweight HTTP client wrapper for PHP that simplifies making GET and POST requests using native cURL.  
It provides simple methods for sending requests and returning structured responses as a string, objects or arrays.

---

## 🚀 Installation

Install via Composer:

```bash
composer require chaoswd/http
```

---

## 🧩 Basic Usage

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Chaos\Http\Http;

$http = new Http();

// Basic GET request
$response = $http->get('https://jsonplaceholder.typicode.com/todos/1');

// $response is now a response object - use `json()`, `object()`, or `array()` for which response type you want.
var_dump($response->json());
```

---

## ⚙️ Features

- Simplified wrapper around PHP’s native cURL
- Supports GET, POST methods
- Handles headers and query parameters
- Configurable timeout

Example POST request:

```php
$response = $http->post('https://example.com/api/posts', [
    'title' => 'Chaos Framework',
    'body'  => 'Lightweight HTTP client test',
]);
```

---

## 📚 Methods

Method                                    | Description           |
| --------------------------------------- | --------------------- |
| `get(string $url, array $params = [])`  | Send a GET request    |
| `post(string $url, array $data = [])`   | Send a POST request   |
| `withHeaders(array $headers)`           | Set request headers   |
| `withTimeout(int $seconds)`             | Set request timeout   |
| `headers()`                             | Return headers        |
| `json()`                                | Return a JSON string  |
| `object()`                              | Parse JSON as objects |
| `array()`                               | Parse JSON as arrays  |

---

## 🧾 Example: Custom Headers

```php
$http = new Chaos\Http\Http();

$http->withHeaders([
    'Authorization' => 'Bearer YOUR_TOKEN_HERE',
    'Accept' => 'application/json'
]);

$response = $http->get('https://api.example.com/user')->json();
```

---

## 🧰 Requirements

- PHP 8.2+
- cURL extension enabled

---

## 🪪 License

Released under the [MIT License](LICENSE).

---

© 2025 Chaos Web Development & Jordan Gerber
