# Common Commands


## Servers

- View web server error logs: `./surveyo php-logs`


## Gettext

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


## Notes

- `surveyo` commands must be executed from `<CODE_DIR>`. On Linux, this must executed as root/with sudo.
- `grunt` commands must be executed from `<CODE_DIR>/src`.
