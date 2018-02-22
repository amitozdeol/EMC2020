```
   ___   ____ ___   ____     _       ____________
  |__ \ / __ \__ \ / __ \   | |     / / ____/ __ )
  __/ // / / /_/ // / / /   | | /| / / __/ / __  |
 / __// /_/ / __// /_/ /    | |/ |/ / /___/ /_/ /
/____/\____/____/\____/     |__/|__/_____/_____/
```

#### Web interface for the EMC 2020
This includes the website for registering and managing the remote installs, a
series of database migration files for setting up or updating a database
instance, and a command line utility for running local and remote tasks.


## Requirements
**Webserver:** You will need a webserver running (e.g.
[apache](https://httpd.apache.org) or [nginx](http://nginx.org/en))

**PHP:** The server must be configured to parse [PHP](http://php.net) pages using
PHP version 5.4 or greater

**Database:** A database service must be available (we've been using
[MySQL](https://www.mysql.com/)).

**Dependancy Manager:** [Composer](https://getcomposer.org) is required in order
to download dependancies and generate a class autoload file.


## Installation
Navigate to the location where you want to keep this projects files and run
`git clone git@git.hvtdc.org:oas/2020_web.git`.

Once the project files are cloned you can run `composer install` to download
dependancies and generate an autoload file.

Now configure the web server to load files from the projects `/public` directory
as it's web_root. If using apache, be sure that teh `mod_rewrite` module is
activated so that URLs can be rewritten by the `.htaccess` file without
displaying `index.php`.

Make a copy of the sample environment file at `env.sample.php` and rename it to
`.env.local.php`. Fill that environment file with the correct credentials for
the database being used.

Make sure that PHP is configured to use short open tags by setting the
`short_open_tag` value to `On` in your `php.ini` file. If you don't know where
the correct `php.ini` file is you can output that information by loading a
webpage that executes the `php_info()` function.


---


[Source](http://git.hvtdc.org/oas/2020_web/tree/master)

[Documentation](http://docs.hvtdc.org/EMC_2020)

[Framework Documentation](http://laravel.com/docs/4.2)

[Bug Tracker](http://git.hvtdc.org/oas/2020_web/issues)