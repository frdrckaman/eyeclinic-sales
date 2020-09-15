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
                'batch_id' => array(
                    'required' => true,
                ),
                'user_id' => array(
                    'required' => true,
                ),
                'quantity' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {$nw_st=0;
                $batch=$override->selectData('stock_batch','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'),'status',1);
                $stocks_batch=$override->getSumV3('assigned_stock','quantity','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'),'status',1);
                $nw_st = Input::get('quantity') + $stocks_batch[0]['SUM(quantity)'];
                if($nw_st <= $batch[0]['quantity']){
                    try {
                        $stocks = $override->selectData('assigned_stock','brand_id', Input::get('brand_id'),'batch_id',Input::get('batch_id'),'user_id', Input::get('user_id'));
                        if($stocks){
                            $qnt= $stocks[0]['quantity'] + Input::get('quantity');
                            $user->updateRecord('assigned_stock',array('quantity'=>$qnt),$stocks[0]['id']);
                            $successMessage = 'Stock Assigned Successful';
                        }else{
                            $user->createRecord('assigned_stock', array(
                                'user_id' => Input::get('user_id'),
                                'batch_id' => Input::get('batch_id'),
                                'brand_id' => Input::get('brand_id'),
                                'quantity' => Input::get('quantity'),
                                'status' => 1,
                                'admin_id'=>$user->data()->id
                            ));
                            $successMessage = 'Stock Assigned Successful';
                        }
                        $user->createRecord('assigned_stock_rec', array(
                            'user_id' => Input::get('user_id'),
                            'batch_id' => Input::get('batch_id'),
                            'brand_id' => Input::get('brand_id'),
                            'quantity' => Input::get('quantity'),
                            'create_on' => date('Y-m-d'),
                            'status' => 1,
                            'admin_id'=>$user->data()->id
                        ));
//                        $pStock = $override->get('frame_stock','brand_id', Input::get('brand_id'));
//                        $n_st = $pStock[0]['quantity'] - Input::get('quantity');
//                        $user->updateRecord('frame_stock',array('quantity'=>$n_st),$pStock[0]['id']);
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{
                    $errorMessage='Insufficient Amount, it must be less or equal to stock batch amount';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_batch')){
            $validate = $validate->check($_POST, array(
                'batch' => array(
                    'required' => true,
                ),
                'batch_id' => array(
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
                    $user->createRecord('batch', array(
                        'name' => Input::get('batch'),
                        'batch_id' => Input::get('batch_id'),
                        'quantity' => Input::get('quantity'),
                        'cost' => Input::get('price'),
                        'create_date' => date('Y-m-d'),
                        'status' => 1,
                        'user_id'=>$user->data()->id
                    ));
                    $successMessage = 'Batch Successful Added';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_batch_stock')){
            $validate = $validate->check($_POST, array(
                'brand_id' => array(
                    'required' => true,
                ),
                'batch_id' => array(
                    'required' => true,
                ),
                'quantity' => array(
                    'required' => true,
                ),
                'price' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {$nw_st=0;
                $batch=$override->getNews('batch','id',Input::get('batch_id'),'status',1);
                $stocks_batch=$override->getSumV('stock_batch','quantity','batch_id',Input::get('batch_id'));
                $nw_st = Input::get('quantity') + $stocks_batch[0]['SUM(quantity)'];
                if($nw_st <= $batch[0]['quantity']){
                    try {
                        $user->createRecord('stock_batch', array(
                            'batch_id' => Input::get('batch_id'),
                            'brand_id' => Input::get('brand_id'),
                            'quantity' => Input::get('quantity'),
                            'cost' => Input::get('price'),
                            'create_on' => date('Y-m-d'),
                            'status' => 1,
                            'user_id'=>$user->data()->id
                        ));
                        $user->createRecord('stock_batch_rec', array(
                            'batch_id' => Input::get('batch_id'),
                            'brand_id' => Input::get('brand_id'),
                            'quantity' => Input::get('quantity'),
                            'cost' => Input::get('price'),
                            'create_on' => date('Y-m-d'),
                            'status' => 1,
                            'user_id'=>$user->data()->id
                        ));
                        $successMessage = 'Stock Batch Successful Added';

                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{
                    $errorMessage='Insufficient Amount, it must be less or equal to stock batch amount111';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('frame_sale')){
            $validate = $validate->check($_POST, array(
                'batch_id' => array(
                    'required' => true,
                ),
                'brand_id' => array(
                    'required' => true,
                ),
                'client_name' => array(
                    'required' => true,
                ),
                'client_phone' => array(
                    'required' => true,
                ),
                'quantity' => array(
                    'required' => true,
                ),
                'pay_type' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {$avl=0;$sld=0;
                $assigned_stock=$override->selectData('assigned_stock','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'),'user_id',$user->data()->id);
                $stocks_sold=$override->getSumV3('frame_sale','quantity','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'),'user_id',$user->data()->id);
                $avl=$assigned_stock[0]['quantity'] - $stocks_sold[0]['SUM(quantity)'];
                $invoice = $random->get_rand_numbers(6);
                //$user->updateRecord('frame_sale',array('invoice'=>$invoice),3);
                $checkInvNo = $override->get('frame_sale','invoice',$invoice);
                while($override->unique('frame_sale','invoice',$invoice) == true){
                    $invoice = $random->get_rand_numbers(6);
                }
                if(Input::get('quantity') <= $avl){
                    try {
                        $user->createRecord('frame_sale', array(
                            'client_name' => Input::get('client_name'),
                            'client_phone' => Input::get('client_phone'),
                            'batch_id' => Input::get('batch_id'),
                            'brand_id' => Input::get('brand_id'),
                            'quantity' => Input::get('quantity'),
                            'pay_type' => Input::get('pay_type'),
                            'sale_date' => date('Y-m-d'),
                            'invoice' => $invoice,
                            'note' => Input::get('note'),
                            'status' => 1,
                            'user_id'=>$user->data()->id
                        ));
                        $successMessage = 'Frame Successful Sold';

                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{
                    $errorMessage='Insufficient Amount, it must be less or equal to stock batch amount';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('search')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'start_date' => array(
                    'required' => true,
                ),
                'end_date' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                $star=Input::get('start_date');
                $end=Input::get('end_date');
                $url='info.php?id=10&s='.$star.'&e='.$end;
                Redirect::to($url);
            }else {
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
                <li class="active">Add Info</li>
            </ul>
            <?php include 'pageInfo.php'?>
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
                                            <option value="1">Admin</option>
                                            <option value="2">Sales Personnel</option>
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
                            <h1>Add Frame</h1>
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
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" id="s2_3" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->get('batch','status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
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
                <?php }elseif ($_GET['id'] == 5){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Stock Batch</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" id="s2_1" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->get('batch','status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
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
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Price per Frame:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="price" id="price"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_batch_stock" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 6){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Batch</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Batch Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="batch" id="batch"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Batch ID:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="batch_id" id="batch_id"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Quantity:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="quantity" id="quantity"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Batch Total Cost:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="price" id="price"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_batch" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 7){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Sales Frame</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" id="s2_1" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->get('batch','status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
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
                                    <div class="col-md-3">Client Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="client_name" id="client_name"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Client Phone:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="client_phone" id="client_phone"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Payment Type</div>
                                    <div class="col-md-9">
                                        <select name="pay_type" id="s2_2" style="width: 100%;" required>
                                            <option value="">Select Method</option>
                                            <option value="1">Cash</option>
                                            <option value="2">Credit</option>
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
                                    <div class="col-md-3">Note:</div>
                                    <div class="col-md-9"><textarea name="note" placeholder="Sales notes..."></textarea></div>
                                </div>
                                <div class="footer tar">
                                    <input type="submit" name="frame_sale" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 8){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Search Report</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
<!--                                <div class="row-form clearfix">-->
<!--                                    <div class="col-md-3">Report Type</div>-->
<!--                                    <div class="col-md-9">-->
<!--                                        <select name="batch_id" id="s2_1" style="width: 100%;" required>-->
<!--                                            <option value="">Select</option>-->
<!---->
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Start Date:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required,custom[date]]" type="text" name="start_date" id="date"/>
                                        <span>Example: 2010-12-01</span>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">End Date:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required,custom[date]]" type="text" name="end_date" id="date1"/>
                                        <span>Example: 2010-12-01</span>
                                    </div>
                                </div>
                                <div class="footer tar">
                                    <input type="submit" name="search" value="Search" class="btn btn-default">
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
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>

</html>

