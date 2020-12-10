# component-deployer

[![Current version](https://img.shields.io/packagist/v/eureka/component-deployer.svg?logo=composer)](https://packagist.org/packages/eureka/component-deployer)
[![Supported PHP version](https://img.shields.io/static/v1?logo=php&label=PHP&message=%5E7.4&color=777bb4)](https://packagist.org/packages/eureka/component-deployer)
![CI](https://github.com/eureka-framework/component-deployer/workflows/CI/badge.svg)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=eureka-framework_component-deployer&metric=alert_status)](https://sonarcloud.io/dashboard?id=eureka-framework_component-deployer)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=eureka-framework_component-deployer&metric=coverage)](https://sonarcloud.io/dashboard?id=eureka-framework_component-deployer)


PHP Installer &amp; Deployer for projects based on Eureka Framework


## Usage

### Symfony config for dependency injections in scripts
```yaml
parameters:
    eureka.deployer.config:
        # Default values
        app.name:   'example'
        app.domain: 'www.example.com'
        app.tag:    '1.0.0' # git tag version to deploy when no arg is set with console command

        install:
            #~ Installation steps
            step.start: 0
            step.end:   100

            step.list:
                #~ 0: Start Install (defined in main install script)
                #~ 1 to 9: reserved for deployed itself
                #~ 001: Install composer
                #~ 002: Copy secrets files

                #~ Setup some directories
                10: 'Install/Init/Directories'
                11: 'Install/Init/Symlink'

                #~ Yarn / npm
                40: 'Install/Yarn/Install'
                41: 'Install/Yarn/EncoreBuild'

                #~ Cleaning installation files
                70: 'Install/Clean/Files'


                #~ 90 to 99: reserved for deployed itself
                #~ 098: Clean cache
                #~ 099: Init directory & perms again for production
                #~ 100: Ending installation (defined in main install script)

            init:
                directories:
                    'var/log':   777
                    'var/cache': 777
                    'var/test':  777

                symlinks:
                    '/var/upload/%app.name%/': 'web/upload'

            copy:
                files:
                    # src: dest
                    '/var/conf/{platform}/{domain}/database.yaml': 'config/secrets/database.yaml'

            clean:
                files:
                    - '.gitignore'
                    - 'composer.lock'
                    - 'README.md'
                    - 'webpack.config.js'
                    - 'yarn.lock'
                    - 'yarn-error.log'

                directories:
                    - 'assets/'
                    - 'node_modules/'
                    - 'sql/'

services:
    # default configuration for services in *this* file
    _defaults:
        autowire: true
        autoconfigure: true
        public: false

    Eureka\Component\Deployer\:
        resource: '../../vendor/eureka/component-deployer/src/*'
        exclude:  '../../vendor/eureka/component-deployer/src/{Script}'

    Eureka\Component\Deployer\Script\:
        resource: '../../vendor/eureka/component-deployer/src/Script/*'
        public: true
        calls:
            - ['setPathBuilder']

    Eureka\Component\Deployer\Common\PathBuilder:
        arguments:
            $pathSource: '/var/src'
            $pathLink:   '/var/www'

```

### Console command (in you application)

```bash
~/my-app/$ bin/console deploy --help

Use    :
php  Eureka\Component\Deployer\Script\Deploy [OPTION]...
OPTIONS:
  -h,     --help                        Reserved - Display Help
  -p ARG, --platform=ARG                Platform where installation is executed (default: "prod")
  -t ARG, --tag=ARG                     Tag version to install (default from config or 1.0.0 if not defined in config)
  -d ARG, --domain=ARG                  Application domain (ie: www.my-app.com) (default from config)
  -n ARG, --name=ARG                    Application name, used to retrieve config (default from config)

```

## Installation

You can install the component (for testing) with the following command:
```bash
make install
```

## Update

You can update the component (for testing) with the following command:
```bash
make update
```


## Testing

You can test the component with the following commands:
```bash
make phpcs
make tests
make testdox
```