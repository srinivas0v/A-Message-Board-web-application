<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>

<?php

function auth(){

    $uname = $_POST['uname'];
    $pswd = ($_POST['psw']);
    $pswd = md5($pswd);
    if(!empty($uname) && !empty($pswd))
  {
try {
    $dbpdo = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $query=$dbpdo->prepare('SELECT * FROM USERS WHERE `username` = :username;');
    $query->bindValue(':username', $uname, PDO::PARAM_STR);
    $query->execute();
    if($query->rowcount() > 0)
    {
        foreach($query ->fetchall() as $result)
        if($pswd == $result['password'])
        {
            $_SESSION['user'] = $uname;
            header('Location: board.php');
        }
        else
        {
            echo "<p align='center'> The Username or Password didn't match! Please try again...</p>";
        }
    }
    else
    {
        echo "<p align='center'>The Username doesn't Exists</p>";
    }
        
}
catch(PDOException $e)
{
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
$dbpdo = null;
}

}

?>

<style>
form {
    border: 3px solid #f1f1f1;
}

input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
}

button:hover {
    opacity: 0.8;
}

.container {
    padding: 16px;
}

span.psw {
    float: right;
    padding-top: 16px;
}
</style>
<body>
    <?php
if(isset($_POST['username']))
{
    authorise();
}
?>

<h2>Login Form</h2>

<form action="login.php" method = "POST">

  <div class="container">
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
        
    <button type="submit">Login</button>
  </div>
</form>
<?php
if(isset($_POST['uname']))
{
    auth();
}
?>
</body>
</html>
