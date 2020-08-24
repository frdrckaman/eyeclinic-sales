<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
$successMessage=null;$pageError=null;$errorMessage=null;
if($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_user')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                ),
                'lastname' => array(
                    'required' => true,
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
                        'accessLevel' => $accessLevel,
                        'email_address' => Input::get('email_address'),
                        'branch' => Input::get('branch'),
                        'status' => 1,
                        'last_login'=>'',
                        'power'=>0,
                        'count' => 0,
                        'user_id'=>$user->data()->id
                    ));
                    $successMessage = 'Account Created Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_branch')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'code' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('branch', array(
                        'name' => Input::get('name'),
                        'branch_id' => Input::get('branch_id'),
                        'code' => Input::get('code'),
                        'status' => 1,
                        'user_id'=>$user->data()->id
                    ));
                    $successMessage = 'Account Branch Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_stock')) {
            $validate = $validate->check($_POST, array(
                'brand_id' => array(
                    'required' => true,
                ),
                'quantity' => array(
                    'required' => true,
                ),
                'price' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $stocks = $override->get('frame_stock','brand_id',Input::get('brand_id'));
                    if($stocks){
                        $qnt= $stocks[0]['quantity'] + Input::get('quantity');
                        $user->updateRecord('frame_stock',array('quantity'=>$qnt),$stocks[0]['id']);
                        $successMessage = 'Stock Added Successful';
                    }else{
                        $user->createRecord('frame_stock', array(
                            'quantity' => Input::get('quantity'),
                            'brand_id' => Input::get('brand_id'),
                            'price' => Input::get('price'),
                            'status' => 1,
                            'user_id'=>$user->data()->id
                        ));
                        $successMessage = 'Stock Added Successful';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('assign_stock')) {
            $validate = $validate->check($_POST, array(
                'brand_id' => array(
                    'required' => true,
                ),
                'user_id' => array(
                    'required' => true,
                ),
                'quantity' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $stocks = $override->getNews('assigned_stock','brand_id',Input::get('brand_id'), 'user_id', Input::get('user_id'));
                    if($stocks){
                        $qnt= $stocks[0]['quantity'] + Input::get('quantity');
                        $user->updateRecord('assigned_stock',array('quantity'=>$qnt),$stocks[0]['id']);
                        $successMessage = 'Stock Assigned Successful';
                    }else{
                        $user->createRecord('assigned_stock', array(
                            'user_id' => Input::get('user_id'),
                            'brand_id' => Input::get('brand_id'),
                            'quantity' => Input::get('quantity'),
                            'status' => 1,
                            'admin_id'=>$user->data()->id
                        ));
                        $successMessage = 'Stock Assigned Successful';
                    }
                    $pStock = $override->get('frame_stock','brand_id', Input::get('brand_id'));
                    $n_st = $pStock[0]['quantity'] - Input::get('quantity');
                    $user->updateRecord('frame_stock',array('quantity'=>$n_st),$pStock[0]['id']);
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
}else{
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard - OnaEyeCare</title>
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
                <li class="active">Dashboard</li>
            </ul>

            <ul class="buttons">
                <li>
                    <a href="#" class="link_bcPopupList"><span class="glyphicon glyphicon-user"></span><span class="text">Users list</span></a>

                    <div id="bcPopupList" class="popup">
                        <div class="head clearfix">
                            <div class="arrow"></div>
                            <span class="isw-users"></span>
                            <span class="name">List users</span>
                        </div>
                        <div class="body-fluid users">

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/aqvatarius_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Aqvatarius</a>
                                    <span>online</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/olga_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Olga</a>
                                    <span>online</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/alexey_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Alexey</a>
                                    <span>online</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/dmitry_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Dmitry</a>
                                    <span>online</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/helen_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Helen</a>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/alexander_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Alexander</a>
                                </div>
                            </div>

                        </div>
                        <div class="footer">
                            <button class="btn btn-default" type="button">Add new</button>
                            <button class="btn btn-danger link_bcPopupList" type="button">Close</button>
                        </div>
                    </div>

                </li>
                <li>
                    <a href="#" class="link_bcPopupSearch"><span class="glyphicon glyphicon-search"></span><span class="text">Search</span></a>

                    <div id="bcPopupSearch" class="popup">
                        <div class="head clearfix">
                            <div class="arrow"></div>
                            <span class="isw-zoom"></span>
                            <span class="name">Search</span>
                        </div>
                        <div class="body search">
                            <input type="text" placeholder="Some text for search..." name="search"/>
                        </div>
                        <div class="footer">
                            <button class="btn btn-default" type="button">Search</button>
                            <button class="btn btn-danger link_bcPopupSearch" type="button">Close</button>
                        </div>
                    </div>
                </li>
            </ul>

        </div>

        <div class="workplace">
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
            <div class="row">
                <?php if($_GET['id'] == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add User</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Clinic Branch:</div>
                                    <div class="col-md-9">
                                        <select name="branch" id="branch" class="validate[required]">
                                            <option value="">Choose branch</option>
                                            <?php foreach ($override->getData('branch') as $branch){?>
                                                <option value="<?=$branch['id']?>"><?=$branch['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">First Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="firstname" id="firstname"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Last Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="lastname" id="lastname"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Username:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="username" id="username"/>
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
                                    <div class="col-md-9"><input value="" class="validate[required,custom[email]]" type="text" name="email_address" id="email" />  <span>Example: someone@nowhere.com</span></div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_user" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 2){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Branch</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Branch Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="name" id="name"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Branch ID:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="branch_id" id="branchID"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Short Code:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="code" id="code"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_branch" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 3){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Stock</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Brand</div>
                                    <div class="col-md-9">
                                        <select name="brand_id" id="s2_1" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getData('frame_brand') as $brand){?>
                                                <option value="<?=$brand['id']?>"><?=$brand['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Quantity:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="quantity" id="quantity"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Price per Frame:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="price" id="price"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_stock" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 4){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Assign Stock</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Staff</div>
                                    <div class="col-md-9">
                                        <select name="user_id" id="s2_1" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getData('user') as $brand){?>
                                                <option value="<?=$brand['id']?>"><?=$brand['firstname'].' '.$brand['lastname']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Brand</div>
                                    <div class="col-md-9">
                                        <select name="brand_id" id="s2_2" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getData('frame_brand') as $brand){?>
                                                <option value="<?=$brand['id']?>"><?=$brand['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Quantity:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="quantity" id="quantity"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="assign_stock" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }?>
                <div class="dr"><span></span></div>
            </div>

        </div>
    </div>
</div>
</body>

</html>

