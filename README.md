# ShortrSlim

ShortrSlim is an url shortener microservice based on PHPSlim v3. It is a PHP variant of my written [Shortr](https://github.com/ndege/shortr) service in Golang before.

## Features

Scope of the application is to shorten urls and redirect requests to the corresponding site.

In addition, there are several features implemented as:
* Redirect to your main website when no slug, or incorrect slug, is entered, e.g. `http://domain.tdl/` → `http://website.domain.tdl/`.
* Doesn’t create a short URLs again if there's an attempt to shorten same URL. Therefor script returns already existing short URL.
* Additionally validation and security checks as: (1) Avoid flooding. Limit creation of short urls in a defined time interval. (2) Check if url host is valid, and (3) avoiding self reference on base url.

## Install

```bash
# Download composer
curl -s https://getcomposer.org/installer | php

# Install project dependencies
php composer.phar install
```
Copy `config/settings.example.php` to `config/settings.php` and edit database and other environment variables.

Install ShortrSlim tables by using an existing database or create a new one.

ShortrSlim uses migration tool [Phinx](https://phinx.org). Go in the root of project folder and create tables.

```bash
# Create tables
vendor/bin/phinx migrate
# Fill tables with some content
vendor/bin/phinx seed:run
```

Set your server's document root to the `public/` directory.

## Docker 

It is also possible to run ShortrSlim as Dockerimage. Build and run the image via:

```bash
# Build the dockerimage with latest tag
docker build -t shortr-slim:latest .
# Run the image
docker run --rm -p "80:80" --name shortr-slim shortr-slim:latest
```

### Development

For **development** purposes use Dockerimage.dev which can be started with `docker-compose`. 

```bash
docker-compose up --build
```

Image for development will install additionally `require-dev` libraries with PHPUnit, CodeSniffer and PHPLint as well as PHP library XDebug for debugging.

By running the dev image it will set up database and populate table with some data.

## Usage

| Requests        | Variables                                 | Type   | Response  							   | Token
|-----------------|-------------------------------------------|--------|-----------------------------------------| ------
| `/{shortr_url}` |                                           | GET    | Redirect 301                            | -
| `/auth`         | {'username':{user},'password':{password}} | POST   | {'url':{shortr_url},'status':{2xx}}     | -
| `/shortr`       | {'url':{url_to_shorten}}                  | POST   | {'token':{bearer_token},'status':{2xx}} | X


Please note error response will return {'msg':{error_msg},'status':{4xx}}

##### Examples:
```bash
curl -X POST "domain.tdl/auth" -H "Content-Type: application/json" -d "{\"username\":\"test\",\"password\":\"pass\"}"
curl -X POST "domain.tdl/shortr" -H "Content-Type: application/json" -H "Authorization: bearer {token}" -d "{\"url\":\"domain_to_shorten.tdl\"}"
```

## Tests

Run tests for shortr-slim by.

```bash
composer test
```

## Notes

* Please note to use https if JWT middleware is enabled or define exceptions at `relaxed` parameter at `app/src/middleware.php`. This is not recommended for productive use.
* Please keep in mind that application serves for me as an exercise field to try out concepts and patterns. It should work but any overhead is deliberately accepted.

## Licence

This package is released below [MIT licence](https://opensource.org/licenses/MIT). Please see [License File](LICENSE) for more information.
