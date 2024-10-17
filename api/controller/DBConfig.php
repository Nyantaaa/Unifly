<?php
class DB
{
    private static $writeDBConnection;
    private static $readConnection;
    public static function connectionWriteDB()
    {
        if (self::$writeDBConnection == null) {
            self::$writeDBConnection = new
            PDO('mysql:host=localhost;dbname=unifly;charset=utf8', 'root', '');
            self::$writeDBConnection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            self::$writeDBConnection->setAttribute(
                PDO::ATTR_EMULATE_PREPARES,
                false
            );
        }
        return self::$writeDBConnection;
    }
    public static function connectionReadDB()
    {
        if (self::$readConnection == null) {
            self::$readConnection = new
            PDO('mysql:host=localhost;dbname=unifly;charset=utf8', 'root', '');
            self::$readConnection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            self::$readConnection->setAttribute(
                PDO::ATTR_EMULATE_PREPARES,
                false
            );
        }
        return self::$readConnection;
    }
}
?>