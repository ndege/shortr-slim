# Change Log

## [v0.3.0](https://github.com/ndege/shortr-slim/tree/v0.3.0)

[Full Changelog](https://github.com/ndege/shortr-slim/compare/v0.2.1...v0.3.0)

**Security issues**

* Updates composer library of "illuminate/database" to v6.20.14 and higher
* Sets 7.3 as minimum version for PHP

**Implemented enhancements**

* Removes dependency of hash from downloading composer library at Docker image 

**Fixed bugs**

* Fixin defect Docker image for development

## [v0.2.1](https://github.com/ndege/shortr-slim/tree/v0.2.1)

[Full Changelog](https://github.com/ndege/shortr-slim/compare/v0.2.0...v0.2.1)

**Fixed bugs**

* Fixing running migration and seeding of docker development docker image
* Replaces sleeping by waiting of db container to run init of app
* Path fixing to load migration and seeding at ./phinx.php configuration file
* Actualize composer hash
* Minor wording at docs app7srxc/Helpers/HashHelper 

## [v0.2.0](https://github.com/ndege/shortr-slim/tree/v0.2.0)

[Full Changelog](https://github.com/ndege/shortr-slim/compare/v0.1.1...v0.2.0)

**Implemented enhancements**

* Dockerize ShortrSlim
* Adds production image as Dockerfile and development image Dockerfile.dev via docker-compose

**Minor enhancements**

* Adapts `.travis.yml` adding PHP 7.4

**Fixed bugs**

* Fixing false countable type of database object with count() at ShortrController::redirectAction() 

## [v0.1.1](https://github.com/ndege/shortr-slim/tree/v0.1.0)

[Full Changelog](https://github.com/ndege/shortr-slim/compare/v0.1.0...v0.1.1)

**Implemented enhancements**

* By no finding valid slug at database redirect request ot default site.

**Minor enhancements**

* Adapts `.travis.yml` removing PHP 7.0 and adding PHP 7.3

## [v0.1.0](https://github.com/ndege/shortr-slim/tree/v0.1.0)

[Full Changelog](https://github.com/ndege/shortr-slim/compare/v0.0.2...v0.1.0)

**Implemented enhancements**

* Add authorization via JWT token and set up middleware `slim-jwt-auth`
* Authorization with POST request via `/auth` with username and password
* Add `tokens` and `users` table
* Add `\tests` for phpunit tests
* Add code-sniffer for sticking PSR2 standard

**Minor enhancements**

* Add JSON_PRETTY_PRINT to response messages

## [v0.0.2](https://github.com/ndege/shortr-slim/tree/v0.0.2)

[Full Changelog](ttps://github.com/ndege/shortr-slim/compare/v0.0.1...v0.0.2)

**Implemented enhancements**

* Using [Phinx](https://phinx.org) as database migration tool.
* Set up migration script for `shortr` table.
* Remove sql script at `install\db\database.sql`

## [v0.0.1](https://github.com/ndege/shortr-slim/tree/v0.0.1)

**Implemented enhancements**

* Create ShortrSlim app in [PHPSlim v3](https://www.slimframework.com/) environment.
* Default configured redirect if only baseUrl is set.
* Redirect to site if slug is given. If false slug given default redirect.
* Create new short url as far new slug is found.
* By same url return already existing slug
* Implement some validation check. Limited interval to create new short url per ip. Check if url scheme is valid.
* Add licence  headers, README, and changelog.

**Fixed bugs**
