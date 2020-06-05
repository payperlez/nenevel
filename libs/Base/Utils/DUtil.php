<?php

namespace DIY\Base\Utils;

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;

class DUtil {

    /**
     * @param $input
     * @param $length
     * @param bool|true $ellipses
     * @param bool|true $strip_html
     * @return string
     */
    public static function trim_text($input, $length, $ellipses = true, $strip_html = true) {
        if ($strip_html === true) {
            $input = strip_tags($input);
        }

        if (strlen($input) <= $length) {
            return $input;
        }

        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        if ($ellipses === true) {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
    }

    /**
     * @param $arr
     * @return bool
     */
    public static function is_multiArray($arr) {
        $rv = array_filter($arr,'is_array');
        return (count($rv)>0) ? true : false;
    }

    /**
     * @return mixed
     */
    public static function get_ip(){
        if(function_exists('apache_request_headers')){
            $headers = apache_request_headers();
        } else{
            $headers = $_SERVER;
        }

        if(array_key_exists('X-Forwarded-For', $headers) &&
                filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            $the_ip = $headers['X-Forwarded-For'];
        } elseif(array_key_exists('HTTP_X_FORWARDED_FOR', $headers) &&
                filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else{
            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }

        return $the_ip;
    }

    /**
     * @param $data
     * @param null $filename
     */
    public static function createCSV($data, $filename = null){
        if(!isset($filename)){
            $filename = "replies";
        }

        //Clear output buffer
        ob_clean();

        //Set the Content-Type and Content-Disposition headers.
        header("Content-type: text/x-csv");
        header("Content-Transfer-Encoding: binary");
        header("Content-Disposition: attachment; filename={$filename}-".date('YmdHis',strtotime('now')).".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        //Open up a PHP output stream using the function fopen.
        $fp = fopen('php://output', 'w');

        //Loop through the array containing our CSV data.
        foreach ($data as $row) {
            //fputcsv formats the array into a CSV format.
            //It then writes the result to our output stream.
            fputcsv($fp, $row);
        }

        //Close the file handle.
        fclose($fp);
    }

    /**
     * @param $algo - The hashing algorithm eg(md5, sha256 etc)
     * @param $data - The data that is going to be encoded
     * @param $salt - The key used as salt
     * @return string - The hashed/salted data
     */
    public static function hash_value($algo, $data, $salt) {
        $context = hash_init($algo, HASH_HMAC, $salt);
        hash_update($context, $data);
        return hash_final($context);
    }

    /**
     * @param array $array
     * @param array $keys
     * @return bool
     */
    public static function array_keys_exists($array, $keys) {
        if(count(array_intersect_key(array_flip($keys), $array)) === count($keys)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * hash_cost - Calculate the cost the server can take when using password_hash function
     *
     * @return int
     */
    public static function hash_cost(){
        $timeTarget = 0.05;
        $cost = 8;
        do{
            $cost++;
            $start = microtime(true);
            password_hash("diyframeworktest", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while(($end - $start) < $timeTarget);

        return $cost;
    }

    /**
     * read_stdin - Read data from the command line
     *
     * @return string
     */
    public static function read_stdin() {
        $fr=fopen("php://stdin","r");   // open our file pointer to read from stdin
        $input = fgets($fr,255);        // read a maximum of 255 characters
        $input = rtrim($input);         // trim any trailing spaces.
        fclose ($fr);                   // close the file handle
        return $input;                  // return the text entered
    }

    /**
     * debug - print array elements nicely in the browser;
     *
     * @param array $data
     *
     */
    public static function debug($data = array()){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();
    }

    /**
     * startsWith - check that a string starts with some character/string
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return boolean
     *
     */
    public static function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * endsWith - check that a string ends with some character/string
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return boolean
     *
     */
    public static function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * isXmlHttpRequest - check the existence of an ajax object
     *
     * @return boolean
     *
     */
    public static function isXmlHttpRequest(){
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;
    }

    /**
     * Get url for a route by using either name/alias, class or method name.
     * The name parameter supports the following values:
     * - Route name
     * - Controller/resource name (with or without method)
     * - Controller class name
     * 
     * When searching for controller/resource by name, you can use this syntax "route.name@method".
     * You can also use the same syntax when searching for a specific controller-class "MyController@home".
     * If no arguments is specified, it will return the url for the current loaded route.
     * 
     * @param string|null $name
     * @param string|array|null $parameters
     * @param array|null $getParams
     * @return \Pecee\Http\Url
     * @throws \InvalidArgumentException
     */
    public static function url(?string $name = null, $parameters = null, ?array $getParams = null) : Url{
        return Router::getUrl($name, $parameters, $getParams);
    }
    
    /**
     * @return \Pecee\Http\Response
     */
    public static function response() : Response{
        return Router::response();
    }

    /**
     * @return \Pecee\Http\Request
     */
    public static function request() : Request {
        return Router::request();
    }

    /**
     * Get input class
     * @param string|null $index Parameter index name
     * @param string|null $defaultValue Default return value
     * @param array ...$methods Default methods
     * @return \Pecee\Http\Imput\InputHandler|\Pecee\Http\Input\IInputItem|string
     */
    public static function input($index = null, $defaultValue = null, ...$methods) {
        if($index !== null) 
            return static::request()->getInputHandler()->value($index, $defaultValue, ...$methods);
        return static::request()->getInputHandler();
    }

    /**
     * redirect to another url
     *
     * @param string $url
     * @param int|null $code
     * @return void
     */
    public static function redirect(string $url, ?int $code = null) : void {
        if($code !== null) static::response()->httpCode($code);
        static::response()->redirect($url);
    }

    /**
     * Get current csrf-token
     * @return string|null
     */
    public static function csrf_token() : ?string {
        $baseVerifier = Router::router()->getCsrfVerifier();
        if($baseVerifier !== null) return $baseVerifier->getTokenProvider()->getToken();
        return null;
    }

    /**
     * Cast an array or an stdClass to another class
     *
     * @param array|stdClass $instance
     * @param string $className
     * @return new $className()
     */
    public static function castToObject($instance, $className){
        if(is_array($instance)){
            return unserialize(sprintf(
                'O:%d:"%s"%s',
                strlen($className),
                $className,
                strstr(serialize($instance), ':')
            ));
        } else if(is_object($instance)){
            return unserialize(sprintf(
                'O:%d:"%s"%s',
                strlen($className),
                $className,
                strstr(strstr(serialize($instance), '"'), ':')
            ));
        }
    }

    /**
     * crypt AES 256
     *
     * @param data $data
     * @param string $passphrase
     * @return base64 encrypted data
     */
    public static function encrypt($data, $passphrase){
        $salt = openssl_random_pseudo_bytes(16);
        $salted = '';
        $dx = '';

        // Salt the key(32) and iv(16) = 48
        while(strlen($salted) < 48){
            $dx = hash('sha256', $dx.$passphrase.$salt, true);
            $salted .= $dx;
        }

        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);
        return base64_encode($salt . $encrypted_data);
    }

    /**
     * decrypt AES 256
    *
    * @param data $edata
    * @param string $password
    * @return decrypted data
    */
    public static function decrypt($edata, $passphrase){
        $data = base64_decode($edata);
        $salt = substr($data, 0, 16);
        $ct = substr($data, 16);

        $rounds = 3;
        $data00 = $passphrase.$salt;
        $hash = array();
        $hash[0] = hash('sha256', $data00, true);
        $result = $hash[0];
        for($i = 1; $i < $rounds; $i++){
            $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
            $result .= $hash[$i];
        }

        $key = substr($result, 0, 32);
        $iv = substr($result, 32, 16);

        return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
    }

}
