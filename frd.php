<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
$successMessage=null;$pageError=null;$errorMessage=null;
if($user->isLoggedIn()) {
    if (Input::exists('post')) {echo'frd';
        if (Input::get('add_user')) {
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                    'min' => 3,
                ),
                'lastname' => array(
                    'required' => true,
                    'min' => 3,
                ),
                'position' => array(
                    'required' => true,
                ),
                'username' => array(
                    'required' => true,
                    'unique' => 'user'
                ),
                'email_address' => array(
                    'required' => true,
                    'unique' => 'user'
                ),
            ));
            if ($validate->passed()) {
                $salt = $random->get_rand_alphanumeric(32);
                $password = '12345678';
                switch (Input::get('position')) {
                    case 'Admin':
                        $accessLevel = 1;
                        break;
                    case 'Sales':
                        $accessLevel = 2;
                        break;
                }
                try {
                    $user->createRecord('user', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'position' => Input::get('position'),
                        'username' => Input::get('username'),
                        'password' => Hash::make($password,$salt),
                        'salt' => $salt,
                        'create_on' => date('Y-m-d'),
                        'access_level' => $accessLevel,
                        'email' => Input::get('email'),
                        'branch' => Input::get('branch'),
                        'status' => 1,
                        'last_login'=>'',
                        'power'=>0,
                        'user_id'=>1
                    ));
                    $successMessage = 'Account Created Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
}
?>
<form method="post" >
    <div class="row-form clearfix">
        <div class="col-md-3">Clinic Branch:</div>
        <div class="col-md-9">
            <select name="branch" id="branch" class="validate[required]">
                <option value="">Choose branch</option>
                <option value="1">Sinza</option>
            </select>
        </div>
    </div>

    <div class="row-form clearfix">
        <div class="col-md-3">First Name:</div>
        <div class="col-md-9">
            <input value="" class="" type="text" name="firstname" id="firstname"/>
        </div>
    </div>
    <div class="row-form clearfix">
        <div class="col-md-3">Last Name:</div>
        <div class="col-md-9">
            <input value="" class="" type="text" name="lastname" id="lastname"/>
        </div>
    </div>
    <div class="row-form clearfix">
        <div class="col-md-3">Username:</div>
        <div class="col-md-9">
            <input value="" class="" type="text" name="username" id="username"/>
        </div>
    </div>

    <div class="row-form clearfix">
        <div class="col-md-3">Position</div>
        <div class="col-md-9">
            <select name="position" id="s2_1" style="width: 100%;">
                <option value="Admin">Admin</option>
            </select>
        </div>
    </div>

    <div class="row-form clearfix">
        <div class="col-md-3">E-mail Address:</div>
        <div class="col-md-9"><input value="" class="" type="text" name="email" id="email" />  <span>Example: someone@nowhere.com</span></div>
    </div>

    <div class="footer tar">
        <input type="submit" name="add_user" value="Submit" class="btn btn-default">
    </div>

</form>
