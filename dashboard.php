<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage=null;$pageError=null;$errorMessage=null;
$users = $override->getData('user');
if($user->isLoggedIn()) {

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
                <li><a href="#">Dashboard</a> <span class="divider">></span></li>
            </ul>

            <?php include 'pageInfo.php'?>

        </div>

        <div class="workplace">

            <div class="row">

                <div class="col-md-4">

                    <div class="wBlock red clearfix">
                        <div class="dSpace">
                            <h3>Total Stock</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                            <span class="number"><?=$total?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock green clearfix">
                        <div class="dSpace">
                            <h3>Assigned Stock</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--5,10,15,20,23,21,25,20,15,10,25,20,10--></span>
                            <span class="number"><?=$assigned?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock blue clearfix">
                        <div class="dSpace">
                            <h3>Sold</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number">0</span>
                        </div>

                    </div>

                </div>
            </div>

            <div class="dr"><span></span></div>

            <div class="dr"><span></span></div>

            <div class="row">

                <?php if($user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Simple table</h1>
                            <ul class="buttons">
                                <li><a href="#" class="isw-download"></a></li>
                                <li><a href="#" class="isw-attachment"></a></li>
                                <li>
                                    <a href="#" class="isw-settings"></a>
                                    <ul class="dd-list">
                                        <li><a href="#"><span class="isw-plus"></span> New document</a></li>
                                        <li><a href="#"><span class="isw-edit"></span> Edit</a></li>
                                        <li><a href="#"><span class="isw-delete"></span> Delete</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkall"/></th>
                                    <th width="25%">Name</th>
                                    <th width="25%">Sold</th>
                                    <th width="25%">Remained</th>
                                    <th width="25%">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('assigned_stock') as $aStock){
                                    $staff=$override->get('user','id',$aStock['user_id'])?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></td>
                                        <td>0</td>
                                        <td><?=$aStock['quantity']?></td>
                                        <td><?=$aStock['quantity']?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($user->data()->position == 2){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>My Sales Report</h1>
                            <ul class="buttons">
                                <li><a href="#" class="isw-download"></a></li>
                                <li><a href="#" class="isw-attachment"></a></li>
                                <li>
                                    <a href="#" class="isw-settings"></a>
                                    <ul class="dd-list">
                                        <li><a href="#"><span class="isw-plus"></span> New document</a></li>
                                        <li><a href="#"><span class="isw-edit"></span> Edit</a></li>
                                        <li><a href="#"><span class="isw-delete"></span> Delete</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="15%">Customer Name</th>
                                    <th width="10%">Invoice</th>
                                    <th width="10%">Batch</th>
                                    <th width="10%">Brand</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Issued Date</th>
                                    <th width="20"> Note</th>
                                    <th width="15%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('frame_sale','user_id',$user->data()->id) as $sale){
                                    $brand=$override->get('frame_brand','id',$sale['brand_id']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$sale['client_name']?></a></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><?=$user->data()->firstname.' '.$user->data()->lastname?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }?>

            </div>

            <div class="dr"><span></span></div>

        </div>

    </div>
</div>
</body>

</html>
