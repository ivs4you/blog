<?php 
include_once("db_connect_class.php");

/* Class to manage posts in database */
class post extends db_connect
{
    function __construct()
    {
        parent::__construct();
    }

    /* To get text and title of post 
        $edit_id - ID of post which comment belongs to
        if error returns -1, else - array of value
    */
    function get_post_text($edit_id)
    {
        if (empty($edit_id))
        {
            return -1;
        }

        $edit_id=htmlspecialchars($edit_id);
        
        $sql = "SELECT TEXT, TITLE from posts where ID=".$edit_id;
        $result = $this->connect->query($sql);
        if ($result)
        {
            if (mysqli_num_rows($result)>0){
                $r=mysqli_fetch_assoc($result);       
                return array($r["TEXT"], $r["TITLE"]);
            }         
            else
            {
                return -1;
            }
        }
        else
        {
            echo "<div class\"post error\">Error to get text of a post</div>";
            return -1;
        }

    }

    /* To save a post after editing
        $text - new text of post
        $title - title of the new post
        $edit_id - ID of post which comment belongs to
        if error returns -1, else - 0
    */
    function save_post($text, $title, $edit_id)
    {
        if (empty($text) || empty($title) || empty($edit_id))
        {
            return -1;
        }

        $edit_id=htmlspecialchars($edit_id);
        $title=htmlspecialchars($title);
        $text=htmlspecialchars($text);

        $sql = "UPDATE posts set TEXT = \"".$text."\", TITLE = \"".$title."\"  where ID=".$edit_id;
        $result = $this->connect->query($sql);
        if (!$result) 
        {
            echo "<div class\"post error\">Error to update a post!</div>";
            return -1;
        }
        return 0;
    }

    /* To save a new post after
        $text - new text of post
        $title - title of the new post
        $edit_id - ID of post which comment belongs to
        if error returns -1, else - 0
    */
    function save_new_post($text, $title, $user_id)
    {
        if (empty($text) || empty($title) || empty($user_id))
        {
            return -1;
        }

        $text=htmlspecialchars($text);
        $title=htmlspecialchars($title);
        $user_id=htmlspecialchars($user_id);

        $sql = "INSERT INTO posts (ID_USER, TEXT, TITLE) values (\"".$user_id."\", \"".$text."\", \"".$title."\")";
        $result = $this->connect->query($sql);
        if (!$result) 
        {
            echo "<div class\"post error\">Error to write a new post!</div>";
            return -1;
        }
        return 0;
    }

    /* To delete a comment
        $id - ID of a comment to delete
        if error returns -1, else - 0
    */ 
    function del_post($id)
    {
        if (empty($id))
        {
            return -1;
        }
        $id=htmlspecialchars($id);

        $sql = "DELETE from posts where ID=".$id;
        $result = $this->connect->query($sql);
        if (!$result) 
        {
            echo "<div class\"post error\">Error to delete a post!</div>";
            return -1;
        }
        return 0;
    }
   
    /* To get posts of certain user
        $login_id - login of current user
        if error returns -1, else - array of value
    */ 
    function get_posts($login_id)
    {

        if (empty($login_id))
        {
            return -1;
        }

        $login_id=htmlspecialchars($login_id);

        $sql = "SELECT posts.TEXT, posts.ID, posts.TITLE, UNIX_TIMESTAMP(posts.CREATE_DATE) as CREATE_DATE, users.NAME, users.SURNAME, 
        (SELECT COUNT(*)  from comments where comments.ID_POST=posts.ID) as COMM_NUM from posts, users where posts.ID_USER = users.ID and posts.ID_USER=\"".$login_id."\"";
        $result = $this->connect->query($sql);
        if ($result)
        {
            while ($row = $result->fetch_assoc())
            {
                $posts[] = array($row["TEXT"], $row["ID"], $row["TITLE"], $row["CREATE_DATE"], $row["NAME"], $row["SURNAME"], $row["COMM_NUM"]);
            }
            $result->close();
            return $posts;          
        }
        else
        {
            echo "<div class\"post error\">Error to get information about posts</div>";
            return -1;
        }
    }

    /* To get all posts
        if error returns -1, else - array of value
    */ 
    function get_all_posts()
    {
        $sql = "SELECT posts.TEXT, posts.ID, posts.TITLE, UNIX_TIMESTAMP(posts.CREATE_DATE) as CREATE_DATE, users.NAME, users.SURNAME, 
        (SELECT COUNT(*)  from comments where comments.ID_POST=posts.ID) as COMM_NUM, posts.ID_USER from posts, users where posts.ID_USER = users.ID order by posts.CREATE_DATE desc";
        $result = $this->connect->query($sql);
        if ($result)
        {
            while ($row = $result->fetch_assoc())
            {
                $posts[] = array($row["TEXT"], $row["ID"], $row["TITLE"], $row["CREATE_DATE"], $row["NAME"], $row["SURNAME"], $row["COMM_NUM"], $row["ID_USER"]);
            }
            $result->close();
            return $posts;          
        }
        else
        {
            echo "<div class\"post error\">Error to get information about posts</div>";
            return -1;
        }
    }
}

?>