<?php
    require_once 'vendor/autoload.php';
    use DIY\Base\Utils\DRand;

    function prompt_silent($prompt = "Enter Password:") {
        if (preg_match('/^win/i', PHP_OS)) {
            $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
            file_put_contents($vbscript, 'wscript.echo(InputBox("' . addslashes($prompt) . '", "", "password here"))');
            $command = "cscript //nologo " . escapeshellarg($vbscript);
            $password = rtrim(shell_exec($command));
            unlink($vbscript);
            return $password;
        } else {
            $command = "/usr/bin/env bash -c 'echo OK'";
            if (rtrim(shell_exec($command)) !== 'OK') {
                trigger_error("Can't invoke bash");
                return;
            }
            $command = "/usr/bin/env bash -c 'read -s -p \"" . addslashes($prompt) . "\" mypassword && echo \$mypassword'";
            $password = rtrim(shell_exec($command));
            echo "\n";
            return $password;
        }
    }

    // Create and open .env file to write in it ...
    $envfile = fopen("config/.env", 'w') or die("Unable to create file!");

    // Receive standard input ...
    $handle = fopen("php://stdin", "r");

    // Generate secret key and append to file ...
    $secret_key = DRand::getCustomGen(str_split("!#$%&()*+,-./0123456789:;<>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~"), 64);
    fwrite($envfile, "SECRET_KEY = {$secret_key}\n");

    // Prepare database permissions ...
    echo "Are you going to use a database? Type 'yes' or 'no' to continue: ";
    $useDB = fgets($handle);

    if(trim($useDB) === 'yes'){
        $scheme = "";
        while(!in_array(trim($scheme), array('mysql', 'postgresql'))){
            echo "Enter database engine are you using? => ['mysql', 'postgresql']: ";
            $scheme = fgets($handle);
        }

        echo "Enter username for your specified database engine: ";
        $user = fgets($handle);

        $password = prompt_silent("Enter the password for the user (Text should not show up on terminal): ");

        echo "Enter host on which database engine is located: ";
        $host = fgets($handle);

        echo "Enter port the database engine communicate on: ";
        $port = fgets($handle);

        echo "Enter database name: ";
        $dbname = fgets($handle);

        // form databse uri ...
        $database_uri = trim($scheme) . "://"; 
        $database_uri .= trim($user) . ":"; 
        $database_uri .= $password . "@"; 
        $database_uri .= trim($host) . ":";
        if(trim($port) != '') $database_uri .= trim($port);
        else $database_uri .= (trim($scheme) === 'mysql') ? "3306" : "5432";
        $database_uri .= "/" . trim($dbname);
            
        // write database uri to file ...
        fwrite($envfile, "DATABASE_URL = " . trim($database_uri) . "\n");
    } else{
        echo "If you are not using any database, make sure you comment out the 'DATABASE' constant in config/settings.php ... \n";
        fwrite($envfile, "DATABASE_URL = " . "\n");
    }

    // session store ...
    echo "What session store will you want to use? => ['files', 'redis']: ";
    $session_store = fgets($handle);
    if(trim($session_store) !== 'redis') $session_store = "files";
    fwrite($envfile, "SESSION_STORE = " . trim($session_store) . "\n");

    if(trim($session_store) === 'redis'){
        echo "What is the redis host: ";
        $rhost = fgets($handle);
        $rhost = trim($rhost) ?: "127.0.0.1";

        echo "What is the redis port: ";
        $rport = fgets($handle);
        $rport = (int)trim($rport) ?: 6379;

        echo "What is the redis database: ";
        $rdb = fgets($handle);
        $rdb = (int)trim($rdb) ?: 0;

        $redis_uri = "tcp://{$rhost}:{$rport}?database={$rdb}";
        fwrite($envfile, "REDIS_URI = " . $redis_uri . "\n");

    }

    // Domain name ...
    echo "Enter domain name for your project / host part of your project's url: ";
    $domain = fgets($handle);
    fwrite($envfile, "DOMAIN = " . trim($domain) . "\n");

    // Runtime environment ...
    $env = "";
    while(!in_array(trim($env), array('dev', 'prod'))){
        echo "What environment are you running on? => [dev, prod]: ";
        $env = fgets($handle);
    }
    
    fwrite($envfile, "RUNTIME_ENVIRONMENT = " . trim($env));

    // close env file ... 
    fclose($envfile);

    // making a copy of settings file ...
    if(!copy("config/settings.sample.php", "config/settings.php")) echo "Make sure you manually make a copy of 'config/settings.sample.php' \n";

    echo "\n";
    echo "Check config/.env and make sure that the configuration setup is correct. Cheers to development... \n";
?>