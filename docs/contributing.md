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


## Start the servers

```
cd $CODE_DIR
./elabctl start
```

The app is now accessible at https://localhost/
