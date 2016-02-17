# Holiday API

Holiday API is website (and soon to be stand-alone PHP class) for obtaining
information about holidays. The project was started as a personal challenge to
see if I could generate holiday lists on the fly for any date instead of
keeping a static list in a database.

## Local Development

To install `composer`:

```shell
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

To install the dependencies:

```shell
composer install
npm install
```

To install `gulp`:

```shell
npm install -g gulp
```

To run the server:

```shell
gulp server
```
