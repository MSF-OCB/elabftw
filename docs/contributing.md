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

Edit `docker-compose.yml` and `elabctl.conf`, and follow the `@TODO`'s.

**Create required folders.**

```
mkdir -p $CODE_DIR/var/{backups,log,mysql,ssl,uploads}
```

**Install PHP.**

```
brew update
brew upgrade
brew tap homebrew/dupes
brew tap homebrew/versions
brew tap homebrew/homebrew-php
brew install php71 php71-mcrypt
```

**Install other tools.**

```
brew install composer
brew install yarn
npm install -g grunt-cli
```

**Install PHP and JavaScript dependencies.**

```
cd $CODE_DIR/src
composer install
yarn install
```

**Install Docker.**

Getting started:
[Docker for Mac](https://docs.docker.com/docker-for-mac/),
[Docker for Windows](https://docs.docker.com/docker-for-windows/).

Quick links to downloads:
[Mac](https://docs.docker.com/docker-for-mac/install/),
[Windows](https://docs.docker.com/docker-for-windows/install/).

**Install Docker containers.**

_This will take some time._

```
cd $CODE_DIR
docker-compose up
```

**Enable debug mode.**  
This will disable the caching of Twig templates.

Execute these commands one of the other:

- Launch container shell: `docker exec -it surveyo-mysql bash`
- Launch MySQL shell: `mysql -u$MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE`
- Execute SQL command: `UPDATE config SET conf_value = '1' WHERE conf_name = 'debug';`
- Exit MySQL shell: `exit` (or `ctrl + d`)
- Exit container shell: `exit` (or `ctrl + d`)


## Start the servers

```
cd $CODE_DIR
./elabctl start
```

The app will be accessible at https://localhost/ when the servers are ready (after 30-60s).

When you're done working, you can stop the servers with `./elabctl stop`.


## Common commands

- **Servers:**
    - View web server error logs: `./elabctl php-logs`
- **JS & CSS:**
    - Regenerate JS and CSS: `grunt`
    - Regenerate CSS: `grunt css`
    - Regenerate JS: `grunt js`
- **Gettext:**
    - Compile translations: `./elabctl compile-messages`
- **Testing:**
    - Run the unit tests: `grunt unit`
    - Run the unitand acceptance  tests: `grunt test`
- **Documentation:**
    - Generate a PHP Docblock documentation: `grunt api`

**Note:**
- `elabctl` commands must be executed from `<CODE_DIR>`.
- `grunt` commands must be executed from `<CODE_DIR>/src`.
