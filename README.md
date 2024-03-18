# component-deployer

[![Current version](https://img.shields.io/packagist/v/eureka/component-deployer.svg?logo=composer)](https://packagist.org/packages/eureka/component-deployer)
[![Supported PHP version](https://img.shields.io/static/v1?logo=php&label=PHP&message=8.1%20-%208.3&color=777bb4)](https://packagist.org/packages/eureka/component-deployer)
![CI](https://github.com/eureka-framework/component-deployer/workflows/CI/badge.svg)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=eureka-framework_component-deployer&metric=alert_status)](https://sonarcloud.io/dashboard?id=eureka-framework_component-deployer)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=eureka-framework_component-deployer&metric=coverage)](https://sonarcloud.io/dashboard?id=eureka-framework_component-deployer)


PHP Installer &amp; Deployer for projects based on Eureka Framework


## Composer
```bash
composer require "eureka/component-deployer"
```

## Usage

### Symfony config for dependency injections in scripts
```yaml
parameters:

  eureka.deployer.dir.src:  '/path/to/src'  # where the sources will be installed
  eureka.deployer.dir.www:  '/path/to/www'  # where the symlink on source will be put (link to use in apache/nginx conf)
  eureka.deployer.dir.conf: '/path/to/conf' # where the config with prod secrets are stored

  eureka.deployer.config:
    # Default values
    app.name:   '%app.name%'
    app.tag:    '%app.version%'
    app.domain: 'www.%app.name%.com'

    install:
      #~ Installation steps
      step.start: 0
      step.end:   100

      step.list:
        #~ 0: Start Install (defined in main installation script)
        #~ 1 to 9: reserved for deployed itself
        #~  1: Install composer
        #~  2: Copy secrets files

        #~ Setup some directories
        10: 'Install/Init/Directories'
        11: 'Install/Init/Symlink'

        #~ Yarn / npm
        40: 'Install/Yarn/Install'
        41: 'Install/Yarn/EncoreBuild'

        #~ Cleaning installation files
        70: 'Install/Clean/Files'

        #~ Composer cleaning & install no-dev
        #80: 'Install/Clean/Cache'

        #~ 90 to 99: reserved for deployed itself

        #~  98: Clean cache
        #~  99: Init directory again for production
        #~ 100: Ending installation (defined in main installation script)

      init:
        directories:
          'var/log':   777
          'var/cache': 777

        symlinks:
          '/path/to/upload/': 'web/upload'

      copy:
        #~ list of files to copy, with the format "from: to"
        files:
          '/path/to/conf/{platform}/{domain}/app.yaml':      'config/secrets/app.yaml'
          '/path/to/conf/{platform}/{domain}/database.yaml': 'config/secrets/database.yaml'

      clean:
        files:
          - '.gitignore'
          - 'composer.lock'
          - 'package.json'
          - 'README.md'
          - 'webpack.config.js'
          - 'yarn.lock'
          - 'yarn-error.log'

        directories:
          - 'assets/'
          - 'node_modules/'
          - 'sql/'


services:
  _defaults:
    autowire: true

  Eureka\Component\Deployer\:
    resource: '../../vendor/eureka/component-deployer/src/*'
    exclude:  '../../vendor/eureka/component-deployer/src/{Script}'

  Eureka\Component\Deployer\Script\:
    resource: '../../vendor/eureka/component-deployer/src/Script/*'
    public: true
    calls:
      - setPathBuilder: [ '@Eureka\Component\Deployer\Common\PathBuilder']
      - setRootDir:     [ '%kernel.directory.root%' ]
      - setConfig:      [ '%eureka.deployer.config%' ]

  Eureka\Component\Deployer\Common\PathBuilder:
    arguments:
      $pathSource: '%eureka.deployer.dir.src%'
      $pathLink:   '%eureka.deployer.dir.www%'

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



## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file.


### Install / update project

You can install project with the following command:
```bash
make install
```

And update with the following command:
```bash
make update
```

NB: For the components, the `composer.lock` file is not committed.

### Testing & CI (Continuous Integration)

#### Tests
You can run unit tests (with coverage) on your side with following command:
```bash
make tests
```

You can run integration tests (without coverage) on your side with following command:
```bash
make integration
```

For prettier output (but without coverage), you can use the following command:
```bash
make testdox # run tests without coverage reports but with prettified output
```

#### Code Style
You also can run code style check with following commands:
```bash
make phpcs
```

You also can run code style fixes with following commands:
```bash
make phpcsf
```

#### Check for missing explicit dependencies
You can check if any explicit dependency is missing with the following command:
```bash
make deps
```

#### Static Analysis
To perform a static analyze of your code (with phpstan, lvl 9 at default), you can use the following command:
```bash
make analyse
```

To ensure you code still compatible with current supported version at Deezer and futures versions of php, you need to
run the following commands (both are required for full support):

Minimal supported version:
```bash
make php81compatibility
```

Maximal supported version:
```bash
make php83compatibility
```

#### CI Simulation
And the last "helper" commands, you can run before commit and push, is:
```bash
make ci  
```

## License

This project is currently under The MIT License (MIT). See [LICENCE](LICENSE) file for more information.
