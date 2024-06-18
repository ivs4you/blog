<?php
/* Base class to establish connection to database */
class db_connect
{
    const USERNAME = "blog";
    const PASSWORD = "12345";
    const DBNAME = "blog";
    const SERVER = "localhost";

    protected $connect;

    /* Make a connection to a database
    */
    function __construct()
    {
        $mysql = new mysqli(self::SERVER, self::USERNAME, self::PASSWORD, self::DBNAME);
                
        if (mysqli_connect_errno())
        {
            echo "<div class=\"post error\">Error to connect to database " . $mysql->connect_error."</div>";
            exit();
        }
        else
        {
            $this->connect = $mysql; 
        }

    }
    /* Close a connection to a database
    */
    function __destruct()
    {
        $this->connect->close();
    }
}
?>