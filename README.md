![build status]](https://travis-ci.org/asiagohan/MonologStackdriverHandler.svg?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/asiagohan/MonologStackdriverHandler/badge.svg?branch=master)](https://coveralls.io/github/asiagohan/MonologStackdriverHandler?branch=master)
# MonologStackdriverHandler
Monolog Stackdriver Handler is Stackdriver handler for Monolog. It will send Stackdriver a log when an app logs something.  
To use this handler, you should have Google Project Id. For more details, check [here](http://www.stackdriver.com/)

## Installation
You can install the latest version with:
```bash
$ composer require asiagohan/monolog-stackdriver-handler
```

### When use it with Laravel5
edit bootstrap/app.php as below:
```php
$app->configureMonologUsing(function ($monolog) {
     $stackdriverHandler = new MonologStackdriverHandler('googleProjectId');
     $monolog->pushHandler($stackdriverHandler);
});
```

If you want to change the name of the log or other options,
```php
$app->configureMonologUsing(function ($monolog) {
     $stackdriverHandler = new MonologStackdriverHandler(
         'googleProjectId',
         'the-name-of-the-log',
         [
            'labels' => [
               'foo' => 'bar',
            ],
         ]
     );
     $monolog->pushHandler($stackdriverHandler);
});
```
