# Laravel URL Shortener - Flashy Home Assignment

A simple URL Shortener service built with Laravel 10 and PHP 8.2+, supporting:

-   Create short URLs
-   Redirect by slug
-   Basic analytics (total hits + last 5 hits)
-   Async hit logging with queues
-   Cache for fast stats response
-   Full test coverage (Feature + Unit)

# Features

### Create shortened links

POST /api/links with optional custom slug.

### Redirect to target URL

GET /r/{slug} - 302 redirect.

### Hit tracking

Every redirect logs a hit (IP + User Agent) asynchronously via queue.

### Statistics endpoint

GET /api/links/{slug}/stats returns:

-   total_hits
-   last 5 hits
-   target_url
    with 60-second caching.

### Cache invalidation

Every time a new hit is logged, event triggers listener, stats cache is cleared.

### API Key protection

All API routes require:
X-Api-Key: secret123

### Tech Stack

-   PHP 8.2+
-   Laravel 10
-   SQLite
-   Laravel Queue (database driver)
-   Laravel Events & Listeners
-   Laravel Cache
-   PHPUnit

# TABLE OF CONTENTS

-   [Installation](#Installation)
-   [.env configuration](#.env-configuration)
-   [Running the queue worker](#.Running-the-queue-worker)
-   [Running the server](#Running-the-server)
-   [API Documentation](#API-Documentation)
-   [Architecture Explanation](#Architecture-Explanation)
-   [Running Tests](#RunningTests)
-   [Time Spent](#Time-Spent)
-   [Video Demo](<#Video-Demo-(Instructions)>)

# Installation

**Clone the repository:**
`git clone https://github.com/stavgolan/Flashy_home_assignment.git`

**Install dependencies:**
`composer install`

**Create SQLite database:**
`touch database/database.sqlite`

**Copy .env example:**
`cp .env.example .env`

**Generate app key:**
`php artisan key:generate`

**Run migrations:**
`php artisan migrate`

# .env configuration

**Make sure the following values exist in .env:**

API_KEY=secret123  
QUEUE_CONNECTION=database  
DB_CONNECTION=sqlite  
DB_DATABASE=./database/database.sqlite

# Running the queue worker

`php artisan queue:work`

# Running the server

`php artisan serve`

# API Documentation

1. **Create link: POST /api/links**  
   Headers:  
   `X-Api-Key: secret123`  
   `Content-Type: application/json`

    Body:
    <pre>{
    "target_url": "https://google.com",
    "slug": "optional-custom-slug"
    }</pre>

    Curl:  
     `curl -X POST http://localhost:8000/api/links \`  
     `-H "X-Api-Key: secret123" \`  
     `-d "target_url=https://google.com"`

    Response:
    <pre>{
    "slug": "Ab12Cd",
    "target_url": "https://google.com",
    "is_active": true
    }</pre>

2. **Redirect: GET /r/{slug}**  
   Example:  
   http://localhost:8000/r/Ab12Cd

    Behavior:  
     302 redirect  
     Logs hit asynchronously  
     Returns 404 if slug missing  
     Returns 410 if link is inactive

3. **Stats endpoint: GET /api/links/{slug}/stats**  
   Example:  
   `curl -X GET http://localhost:8000/api/links/Ab12Cd/stats \`  
   `-H "X-Api-Key: secret123"`

    Response:
      <pre>{
      "slug": "Ab12Cd",
      "target_url": "https://google.com",
      "total_hits": 3,
      "last_hits": [
      { "ip": "127.0.0.\*", "created_at": "2025-12-09T10:00:00" },
      { "ip": "127.0.0.\*", "created_at": "2025-12-09T10:00:01" }
      ]
      }</pre>

# Architecture Explanation

**Why Queue for Hit Logging?**

-   Redirects must be instant.
-   Writing to DB during redirect slows down response.
-   Queue allows the redirect to return immediately.
-   Hit logging is processed in the background - scalable solution.

**Why Cache for Stats?**

-   Stats require counting many hits.
-   Without caching, heavy DB load on high-traffic links.
-   Cache ensures fast response (<1ms).
-   Cache invalidates automatically after each new hit via Event + Listener.

**Why Events & Listeners?**

-   Decouples hit saving from cache management.
-   Cleaner architecture:
-   Job - saves hit
-   Event - emitted
-   Listener - clears cache

# Running Tests

**Run all tests:**
`php artisan test`

**Tests include:**

-   Feature tests for:
-   Creating links
-   Redirect behavior
-   Hit queue dispatch
-   Stats endpoint
-   Unit test for SlugGenerator:
-   Length
-   Allowed chars
-   Collision handling
-   DB uniqueness

# Time Spent

Total estimated time: ~7–8 hours  
(including coding, debugging, queue setup, cache, events, and test coverage)

# Video Demo (Instructions)
