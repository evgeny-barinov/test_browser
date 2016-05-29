<?php
/**
 * example
 * php browser.php username=admin password=123 email=admin@site.com
 */
require_once "class_browser.php";

$username = 'admin';
$password = '123';

$availableParams = [
    'username', 'password', 'email'
];

if (PHP_SAPI == 'cli') {
    array_shift($argv); //убираем script name
    foreach ($argv as $arg) {
        
        list($param, $value) = explode('=', $arg);
        if (in_array($param, $availableParams)) {
            $$param = $value;
        }

    }
}

$browser = new browser("http://test.local");
$browser->go("/recr.php");
$browser->go("/recr.php?formsubmit", array("username" => $username, "password" => $password, "chk" => $browser->cookie['chk']));

$result = $browser->regexp('~name=line_id\[\] value=\'([0-9]+)\'~Usi');

if (!empty($email)) {
    mail($email, 'Result', $result);
} else {
    var_dump($result);
}
