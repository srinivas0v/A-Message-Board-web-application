<html>
<head><title>Message Board</title></head>
<style>
.button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    margin: 6px 0;
    border: none;
    cursor: pointer;
    width: 100%;
     position:relative;
    bottom:0;
    right:0;
}
form {
    border: 3px solid #f1f1f1;
}

.button1 {
  display: inline-block;
  padding: 15px 25px;
  font-size: 20px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
  color: #fff;
  background-color: #4CAF50;
  border: none;
  border-radius: 12px;
  box-shadow: 0 9px #999;
}

.container {
    padding: 16px;
}
</style>
<body>

<?php
session_start();
try {
    $dbpdo = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));    
}
catch(PDOException $e)
{
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
<?php
function post()
  {
    $id = uniqid();
    $name = $_SESSION['user'];
    $datet = date("Y-m-d H:i:s");
    $post = $_POST['post'];
    $reply = NULL;
    if(!empty($post) && empty($reply)){
try {

    $dbpdo = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));    
    $dbpdo->beginTransaction();
    $query2=$dbpdo->prepare('INSERT INTO `posts` (`id`, `replyto`, `postedby`, `datetime`, `message`) VALUES (:id, NULL,:name,:datet,:post);');
    $query2->bindValue(':id', $id, PDO::PARAM_STR);
    $query2->bindValue(':name', $name, PDO::PARAM_STR);
    $query2->bindValue(':datet', $datet, PDO::PARAM_STR);
    $query2->bindValue(':post', $post, PDO::PARAM_STR);
    $query2->execute();
    $dbpdo->commit();
        
}
catch(PDOException $e)
{
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
$dbpdo = null;
}}
?>
<?php
function reply()
  {
    $id = uniqid();
    $name = $_SESSION['user'];
    $datet = date("Y-m-d H:i:s");
    $post = $_POST['post'];
    $reply = $_POST['replymsg'];
    //echo  $_POST['replymsg'];
    if(!empty($post) && !empty($reply)){
try {

    $dbpdo = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));    
    $dbpdo->beginTransaction();
    $query3=$dbpdo->prepare('INSERT INTO `posts` (`id`, `replyto`, `postedby`, `datetime`, `message`) VALUES (:id, :reply,:name,:datet,:post);');
    $query3->bindValue(':id', $id, PDO::PARAM_STR);
    $query3->bindValue(':reply', $reply, PDO::PARAM_STR);
    $query3->bindValue(':name', $name, PDO::PARAM_STR);
    $query3->bindValue(':datet', $datet, PDO::PARAM_STR);
    $query3->bindValue(':post', $post, PDO::PARAM_STR);
    $query3->execute();
    $dbpdo->commit();
        
}
catch(PDOException $e)
{
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
$dbpdo = null;
}}
?>

<?php
function userLogout(){
     session_unset(); 
     session_destroy(); 
     Header("Location: login.php");
}

?>

<?php
function display()
  {
    echo "<h3>Message Board</h3>";
try {
    $dbpdo = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));    
    $query1=$dbpdo->prepare('SELECT posts.id,posts.postedby,users.fullname,posts.datetime,posts.replyto,posts.message FROM posts join users on users.username = posts.postedby ORDER BY UNIX_TIMESTAMP(`datetime`) DESC');
    //$query->bindValue(':username', $uname, PDO::PARAM_STR);
    $query1->execute();
    if($query1->rowcount() > 0)
    {
        foreach($query1 ->fetchall() as $result)
          //echo $result[1];
        echo "<p>Message ID: ".$result[0]."</br> postedby:  ".$result[1]."</br> fullname:  ".$result[2]."</br> date:  ".$result[3]."</br> reply to:  ".$result[4]."</br>post:  ".$result[5]."</p>";
        //echo "<p>Posted By: ".$result[2]."</p>";
       // echo "<p>Date: ".$result[3]."</p>";
        //echo "<p>Post: ".$result[4]."</p>";
    }
    else
    {
        echo "<p align='center'>no posts</p>";
    }
        
}
catch(PDOException $e)
{
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
//display();
$dbpdo = null;
}

?>
<form action='board.php' method="POST">
  <div class="container">
  Message: <textarea name="post" rows="5" cols="40"></textarea>
  <button name = "newpost" value="new" type="submit" class='button1'>POST</button>
  <br><br>
  Reply to: <input type="text" name="replymsg" >
  <button name="msgreply" value="reply" type="submit" class='button1' formaction="board.php?replyto=<?php echo $_REQUEST['replymsg'];?>">REPLY</button>
  <br><br><br><br>
    <button type="submit" formaction = "board.php?logout=1" class='button'>Logout</button>
  </div>


</form>
 <?php
//echo "here";

  
if(isset($_POST['newpost'])){
  //echo "post";
  post();
  //display();
}
if(isset($_POST['msgreply'])){
    //echo "reply";
    reply();
    //display();
  }

  if(isset($_GET['logout']))
{
  userLogout();
}
if(!isset($_SESSION["user"]))
{
    //redirect back to login page or display message
    echo "Please login again";
    userLogout();
    echo "Please login again";
}
else
display();


?>



</body>
</html>
