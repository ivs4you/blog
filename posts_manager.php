<?php
include_once("pattern_class.php");
include_once("post_class.php");
include_once("comment_class.php");
session_start();
$pattern = new pattern();
$pattern->show_top();


@$text = $_POST['text'];
@$title = $_POST['title'];

// Here are new text and title of post. Need to add new post or update.
if (isSet($text) && isSet($title))
{

    @$edit_id = $_POST['edit_id'];
    
    if (isSet($edit_id))
    {
        $post = new post();
        $post->save_post($text, $title, $edit_id);
    }
    else
    {
        $post = new post();
        $post->save_new_post($text, $title, $_SESSION["login_id"]);
    }
    header("Location: index.php");
    exit();
}

@$del_id = $_GET['del_id'];

//Here we have ID of post to delete. It means that this post must be deleted.
if (isSet($del_id))
{
    $post = new post();
    $post->del_post($del_id);
    header("Location: index.php");
    exit();
}

@$edit_id = $_GET['edit_id'];

$post_text = "";

//Here we have ID of post to edit. Getting ingormation to edit.
if (isSet($edit_id))
{   
    $post = new post();
    $post_text = $post->get_post_text($edit_id);
    if ($post_text == -1)
    {
        exit();
    }
}

@$view_id = $_REQUEST['view_id'];

//Block of code to get information of post to review (not edit)
if (isSet($view_id))
{
    
    $post = new post();
    $post_text = $post->get_post_text($view_id);
    if ($post_text == -1)
    {
        exit();
    }

    @$comment = $_POST['comment'];

    //In preview mode we also can add a new comment
    if (isSet($comment))
    {   
        @$text = $_POST["text"];
        if (isSet($text))
        {
            $comment = new comment();
            $comment->save_new_comment($text, $view_id, $_SESSION["login_id"]);
        }
    }

}

@$comm_del_id = $_GET['comm_del_id'];

//Deleting of comment
if (isSet($comm_del_id))
{
    $comment = new comment();
    $comment->del_comment($comm_del_id);
}


//Show the post in different mode (preview, edit, add)
if (isSet($edit_id))
{
    echo "<div class=\"post2\"><form action='posts_manager.php' method='post'> ";

    echo "<div class=\"title\"><label  for=\"title\">Title:</label>";
    echo "<input required type=\"text\" maxlength=\"50\" name=\"title\" value=\"".html_entity_decode($post_text[1])."\"></div>";

    echo "<textarea class=\"wysiwyg_editor\" name=\"text\" cols=\"40\" rows=\"10\">".htmlspecialchars_decode($post_text[0])."</textarea>";
    echo "<div class=\"manager\"><input type=\"hidden\" name=\"edit_id\" value=\"".$edit_id."\"/>";
    echo "<input type='submit' value='Save'/>";
    echo "<input type='button' onclick=\"window.location.href='index.php'\" value='Cancel'/></div> ";
    
    echo "</form> </div>";
    $pattern->add_wysiwyg_editor();
}
elseif (isSet($view_id))
{
   

    echo "<div class=\"post2\">";
    echo "<label >".htmlspecialchars_decode($post_text[1])."</label>";
    echo "<div class=\"simple_viewer\" name=\"text\">".htmlspecialchars_decode($post_text[0])."</div>";
    echo "<div class=\"manager\"> <input type='button' onclick=\"window.location.href='index.php'\" value='Close'/></div> ";
    echo "</div>";
    if (isSet($_SESSION["login_id"]))
    {
        echo "<br>";
        
        echo "<div class=\"post2\">";
        echo "<form action='posts_manager.php' method='post'>";
        echo "<textarea class=\"wysiwyg_editor\" name=\"text\" cols=\"40\" rows=\"10\"></textarea>";
        echo "<div class=\"manager\">";
        echo "<input type=\"hidden\" name=\"view_id\" value=\"".$view_id."\"/>";
        echo "<input type=\"hidden\" name=\"comment\" value=\"1\"/>";
        echo "<input type='submit' value='Add comment'/></div>";
        echo "</form></div>";
        $pattern->add_wysiwyg_editor();
    }
       
        
        $comment = new comment();
        $comments = $comment->get_comments($view_id);

        if (($comments != -1) && (count($comments) > 0))
        {
            echo "<div class=\"slide_title\">comments </div>";
            echo "<div class=\"slide_container comments\">";
            for ($i=0; $i<count($comments); $i++)
            {
                echo "<div class=\"comment\">";
                echo "<span>date: ".date("F j Y g:i:s", $comments[$i][2])." </span><br>";
                echo "<span>author: ".$comments[$i][3]." ".$comments[$i][4]." </span>";
                echo "".htmlspecialchars_decode($comments[$i][0]);
                if (isSet($_SESSION["login_id"]) && ($_SESSION["login_id"] == $comments[$i][5]))
                {
                    echo "<div class=\"manager\"><a title=\"delete comment\" href=\"posts_manager.php?view_id=".$view_id."&comm_del_id=".$comments[$i][1]."\"><img width=\"15\" src=\"icons/delete.png\" alt=\"delete\"></a></div>";
                }
                echo "</div>";

            }
            echo "</div>";  
    }
}
else
{
    echo "<div class=\"post2\"><form action='posts_manager.php' method='post'> ";
    echo "<div class=\"title\"><label  for=\"title\">Title:</label>";
    echo "<input required  type=\"text\" maxlength=\"50\" name=\"title\" value=\"".htmlspecialchars_decode($post_text[1])."\"></div>";
    echo "<textarea class=\"wysiwyg_editor\" name=\"text\" cols=\"40\" rows=\"10\">".htmlspecialchars_decode($post_text[0])."</textarea><br>";
    echo "<input type='submit' value='Save'/>";
    echo "<input type='button' onclick=\"window.location.href='index.php'\" value='Cancel'/>";
    echo "</form></div> ";
    $pattern->add_wysiwyg_editor();


}





//$pattern->show_bottom();
?>