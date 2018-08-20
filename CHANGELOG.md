# Change Log

## [v0.3.0](https://github.com/ndege/shortr-slim/tree/v0.3.0)

[Full Changelog](ttps://github.com/ndege/shortr-slim/compare/v0.2.0...v0.3.0)

**Implemented enhancements:**

* Add authorization via JWT token and set up middleware `slim-jwt-auth`
* Authorization with POST request via `/auth` with username and password
* Add `tokens` and `users` table
* Add `\tests` for phpunit tests
* Add code-sniffer for sticking PSR2 standard

**Minor enhancements**

* Add JSON_PRETTY_PRINT to response messages

## [v0.2.0](https://github.com/ndege/shortr-slim/tree/v0.2.0)

[Full Changelog](ttps://github.com/ndege/shortr-slim/compare/v0.1.0...v0.2.0)

**Implemented enhancements:**

* Using [Phinx](https://phinx.org) as database migration tool.
* Set up migration script for `shortr` table.
* Remove sql script at `install\db\database.sql`

## [v0.1.0](https://github.com/ndege/shortr-slim/tree/v0.1.0)

**Implemented enhancements:**

* Create ShortrSlim app in [PHPSlim v3](https://www.slimframework.com/) environment.
* Default configured redirect if only baseUrl is set.
* Redirect to site if slug is given. If false slug given default redirect.
* Create new short url as far new slug is found.
* By same url return already existing slug
* Implement some validation check. Limited interval to create new short url per ip. Check if url scheme is valid.
* Add licence  headers, README, and changelog.

**Fixed bugs:**