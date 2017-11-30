# Contributing to the code

See official documentation for more information: https://doc.elabftw.net/contributing.html


## Installation

**Create the project folder.**

```
CODE_DIR="${HOME}/path/to/surveyo"
[ -d $CODE_DIR ] || mkdir -p $CODE_DIR
```

**Get the source code.**

```
cd $CODE_DIR
git clone -b develop git@github.com:MSF-OCB/surveyo.git .
```

**Create the config files.**

```
cp docker-compose.example.yml docker-compose.yml
cp elabctl.example.conf elabctl.conf
```

Edit these files and follow the `@TODO`'s.

**Install PHP and JavaScript dependencies.**

```
cd $CODE_DIR/src
composer install; yarn install
```

**Install Grunt**

```
npm install -g grunt-cli
```


## Start the servers

```
cd $CODE_DIR
./elabctl start
```

The app is now accessible at https://localhost/


## Update Docker containers

Enable debug mode to disable the caching of Twig templates.

- Container shell: `docker exec -it surveyo-mysql bash`
- MySQL shell: `mysql -u$MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE`
- Run SQL: `UPDATE config SET conf_value = '1' WHERE conf_name = 'debug';`
- Exit MySQL shell: `exit;` (or `ctrl + d`)
- Exit container shell: `exit` (or `ctrl + d`)


## Common commands

- Regenerate JS and CSS: `grunt`
- Regenerate CSS: `grunt css`
- Regenerate JS: `grunt js`
- Run the unit tests: `grunt unit`
- Run the unitand acceptance  tests: `grunt test`
- Generate a PHP Docblock documentation: `grunt api`
