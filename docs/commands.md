# Common Commands

> `surveyo` commands must be executed from `<CODE_DIR>`. On Linux, this script must executed as root/with sudo.  
> `grunt` commands must be executed from `<CODE_DIR>/src`.


## Deployment

- Deploy new source code: `./surveyo git-deploy`


## Services

- Start services: `./surveyo start`
- Restart services: `./surveyo restart`
- Stop services: `./surveyo stop`


## Logs

- View PHP logs: `./surveyo php-logs`
- View web server logs: `./surveyo web-logs`
- View MySQL server logs: `./surveyo mysql-logs`


## Translations

- Compile translations: `./surveyo compile-messages`


## JS & CSS

- Regenerate JS and CSS: `grunt`
- Regenerate CSS: `grunt css`
- Regenerate JS: `grunt js`


## Testing

- Run the unit tests: `grunt unit`
- Run the unitand acceptance  tests: `grunt test`


## Documentation

- Generate a PHP Docblock documentation: `grunt api`
