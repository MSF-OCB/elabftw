# Installation

For more information please read the official documentation.  
For the production environment, see [install](https://doc.elabftw.net/install.html).  
For the development environment, see [contributing](https://doc.elabftw.net/contributing.html).


## Define installation variables

```
DEPLOY_ENV="production|development"
CODE_BRANCH="master|develop"
CODE_DIR="${HOME}/surveyo"
```


## Create the project folder

```
mkdir -p $CODE_DIR
```


## Install dependencies

### Debian/Ubuntu


> **If PHP 7.1 is not available**, install the following apt repository [ppa:ondrej/php](https://launchpad.net/~ondrej/+archive/ubuntu/php):
>
>```
[[ -n `apt-cache search php7.1` ]] || sudo add-apt-repository ppa:ondrej/php
```
>
> _Remark: If there's a "WARNING: add-apt-repository is broken with non-UTF-8 locales", you can ignore it and continue by pressing ENTER._


```
sudo apt-get update
sudo apt-get install --yes docker-engine docker-compose
sudo apt-get install --yes php7.1 php7.1-mcrypt php7.1-curl php7.1-gd php7.1-zip php7.1-mbstring php7.1-xml
sudo apt-get install --yes npm
sudo npm install -g grunt-cli
```

Install Composer ([doc](https://getcomposer.org/download/)):

```
[ -d ${HOME}/bin ] || mkdir ${HOME}/bin
cd ${HOME}/bin

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

mv composer.phar composer
```

Install [Yarn](https://yarnpkg.com/en/docs/install#linux-tab).
    _(not available in apt-get)_


### macOS

Install [Docker for Mac](https://docs.docker.com/docker-for-mac/)
    ([download](https://docs.docker.com/docker-for-mac/install/)).
    _(not yet available on brew)_

```
brew update
brew upgrade
brew tap homebrew/dupes
brew tap homebrew/versions
brew tap homebrew/homebrew-php
brew install bash
brew install php71 php71-mcrypt
brew install composer
brew install yarn
npm install -g grunt-cli
```


### Windows

- Install [Docker for Windows](https://docs.docker.com/docker-for-windows/)
    ([download](https://docs.docker.com/docker-for-windows/install/)).
- Install [PHP](http://php.net/manual/en/install.windows.php).
- Install [Composer](https://getcomposer.org/doc/00-intro.md#installation-windows).
- Install [Yarn](https://yarnpkg.com/en/docs/install#windows-tab).
- Install [Grunt](https://gruntjs.com/getting-started).


## Configure GitHub deploy key

**ONLY FOR THE PRODUCTION ENVIRONMENT.**

```
keyname="surveyo-${DEPLOY_ENV}-deploy-key"
ssh-keygen -b 4096 -f ${HOME}/.ssh/${keyname} -C $keyname -N ""

if [ -f ${HOME}/.ssh/config ]; then echo "" >> ${HOME}/.ssh/config;
else touch ${HOME}/.ssh/config && chmod 644 ${HOME}/.ssh/config; fi
cat << EOF >> ${HOME}/.ssh/config
# Deploy key for https://github.com/MSF-OCB/surveyo
Host github.com
    User git
    IdentityFile ~/.ssh/${keyname}
EOF
```

Go to [GitHub add a new deploy key](https://github.com/MSF-OCB/surveyo/settings/keys/new) and fill in the form:

- Title: `Production server`
- Key: paste the content of `${keyname}.pub`
- Allow write access: No (unchecked)


## Get the source code

```
cd $CODE_DIR
git clone -b $CODE_BRANCH git@github.com:MSF-OCB/surveyo.git .
```


## Create required folders

```
mkdir -p $CODE_DIR/var/{backups,log,mysql,ssl,uploads}
```


## Create the config files

```
cd $CODE_DIR
cp docker-compose.example.yml docker-compose.yml
cp surveyo.example.conf surveyo.conf
```

Edit `docker-compose.yml` and `surveyo.conf`, and follow the `@TODO`'s.


## Install PHP and JavaScript dependencies

```
cd $CODE_DIR/src
composer install
yarn install
```


## Install Docker containers

> **Remark:** On Linux, the `docker*` commands must be executed as root/with `sudo`.

```
cd $CODE_DIR
docker-compose up
```

## Enable debug mode

> **ONLY FOR THE DEVELOPMENT ENVIRONMENT.**

This will disable the caching of Twig templates.

Execute these commands one of the other:

- Launch container shell: `docker exec -it surveyo-mysql bash`
- Launch MySQL shell: `mysql -u$MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE`
- Execute SQL command: `UPDATE config SET conf_value = '1' WHERE conf_name = 'debug';`
- Exit MySQL shell: `exit` (or `ctrl + d`)
- Exit container shell: `exit` (or `ctrl + d`)


## Start the servers

> **Remark:** On Linux, the `./surveyo` commands must be executed as root/with `sudo`.

```
cd $CODE_DIR
./surveyo start
```

> See `./surveyo help` and the file `commands.md` in this folder for more commands.
