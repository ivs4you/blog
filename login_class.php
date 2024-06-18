<?php 
include_once("db_connect_class.php");

/* Class to manage users in database */
class login extends db_connect
{
    function __construct()
    {
        parent::__construct();
    }

    /* To save a new user in database
        $name - readable name
        $surname - surname
        $email - email
        $login_name - imagined login name
        $password - imagined password
        if error returns -1, else - array of value
    */
    function save_new_user($name, $surname, $email, $login_name, $password)
    {
        if (!isSet($name) && !isSet($surname) && !isSet($email) && !isSet($login_name) && !isSet($password))
        {
            return -1;
        }

        if (empty($name) && empty($surname) && empty($email) && empty($login_name) && empty($password))
        {
            return -1;
        }

        $sql = "INSERT INTO users (NAME, SURNAME, EMAIL, LOGIN, PASSWORD) values
         (\"".$name."\", \"".$surname."\", \"".$email."\", \"".$login_name."\", \"".password_hash($password,PASSWORD_BCRYPT)."\")";
        $result = $this->connect->query($sql);
        if (!$result) 
        {
            echo "<div class\"post error\">Error to add new user</div>";
            return -1;
        }
        return 0;

    }

       /* To find user in database according to entered data
        $login_name - login name
        $password - password
        if error returns -1, else - array of value
    */
    function get_login_id($login_name, $password)
    {
        if (!isSet($login_name) || !isSet($password))
        {
            return -1;
        }

        if (empty($login_name) || empty($password))
        {
            return -1;
        }

        $sql = "SELECT ID, PASSWORD from users where LOGIN=\"".$login_name."\"";
        $result = $this->connect->query($sql);
        if ($result)
        {
            if (mysqli_num_rows($result)>0){
                $r=mysqli_fetch_assoc($result);   
                if (password_verify($password, $r["PASSWORD"])) 
                {   
                    return $r["ID"];
                }
                else
                {
                    return -1;
                }

            }  
        }
        else
        {
            return -1;
        }

    }
    /* To output pattern of dialog form
    */
    function show_enter()
    {
        echo <<<_END
        <div class="login">
        <div id='tabs' class='tabs'>
        <ul>
        <li><a href='#panel1'>Enter</a></li>
        <li><a href='#panel2'>Registration</a></li>
        </ul>
        <div id='panel1'>
        <form action='index.php' method='post'>
        <fieldset>
        <legend>Information</legend>
        <table>
        <tr><td><label class='EnterFormLabel' for='Login'>Login: </label></td><td><input autofocus required title='length 2-10, letters or numbers' pattern='[A-Za-z0-9]{2,10}' type='text' name='Login'/></td></tr>
        <tr><td><label class='EnterFormLabel' for='Password'>Password: </label></td><td><input required title='length 8-10, letters or numbers' pattern='[A-Za-z0-9]{8,10}' type='password' name='Password'/></td></tr>
        </table>
        </fieldset>
        <input type='hidden' name='LoginType' value='1'/>
        <input type='submit' value='OK'/>
        </form>
        </div>              
        <div id='panel2'>
        <form action='index.php' method='post'>
        <fieldset>
        <legend>Information</legend>
        <table>
        <tr><td><label class='FormLabel' for='Surname'>Surname: </label></td><td><input autofocus required title='length 2-25, letters only' pattern='[A-Za-z]{2,25}' type='text' placeholder='Duck' name='Surname'/></td></tr>
        <tr><td><label class='FormLabel' for='Name'>Name: </label></td><td><input required title='length 2-25, letters only' pattern='[A-Za-z]{2,25}' type='text' placeholder='Donald' name='Name'/></td></tr>
        <tr><td><label class='FormLabel' for='Email'>Email: </label></td><td><input required type='email' placeholder='name@domain' name='Email'/></td></tr>
        <tr><td><label class='FormLabel' for='Login'>Login: </label></td><td><input required title='length 2-10, letters or numbers' pattern='[A-Za-z0-9]{2,10}' type='text' name='Login'/></td></tr>
        <tr><td><label class='FormLabel' for='Password'>Password: </label></td><td><input required title='length 8-10, letters or numbers' pattern='[A-Za-z0-9]{8,10}'  type='password' name='Password'/></td></tr>
        <tr><td><label class='FormLabel' for='Confirm'>Confirm: </label></td><td><input required title='length 8-10, letters or numbers' pattern='[A-Za-z0-9]{8,10}' type='password' name='Confirm'/></td></tr>
        </table>
        </fieldset>
        <input type='hidden' name='LoginType' value='2'/>
        <input type='submit' value='OK'/>
        </div>
        </div>
        </div>
_END;
    }

    /* To output pattern of script for dialog form
    */
    function show_script()
    {
        echo <<<_END
        <script>
        $(document).ready(
            function () 
            {
                $('#tabs').tabs()            
            });
        </script>
_END;
    }


}
?>
