<?php
    # All configurations can be found in here.

    # Load sensitive data
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
    $dbopts = parse_url(getenv('DATABASE_URL'));

    define('RUNTIME_ENVIRONMENT', getenv('RUNTIME_ENVIRONMENT'));
    define('APP_TYPE', 'web'); 

    # Database constants ...
    define('DATABASE',
        serialize(
            array(
                'type' => $dbopts['scheme'],
                'host' => $dbopts['host'],
                'name' => ltrim($dbopts['path'], '/'),
                'user' => $dbopts['user'],
                'passwd' => $dbopts['pass'],
                'persistent' => false
            )
        )
    );

    define('SESSION_STORE', getenv('SESSION_STORE'));
    if(SESSION_STORE === 'redis') define('REDIS_URI', getenv('REDIS_URI'));

    # Either use the propelorm or not ...
    # This will be changed very soon to include other orms ...
    define('USE_ORM', false);
    # Templates loader ...
    define('TEMPLATES',
        serialize(
            array(
                'templateDir' => 'public/views/',
                'cacheDir' => 'public/cache', 
                'debug' => (RUNTIME_ENVIRONMENT === 'dev') ? true : false, 
                'autoescape' => false
            )
        )
    );

    # Secret key. Make sure you don't change this key whilst in production ....
    # Used mostly for hashing ...
    # You could add more hash keys by just defining one.
    define('SECRET_KEY', getenv('SECRET_KEY'));

    # Paths. Make sure you put a trailing slash(/) infront of all your paths!!!
    define('BASE_URL', 'http://' . getenv('DOMAIN') . '/');
    define('STATIC_URL', '');
    define('MEDIA_URL', '');
