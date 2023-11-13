<?php

require_once('config.php');

class DB
{
    private static $instance = null;

    public static function get()
    {
        if (self::$instance == null) {
            $uri = "mysql:host=" . Config::$db_host  . ";dbname=" . Config::$db_name. ';charset=utf8mb4';

            try {
                self::$instance = new PDO(
                    $uri,
                    Config::$db_username,
                    Config::$db_password,
                    array(
                        PDO::ATTR_PERSISTENT => true
                    )

                );
                self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES,TRUE);
                
            } catch (PDOException $e) {
                // Handle this properly
                throw $e;
            }
        }

        return self::$instance;
    }
}
