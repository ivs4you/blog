<?php
include("post_class.php");
include("login_class.php");
include("pattern_class.php");
session_start();
$pattern = new pattern();
$pattern->show_top();


//Check EXIT status from session
@$exit=$_GET['exit'];
if(isSet($exit) && (int)$exit==1){
    session_unset();
    session_destroy();
}

@$login_type =  $_POST['LoginType'];
$login = new login();
    
//Manipulating with users 
if (isSet($login_type))
{
    if ($login_type == 1) //getting user ID form DB according to entered login and password
    {
        @$login_name = $_POST['Login'];
        @$password = $_POST['Password'];

        if (isSet($login_name) && isSet($password))
        {

            $login_id = $login->get_login_id($login_name, $password);
            if ( $login_id != -1)
            {
                $_SESSION["login_id"]=$login_id;
            }
            else
            {
                echo "<div class=\"post error\">There is no such user</div>";
            }
        }
    }
    else if ($login_type == 2) //adding a new user according to enteres data
    {
        @$name = $_POST['Name'];
        @$surname = $_POST['Surname'];
        @$email = $_POST['Email'];
        @$login_name = $_POST['Login'];
        @$password = $_POST['Password'];

        if (isSet($name) && isSet($surname) && isSet($email) && isSet($login_name) && isSet($password))
        {

            $result = $login->save_new_user($name, $surname, $email, $login_name, $password);
            if ( $result == -1)
            {
                echo "<div class=\"post, error\">Please check the data</div>";
            }
            else
            {
                echo "<div class=\"post\">The new user was created. Try to use new credentials.</div>";
            }
        }

    }
}

if (!isSet($_SESSION["login_id"])) //There is no a session of user. Need to ask credentials.
{
    $login->show_script();
    $login->show_enter();
    $post = new post();
    $posts = $post->get_all_posts();
    if ($posts == -1)
    {
        exit();
    }
    echo "<div class=\"desktop\">";
}
else //There is a user sesssion. Show custom rights to work with posts and comments.
{
    $post = new post();
    $posts = $post->get_all_posts();
    if ($posts == -1)
    {
        exit();
    }
    echo "<div class=\"desktop\">";
    echo "<a title=\"exit\" href=\" index.php?exit=1\"><img width=\"25\" src=\"icons/exit.png\" alt=\"exit\"></a>";
    echo "<a title=\"create post\" href=\"posts_manager.php\"><img width=\"25\" src=\"icons/new.png\" alt=\"new\"></a></a><br>";
}

    for ($i=0; $i<count($posts); $i++)
    {
        echo "<div class=\"post\">";
        echo "<a href=\"posts_manager.php?view_id=".$posts[$i][1]."\"><div class=\"title\">".$posts[$i][2]."</div>";
        echo "<div><span>date: ".date("F j Y g:i:s", $posts[$i][3])."</span><br>";
        echo "<span>author: ".$posts[$i][4]." ".$posts[$i][5]."  </span><br>";
        echo "<span>comments: ".$posts[$i][6]."</span></div></a>";
        //Let manipulate with posts only for creator
        if (isSet($_SESSION["login_id"]) && $_SESSION["login_id"] == $posts[$i][7])
        {
            echo "<div class=\"manager\"> <a title=\"edit post\" href=\"posts_manager.php?edit_id=".$posts[$i][1]."\"><img width=\"15\" src=\"icons/edit.png\" alt=\"delete\"></a>";
            echo "<a title=\"delete post\" href=\"posts_manager.php?del_id=".$posts[$i][1]."\"><img width=\"15\" src=\"icons/delete.png\" alt=\"delete\"></a></div>";
        }
        echo "</div>";
    }
    echo "</div>";
    


$pattern->show_bottom();

?>