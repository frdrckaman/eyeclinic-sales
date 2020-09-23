<?php

session_start();

//initializing variables

$username="";
$email="";

$errors=array();

//connect to the database (db)

$db= mysqli_connect(' localhost','hamabebe','Beatrice187##Beatrice187##','hamabebe_signup') or die("could not connect to the database");

//register users

$username=mysqli_real_escape_string($db,$_POST["username"]);
$email=mysqli_real_escape_string($db,$_POST["email"]);
$password1=mysqli_real_escape_string($db,$_POST["password1"]);
$password2=mysqli_real_escape_string($db,$_POST["password2"]);

//form validation

if(empty($username)){array_push($errors,"username is required");}
if(empty($email)){array_push($errors,"email is required");}
if(empty($password1)){array_push($errors,"password is required");}
if($password1 != password2){array_push($errors,"passowrds do not match");}

//checking db for exixting user with the same username

$user_check_query="SELECT = FROM user WHERE username='$username' or email='$email' LIMIT 1";

$results=mysqli_query($db, $user_check_query);
$user=mysqli_fetch_assoc($results) ;

if($user){
if($user["username"]===$username){array_push($errors,"username already exist");}
    if($user["email"]===$email){array_push($errors,"This email Id has already registered a username");}

}

//register the user if no error

if(count($errors)==0){

    $password = md5($password1); //this will encrypt the password
    $query= "INSERT INTO user (username,email,password) VALUES('$username','$email','$password')";

    mysqli_query($db,$query);

$_SESSION['username']=$username;
$_SESSION['success']="you are now logged in";

header('location:index.php');


}
//login user
if(isset($_POST['login_user'])){

    $username=mysqli_real_escape_string($db, $_POST['username']);
    $password=mysqli_real_escape_string($db, $_POST['password1']);

    if(empty($username)){

        array_push($errors,"username is required");
    }
    if(empty($password)){

        array_push($errors,"password is required");
    }
    if(count($errors)==0){

        $password= md5($password);

        $query="SELECT = FROM user WHERE username= '$username' AND password='$password'";
        $results=mysqli_query($db,$query);

        if(mysqli_num_rows($results)){
            $_SESSION['username']=$username;
            $_SESSION['success']="logged in successfully";
            header('location: index.php');
        }
        else{
            array_push($errors,"wrong username/password combination.please try again");
        }
    }
}
?>
