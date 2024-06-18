<?php 
include_once("db_connect_class.php");

/* Class to manage comments in database */
class comment extends db_connect
{
    function __construct()
    {
        parent::__construct();
    }

    /* To get information about a comments
        $post_id - ID of post which comment belongs to
        if error returns -1, else - array of value
    */
    function get_comments($post_id)
    {

        $post_id=htmlspecialchars($post_id);

        if (empty($post_id))
        {
            return -1;
        }

        $sql = "SELECT comments.TEXT, comments.ID, UNIX_TIMESTAMP(comments.CREATE_DATE) as CREATE_DATE, 
        users.NAME, users.SURNAME, comments.ID_USER from comments, users where comments.ID_USER=users.ID and comments.ID_POST=\"".$post_id."\" order by comments.CREATE_DATE desc";
        $result = $this->connect->query($sql);
        if ($result)
        {
            while ($row = $result->fetch_assoc())
            {
                $posts[] = array($row["TEXT"], $row["ID"], $row["CREATE_DATE"], $row["NAME"], $row["SURNAME"] , $row["ID_USER"]);
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

    /* To save information about a comment
        $text - new version of text to save
        $edit_id - ID of commet which to save
        if error returns -1, else - 0
    */
    function save_comment($text, $edit_id)
    {
        if (empty($edit_id) || empty($text))
        {
            return -1;
        }

        $text=htmlspecialchars($text);
        $edit_id=htmlspecialchars($edit_id);

        $sql = "UPDATE comments set TEXT = \"".$text."\" where ID=".$edit_id;
        $result = $this->connect->query($sql);
        if (!$result) 
        {
            echo "<div class\"post error\">Error to update a comment!</div>";
            return -1;
        }
        return 0;
    }
    
    /* To add a new comment
        $text - new version of text to save
        $post_id - ID post which comment belongs to
        $user_id - author of operation
        if error returns -1, else - 0
    */
    function save_new_comment($text, $post_id, $user_id)
    {
        if (empty($text) || empty($post_id) || empty($user_id))        {
            return -1;
        }

        $text=htmlspecialchars($text);
        $post_id=htmlspecialchars($post_id);
        $user_id=htmlspecialchars($user_id);

        $sql = "INSERT INTO  comments (ID_USER, TEXT, ID_POST) values (\"".$user_id."\", \"".$text."\", \"".$post_id."\")";
        $result = $this->connect->query($sql);
        if (!$result) 
        {
            echo "<div class\"post error\">Error to write a new comment!</div>";
            return -1;
        }
        return 0;
    }

    /* To delete a comment
        $id - ID of a comment to delete
        if error returns -1, else - 0
    */
    function del_comment($id)
    {
        if (empty($id))
        {
            return -1;
        }

        $id=htmlspecialchars($id);

        $sql = "DELETE from comments where ID=".$id;
        $result = $this->connect->query($sql);
        if (!$result) 
        {
            echo "<div class\"post error\">Error to delete a comments!</div>";
            return -1;
        }
        return 0;
    }

}
?>
