<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage=null;$pageError=null;$errorMessage=null;
$users = $override->getData('user');
if($user->isLoggedIn()) {
    if(Input::exists('post')){
        $validate = new validate();
        $validate = $validate->check($_POST, array(
            'new_password' => array(
                'required' => true,
                'min' => 6,
            ),
            'retype_password' => array(
                'required' => true,
                'matches' => 'new_password'
            )
        ));
        if ($validate->passed()) {
            $salt = $random->get_rand_alphanumeric(32);
            try {
                $user->updateRecord('user',array(
                    'password' => Hash::make(Input::get('new_password'), $salt),
                    'salt' => $salt
                ),$user->data()->id);
            } catch (Exception $e) {
            }
            $successMessage = 'Password changed successfully';
        } else {
            $pageError = $validate->errors();
        }
    }
}else{
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile - OnaEyeCare</title>
    <?php include "head.php";?>
</head>
<body>
<div class="wrapper">
    <?php include 'topbar.php'?>
    <?php include 'menu.php'?>
    <div class="content">
        <div class="breadLine">
            <ul class="breadcrumb">
                <li><a href="#">Simple Admin</a> <span class="divider">></span></li>
                <li><a href="#">User info</a> <span class="divider">></span></li>
                <li class="active">OnaEyeCare</li>
            </ul>
            <?php include 'pageInfo.php'?>
        </div>

        <div class="workplace">

            <div class="page-header">
                <h1>User info <small>OnaEyeCare</small></h1>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="ucard clearfix">
                                <div class="right">
                                    <h4><?=$user->data()->firstname?></h4>
                                    <div class="image">
                                        <a href="#"><img src="img/users/no-image.jpg" class="img-thumbnail"></a>
                                    </div>
                                    <ul class="control">
                                        <li><span class="glyphicon glyphicon-pencil"></span> <a href="#">Edit</a></li>
                                        <li><span class="glyphicon glyphicon-user"></span> <a href="#">Status</a></li>
                                        <li><span class="glyphicon glyphicon-info-sign"></span> <a href="#">Information</a></li>
                                        <li><span class="glyphicon glyphicon-envelope"></span> <a href="#">Send message</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="block-fluid ucard">

                                <div class="info">
                                    <ul class="rows">
                                        <li class="heading">User info</li>
                                        <li>
                                            <div class="title">Name:</div>
                                            <div class="text"><?=$user->data()->firstname?></div>
                                        </li>
                                        <li>
                                            <div class="title">Surname:</div>
                                            <div class="text"><?=$user->data()->lastname?></div>
                                        </li>
                                        <li>
                                            <div class="title">Email:</div>
                                            <div class="text"><?=$user->data()->email_address?></div>
                                        </li>
                                        <li>
                                            <div class="title">Clinic Branch:</div>
                                            <div class="text"><?=$override->get('branch','id',$user->data()->branch)[0]['name']?></div>
                                        </li>
                                        <li>
                                            <div class="title">Position:</div>
                                            <div class="text"><?php if($user->data()->position == 1){echo'Admin';}elseif ($user->data()->position == 2){echo'Sales Personnel';}?></div>
                                        </li>
                                        <li>
                                            <div class="title">Last Login:</div>
                                            <div class="text"><?=$user->data()->last_login?></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="head clearfix">
                        <div class="isw-ok"></div>
                        <h1>Change Password</h1>
                    </div>
                    <div class="block-fluid">
                        <?php if($errorMessage){?>
                            <div class="alert alert-danger">
                                <h4>Error!</h4>
                                <?=$errorMessage?>
                            </div>
                        <?php }elseif($pageError){?>
                            <div class="alert alert-danger">
                                <h4>Error!</h4>
                                <?php foreach($pageError as $error){echo $error.' , ';}?>
                            </div>
                        <?php }elseif($successMessage){?>
                            <div class="alert alert-success">
                                <h4>Success!</h4>
                                <?=$successMessage?>
                            </div>
                        <?php }?>
                        <form id="validation" method="post" >
                            <div class="row-form clearfix">
                                <div class="col-md-3">Old Password:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="password" name="old_password" id="pass1"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">New Password:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="password" name="new_password" id="pass2"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">Re-type Password:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="password" name="retype_password" id="pass3"/>
                                </div>
                            </div>
                            <div class="footer tar">
                                <input type="submit" name="pwd" value="Change Password" class="btn btn-warning">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="dr"><span></span></div>
        </div>

    </div>
</div>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>

</html>
