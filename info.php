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
        if(Input::get('d_frame_b')){
            $user->deleteRecord('stock_batch','id', Input::get('id'));
            $successMessage = 'Frame Batch Deleted Successful';
        }elseif (Input::get('d_lens_l')){
            $user->deleteRecord('stock_batch_lens','id', Input::get('id'));
            $successMessage = 'Frame Batch Deleted Successful';
        }
    }
}else{
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Info | OnaEyeCare </title>
    <?php include "head.php";?>
</head>
<body>
<div class="wrapper">

    <?php include 'topbar.php'?>
    <?php include 'menu.php'?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Info</a> <span class="divider">></span></li>
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
                <?php if($_GET['id'] == 1 && $user->data()->position == 1){?>
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
                                <?php foreach ($override->getDataTable('assigned_stock','user_id') as $aStock){
                                    $staff=$override->get('user','id',$aStock['user_id'])?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td><a href="info.php?id=2&uid=<?=$staff[0]['id']?>"> <?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a></td>
                                        <td>0</td>
                                        <td><?=$aStock['quantity']?></td>
                                        <td><?php print_r($override->getSumV('assigned_stock','quantity','user_id',$staff[0]['id'])[0]['SUM(quantity)'])?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } elseif ($_GET['id'] == 2 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Frame Batch Summary</h1>
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
                                    <th width="20%">Sold</th>
                                    <th width="20%">Remained</th>
                                    <th width="20%">Total</th>
                                    <th width="15%">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $sld=0;$batches = $override->getNoRepeat('assigned_stock','batch_id', 'user_id',$_GET['uid']);
                                foreach ($batches as $batch){
                                    $sold = $override->getSumV('frame_sale','quantity','batch_id',$batch['batch_id']);
                                    $staff=$override->get('user','id',$_GET['uid']);
                                    $batch_name = $override->get('batch','id',$batch['batch_id'])[0]['name'];
                                    $aStock=$override->get('assigned_stock','batch_id',$batch['batch_id']);
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td><a href="#"> <?=$batch_name?></a></td>
                                        <td><?php if($sold[0]['SUM(quantity)']){$sld=$sold[0]['SUM(quantity)'];echo $sold[0]['SUM(quantity)'];}else{echo 0;}?></td>
                                        <td><?=($aStock[0]['quantity']-$sld)?></td>
                                        <td><?=$aStock[0]['quantity']?></td>
                                        <td><a href="info.php?id=3&ty=f&uid=<?=$staff[0]['id']?>&bid=<?=$batch['batch_id']?>">Details</a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Lens Batch Summary</h1>
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
                                    <th width="20%">Sold</th>
                                    <th width="20%">Remained</th>
                                    <th width="20%">Total</th>
                                    <th width="15%">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $sld=0;$batches = $override->getNoRepeat('assigned_stock_lens','batch_id', 'user_id',$_GET['uid']);
                                foreach ($batches as $batch){
                                    $sold = $override->getSumV('lens_sale','quantity','batch_id',$batch['batch_id']);
                                    $staff=$override->get('user','id',$_GET['uid']);
                                    $batch_name = $override->get('batch','id',$batch['batch_id'])[0]['name'];
                                    $aStock=$override->get('assigned_stock_lens','batch_id',$batch['batch_id']);
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td><a href="#"> <?=$batch_name?></a></td>
                                        <td><?php if($sold[0]['SUM(quantity)']){$sld=$sold[0]['SUM(quantity)'];echo $sold[0]['SUM(quantity)'];}else{echo 0;}?></td>
                                        <td><?=($aStock[0]['quantity']-$sld)?></td>
                                        <td><?=$aStock[0]['quantity']?></td>
                                        <td><a href="info.php?id=3&ty=l&uid=<?=$staff[0]['id']?>&bid=<?=$batch['batch_id']?>">Details</a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 3 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <?php if($_GET['ty'] == 'f'){?>
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Frame Batch Details</h1>
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
                                        <th width="15%">Brand</th>
                                        <th width="15%">Batch</th>
                                        <th width="10%">No. Sold by Credit</th>
                                        <th width="5%">No. Sold in Cash</th>
                                        <th width="5%">No. Total Sold</th>
                                        <th width="5%">Stock Remained</th>
                                        <th width="5%">Total Stock Given</th>
                                        <th width="10%">Cash Sales</th>
                                        <th width="10%">Credit Sales</th>
                                        <th width="10%">Total Sales Amount</th>
                                        <th width="10%">Total Expect Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($override->getNews('assigned_stock','user_id',$_GET['uid'],'batch_id',$_GET['bid']) as $aStock){
                                        $brand=$override->get('frame_brand','id',$aStock['brand_id']);
                                        $batch=$override->get('batch','id',$aStock['batch_id']);
                                        $sold = $override->getSumV('frame_sale','quantity','batch_id',$aStock['batch_id']);
                                        $payCr=0;$payC=0;$price=0;$cCost=0;$crCost=0;$tSales=0;$tExpCost=0;$payC=0;
                                        $sld = $override->getNews('frame_sale','batch_id',$_GET['bid'],'brand_id',$brand[0]['id']);
                                        $payC=$override->getSumV3('frame_sale','quantity','batch_id',$aStock['batch_id'],'brand_id',$brand[0]['id'],'pay_type',1)[0]['SUM(quantity)'];
                                        $payCr=$override->getSumV3('frame_sale','quantity','batch_id',$aStock['batch_id'],'brand_id',$brand[0]['id'],'pay_type',2)[0]['SUM(quantity)'];
                                        $price=$override->getNews('stock_batch','batch_id',$_GET['bid'],'brand_id',$brand[0]['id']);
                                        $cCost=$payC*$price[0]['cost'];
                                        $crCost=$payCr*$price[0]['cost'];
                                        $tSales=$crCost+$cCost;
                                        $tExpCost=$aStock['quantity']*$price[0]['cost'];
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox"/></td>
                                            <td><a href="#"> <?=$brand[0]['name']?></a></td>
                                            <td><?=$batch[0]['name']?></td>
                                            <td><?=$payCr?></td>
                                            <td><?=$payC?></td>
                                            <td><?=$sold[0]['SUM(quantity)']?></td>
                                            <td><?=$aStock['quantity']-$sold[0]['SUM(quantity)']?></td>
                                            <td><?=$aStock['quantity']?></td>
                                            <td><?=number_format($cCost)?></td>
                                            <td><?=number_format($crCost)?></td>
                                            <td><?=number_format($tSales)?></td>
                                            <td><?=number_format($tExpCost)?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        <?php }elseif ($_GET['ty'] == 'l'){?>
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Lens Batch Details</h1>
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
                                        <th width="15%">Lens Type</th>
                                        <th width="15%">Batch</th>
                                        <th width="10%">No. Sold by Credit</th>
                                        <th width="5%">No. Sold in Cash</th>
                                        <th width="5%">No. Total Sold</th>
                                        <th width="5%">Stock Remained</th>
                                        <th width="5%">Total Stock Given</th>
                                        <th width="10%">Cash Sales</th>
                                        <th width="10%">Credit Sales</th>
                                        <th width="10%">Total Sales Amount</th>
                                        <th width="10%">Total Expect Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($override->getNews('assigned_stock_lens','user_id',$_GET['uid'],'batch_id',$_GET['bid']) as $aStock){
                                        $brand=$override->get('lens_type','id',$aStock['lens_type'])[0];
                                        $batch=$override->get('batch','id',$aStock['batch_id']);
                                        $sold = $override->getSumV('lens_sale','quantity','batch_id',$aStock['batch_id']);
                                        $payCr=0;$payC=0;$price=0;$cCost=0;$crCost=0;$tSales=0;$tExpCost=0;$payC=0;
                                        $sld = $override->selectData4('lens_sale','batch_id',$_GET['bid'],'lens_type',$brand['id'],'lens_cat',$aStock['lens_cat'],'lens_power',$aStock['lens_power']);
                                        $payC=$override->getSumV4('lens_sale','quantity','batch_id',$aStock['batch_id'],'lens_type',$brand['id'],'lens_cat',$aStock['lens_cat'],'lens_power',$aStock['lens_power'])[0]['SUM(quantity)'];
                                        $payCr=$override->getSumV4('lens_sale','quantity','batch_id',$aStock['batch_id'],'lens_type',$brand['id'],'lens_cat',$aStock['lens_cat'],'lens_power',$aStock['lens_power'])[0]['SUM(quantity)'];
                                        $price=$override->selectData4('lens_sale','batch_id',$_GET['bid'],'lens_type',$brand['id'],'lens_cat',$aStock['lens_cat'],'lens_power',$aStock['lens_power']);
                                        $cCost=$payC*$price[0]['cost'];
                                        $crCost=$payCr*$price[0]['cost'];
                                        $tSales=$crCost+$cCost;
                                        $tExpCost=$aStock['quantity']*$price[0]['cost'];
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox"/></td>
                                            <td><a href="#"> <?=$brand['name']?></a></td>
                                            <td><?=$batch[0]['name']?></td>
                                            <td><?=$payCr?></td>
                                            <td><?=$payC?></td>
                                            <td><?=$sold[0]['SUM(quantity)']?></td>
                                            <td><?=$aStock['quantity']-$sold[0]['SUM(quantity)']?></td>
                                            <td><?=$aStock['quantity']?></td>
                                            <td><?=number_format($cCost)?></td>
                                            <td><?=number_format($crCost)?></td>
                                            <td><?=number_format($tSales)?></td>
                                            <td><?=number_format($tExpCost)?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        <?php }?>
                    </div>
                <?php }elseif($_GET['id'] == 4 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="head clearfix">
                                    <div class="isw-archive"></div>
                                    <h1>Sales Records</h1>
                                    <ul class="buttons">
                                        <li>
                                            <a href="#" class="isw-settings"></a>
                                            <ul class="dd-list">
                                                <li><a href="#"><span class="isw-list"></span> Show all</a></li>
                                                <li><a href="#"><span class="isw-ok"></span> Approved</a></li>
                                                <li><a href="#"><span class="isw-minus"></span> Unapproved</a></li>
                                                <li><a href="#"><span class="isw-refresh"></span> Refresh</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="block-fluid accordion">

                                    <h3>November 2012</h3>
                                    <div>
                                        <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
                                            <thead>
                                            <tr>
                                                <th width="60">Date</th><th>User</th><th width="60">Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><span class="date">Nov 6</span><span class="time">12:35</span></td>
                                                <td><a href="#">Aqvatarius</a></td>
                                                <td><span class="price">$1366.12</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="date">Nov 8</span><span class="time">18:42</span></td>
                                                <td><a href="#">Olga</a></td>
                                                <td><span class="price">$146.00</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="date">Nov 15</span><span class="time">8:21</span></td>
                                                <td><a href="#">Alex</a></td>
                                                <td><span class="price">$879.24</span></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="3" align="right"><button class="btn btn-default btn-sm">More...</button></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <h3>October 2012</h3>
                                    <div>
                                        <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
                                            <thead>
                                            <tr>
                                                <th width="60">Date</th><th>User</th><th width="60">Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><span class="date">Oct 6</span><span class="time">12:35</span></td>
                                                <td><a href="#">Aqvatarius</a></td>
                                                <td><span class="price">$1366.12</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="date">Oct 8</span><span class="time">18:42</span></td>
                                                <td><a href="#">Olga</a></td>
                                                <td><span class="price">$146.00</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="date">Oct 15</span><span class="time">8:21</span></td>
                                                <td><a href="#">Alex</a></td>
                                                <td><span class="price">$879.24</span></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="3" align="right"><button class="btn btn-default btn-sm">More...</button></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <h3>September 2012</h3>
                                    <div>
                                        <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
                                            <thead>
                                            <tr>
                                                <th width="60">Date</th><th>User</th><th width="60">Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><span class="date">Sep 6</span><span class="time">12:35</span></td>
                                                <td><a href="#">Aqvatarius</a></td>
                                                <td><span class="price">$1366.12</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="date">Sep 8</span><span class="time">18:42</span></td>
                                                <td><a href="#">Olga</a></td>
                                                <td><span class="price">$146.00</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="date">Sep 15</span><span class="time">8:21</span></td>
                                                <td><a href="#">Alex</a></td>
                                                <td><span class="price">$879.24</span></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="3" align="right"><button class="btn btn-default btn-sm">More...</button></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 5 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Frame Sale Report</h1>
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
                                    <th width="10%">Customer Name</th>
                                    <th width="10%">Invoice</th>
                                    <th width="10%">Batch</th>
                                    <th width="10%">Brand</th>
                                    <th width="10%">Sale Type</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="10"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('frame_sale') as $sale){
                                    $brand=$override->get('frame_brand','id',$sale['brand_id']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $price=$override->getNews('stock_batch','batch_id',$sale['batch_id'],'brand_id',$sale['brand_id'])[0];
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?php if($sale['pay_type'] == 1){echo 'Cash';}elseif ($sale['pay_type'] == 2){echo 'Credit';}?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Lens Sale Report</h1>
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
                                    <th width="10%">Customer Name</th>
                                    <th width="10%">Invoice</th>
                                    <th width="10%">Batch</th>
                                    <th width="10%">Lens Type</th>
                                    <th width="10%">Sale Type</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="10"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('lens_sale') as $sale){
                                    $brand=$override->get('lens_type','id',$sale['lens_type'])[0];
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $price=$override->selectData4('stock_batch_lens','batch_id',$sale['batch_id'],'lens_type',$brand['id'],'lens_cat',$sale['lens_cat'],'lens_power',$sale['lens_power'])[0];
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand['name']?></td>
                                        <td><?php if($sale['pay_type'] == 1){echo 'Cash';}elseif ($sale['pay_type'] == 2){echo 'Credit';}?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Accessories Sale Report</h1>
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
                                    <th width="10%">Customer Name</th>
                                    <th width="10%">Invoice</th>
                                    <th width="10%">Batch</th>
                                    <th width="10%">Lens Type</th>
                                    <th width="10%">Sale Type</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="10"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('accessories_sale') as $sale){
                                    $brand=$override->get('accessories','id',$sale['accessory_id'])[0];
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $price=$override->getNews('stock_batch_accessories','batch_id',$sale['batch_id'],'accessory_id',$brand['accessory_id'])[0];
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand['name']?></td>
                                        <td><?php if($sale['pay_type'] == 1){echo 'Cash';}elseif ($sale['pay_type'] == 2){echo 'Credit';}?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 6 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Frame Sale Details</h1>
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
                                <?php foreach ($override->get('frame_sale','user_id',$_GET['sid']) as $sale){
                                    $brand=$override->get('frame_brand','id',$sale['brand_id']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Lens Sale Details</h1>
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
                                    <th width="10%">Lens Type</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Issued Date</th>
                                    <th width="20"> Note</th>
                                    <th width="15%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('lens_sale','user_id',$_GET['sid']) as $sale){
                                    $brand=$override->get('lens_type','id',$sale['lens_type']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 7 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Batch Report</h1>
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
                                    <th width="20%">Batch Name</th>
                                    <th width="15%">Batch ID</th>
                                    <th width="10%">Batch Type</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Cost</th>
                                    <th width="15%">Batch Date</th>
                                    <th width="15">Status</th>
                                    <th width="5">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('batch') as $batch){?>
                                    <tr>
                                        <td><a href="#"><?=$batch['name']?></a></td>
                                        <td> <?=$batch['batch_id']?></td>
                                        <td> <?php if($batch['batch_type'] == 1){echo'Frame';}elseif($batch['batch_type'] == 2){echo'Lens';}?></td>
                                        <td><?=$batch['quantity']?></td>
                                        <td><?=number_format($batch['cost'])?></td>
                                        <td><?=$batch['create_date']?></td>
                                        <td><?php if($batch['status'] == 1){?><span class="label label-success">Active</span><?php }else{?><span class="label label-danger">Completed</span><?php }?></td>
                                        <?php if($batch['batch_type'] == 1){?>
                                            <td><a href="info.php?id=8&bid=<?=$batch['id']?>">Details</a> </td>
                                        <?php }elseif($batch['batch_type'] == 2){?>
                                            <td><a href="info.php?id=20&bid=<?=$batch['id']?>">Details</a> </td>
                                        <?php }elseif ($batch['batch_type'] == 3){?>
                                            <td><a href="info.php?id=23&bid=<?=$batch['id']?>">Details</a> </td>
                                        <?php }?>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 8 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Frame Batch Report</h1>
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
                                    <th width="15%">Batch Name</th>
                                    <th width="15%">Batch ID</th>
                                    <th width="15%">Brand</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Cost per Frame</th>
                                    <th width="15%">Total Cost</th>
                                    <th width="5%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('stock_batch','batch_id',$_GET['bid']) as $batch){
                                    $stockBatch=$override->get('batch','id',$batch['batch_id']);
                                    $brand=$override->get('frame_brand','id',$batch['brand_id']);
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$stockBatch[0]['name']?></a></td>
                                        <td> <?=$stockBatch[0]['batch_id']?></td>
                                        <td> <?=$brand[0]['name']?></td>
                                        <td><?=$batch['quantity']?></td>
                                        <td><?=number_format($batch['cost'])?></td>
                                        <td><?=number_format($batch['cost']*$batch['quantity'])?></td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="id" value="<?=$batch['id']?>">
                                                <input type="submit" name="d_frame_b" value="delete">
                                            </form>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 9 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Batch Report</h1>
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
                                    <th width="15%">Batch Name</th>
                                    <th width="15%">Batch ID</th>
                                    <th width="15%">Assigned</th>
                                    <th width="15%">unassigned</th>
                                    <th width="15%">Sold</th>
                                    <th width="15%">Remained Unsold</th>
                                    <th width="15">Total</th>
                                    <th width="10">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('batch') as $batch){
                                    $assigned=$override->getSumV('assigned_stock','quantity','batch_id',$batch['id'])[0]['SUM(quantity)'];
                                    $unassigned=$batch['quantity']-$assigned;
                                    $sold=$override->getSumV('frame_sale','quantity','batch_id',$batch['id'])[0]['SUM(quantity)'];
                                    $remain=$assigned-$sold;
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$batch['name']?></a></td>
                                        <td> <?=$batch['batch_id']?></td>
                                        <td><?=$assigned?></td>
                                        <td><?=$unassigned?></td>
                                        <td><?=$sold?></td>
                                        <td><?=$remain?> </td>
                                        <td><?=$batch['quantity']?></td>
                                        <td><?php if($batch['status'] == 1){?><span class="label label-success">Active</span><?php }else{?><span class="label label-danger">Completed</span><?php }?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 10 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Search Results</h1>
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
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="15"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $tts=0;$tq=0;foreach ($override->range('frame_sale','sale_date',$_GET['s'],'sale_date',$_GET['e']) as $sale){
                                    $brand=$override->get('frame_brand','id',$sale['brand_id']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $price=$override->getNews('stock_batch','batch_id',$sale['batch_id'],'brand_id',$sale['batch_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id']);
                                    $sl=$price['cost']*$sale['quantity'];
                                    $tts+=$sl;$tq+=$sale['quantity'];
                                    ?>
                                    <tr>
                                        <td><?=$sale['client_name']?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($sl)?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong><?=number_format($tq)?></strong></td>
                                    <td><strong><?=number_format($tts)?></strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 11 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Frame Cash Report</h1>
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
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="15"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('frame_sale','pay_type',1) as $sale){
                                    $brand=$override->get('frame_brand','id',$sale['brand_id']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $price=$override->getNews('stock_batch','batch_id',$sale['batch_id'],'brand_id',$sale['batch_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?=$sale['client_name']?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Lens Cash Report</h1>
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
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="15"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('lens_sale','pay_type',1) as $sale){
                                    $brand=$override->get('lens_type','id',$sale['lens_type']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $price=$override->selectData4('stock_batch_lens','batch_id',$sale['batch_id'],'lens_type',$brand['id'],'lens_cat',$sale['lens_cat'],'lens_power',$sale['lens_power'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Accessories Cash Report</h1>
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
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="15"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('accessories_sale','pay_type',1) as $sale){
                                    $brand=$override->get('accessories','id',$sale['accessory_id']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $price=$override->getNews('stock_batch_accessories','batch_id',$sale['batch_id'],'accessory_id',$brand['id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 12 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Frame Credit Report</h1>
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
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="15"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('frame_sale','pay_type',2) as $sale){
                                    $brand=$override->get('frame_brand','id',$sale['brand_id']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $price=$override->getNews('stock_batch','batch_id',$sale['batch_id'],'brand_id',$sale['batch_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?=$sale['client_name']?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Lens Credit Report</h1>
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
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="15"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('lens_sale','pay_type',2) as $sale){
                                    $brand=$override->get('lens_type','id',$sale['lens_type']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $price=$override->selectData4('stock_batch_lens','batch_id',$sale['batch_id'],'lens_type',$brand['id'],'lens_cat',$sale['lens_cat'],'lens_power',$sale['lens_power'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Accessories Credit Report</h1>
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
                                    <th width="10%">Cost</th>
                                    <th width="10%">Sales Date</th>
                                    <th width="15"> Note</th>
                                    <th width="10%">Staff</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('accessories_sale','pay_type',2) as $sale){
                                    $brand=$override->get('accessories','id',$sale['lens_type']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $customer=$override->get('customer','id',$sale['customer_id'])[0];
                                    $price=$override->getNews('stock_batch_accessories','batch_id',$sale['batch_id'],'accessory_id',$brand['accessory_id'])[0];
                                    $staff=$override->get('user','id',$sale['user_id'])?>
                                    <tr>
                                        <td><?php if($sale['client_name']){echo $sale['client_name'];}else{echo $customer['name'];}?></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($price['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="info.php?id=6&sid=<?=$sale['user_id']?>"><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 131){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>My Batch Report</h1>
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
                                    <th width="10%">Batch Name</th>
                                    <th width="10%">Batch ID</th>
                                    <th width="10%">Quantity Given</th>
                                    <th width="10%">Quantity SOld</th>
                                    <th width="10%">Quantity Remains</th>
                                    <th width="15%">Batch Date</th>
                                    <th width="15">Status</th>
                                    <th width="5">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $uBatch=$override->getNoRepeat('assigned_stock','batch_id','user_id',$user->data()->id);
                                foreach ($uBatch as $batches){$batch=$override->get('batch','id',$batches['batch_id'])[0];
                                    $quantity=$override->getSumV2('assigned_stock','quantity','batch_id',$batches['batch_id'],'user_id',$user->data()->id)[0];
                                    $sold=$override->getSumV2('frame_sale','quantity','batch_id',$batches['batch_id'],'user_id',$user->data()->id)[0];
                                    $remain=$quantity['SUM(quantity)']-$sold['SUM(quantity)']?>
                                    <tr>
                                        <td><a href="info.php?id=141&bid=<?=$batches['batch_id']?>"><?=$batch['name']?></a></td>
                                        <td> <?=$batch['batch_id']?></td>
                                        <td><?=$quantity['SUM(quantity)']?></td>
                                        <td><?=$sold['SUM(quantity)']?></td>
                                        <td><?=$remain?></td>
                                        <td><?=$batch['create_date']?></td>
                                        <td><?php if($batch['status'] == 1){?><span class="label label-success">Active</span><?php }else{?><span class="label label-danger">Completed</span><?php }?></td>
                                        <td><a href="info.php?id=141&bid=<?=$batches['batch_id']?>">Details</a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 132){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>My Batch Report Lens</h1>
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
                                    <th width="10%">Batch Name</th>
                                    <th width="10%">Batch ID</th>
                                    <th width="10%">Quantity Given</th>
                                    <th width="10%">Quantity SOld</th>
                                    <th width="10%">Quantity Remains</th>
                                    <th width="15%">Batch Date</th>
                                    <th width="15">Status</th>
                                    <th width="5">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $uBatch=$override->getNoRepeat('assigned_stock_lens','batch_id','user_id',$user->data()->id);
                                foreach ($uBatch as $batches){$batch=$override->get('batch','id',$batches['batch_id'])[0];
                                    $quantity=$override->getSumV2('assigned_stock_lens','quantity','batch_id',$batches['batch_id'],'user_id',$user->data()->id)[0];
                                    $sold=$override->getSumV2('lens_sale','quantity','batch_id',$batches['batch_id'],'user_id',$user->data()->id)[0];
                                    $remain=$quantity['SUM(quantity)']-$sold['SUM(quantity)']?>
                                    <tr>
                                        <td><a href="info.php?id=142&bid=<?=$batches['batch_id']?>"><?=$batch['name']?></a></td>
                                        <td> <?=$batch['batch_id']?></td>
                                        <td><?=$quantity['SUM(quantity)']?></td>
                                        <td><?=$sold['SUM(quantity)']?></td>
                                        <td><?=$remain?></td>
                                        <td><?=$batch['create_date']?></td>
                                        <td><?php if($batch['status'] == 1){?><span class="label label-success">Active</span><?php }else{?><span class="label label-danger">Completed</span><?php }?></td>
                                        <td><a href="info.php?id=142&bid=<?=$batches['batch_id']?>">Details</a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 133){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>My Batch Report Accessories</h1>
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
                                    <th width="10%">Batch Name</th>
                                    <th width="10%">Batch ID</th>
                                    <th width="10%">Quantity Given</th>
                                    <th width="10%">Quantity SOld</th>
                                    <th width="10%">Quantity Remains</th>
                                    <th width="15%">Batch Date</th>
                                    <th width="15">Status</th>
                                    <th width="5">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $uBatch=$override->getNoRepeat('assigned_stock_accessories','batch_id','user_id',$user->data()->id);
                                foreach ($uBatch as $batches){$batch=$override->get('batch','id',$batches['batch_id'])[0];
                                    $quantity=$override->getSumV2('assigned_stock_accessories','quantity','batch_id',$batches['batch_id'],'user_id',$user->data()->id)[0];
                                    $sold=$override->getSumV2('accessories_sale','quantity','batch_id',$batches['batch_id'],'user_id',$user->data()->id)[0];
                                    $remain=$quantity['SUM(quantity)']-$sold['SUM(quantity)']?>
                                    <tr>
                                        <td><a href="info.php?id=143&bid=<?=$batches['batch_id']?>"><?=$batch['name']?></a></td>
                                        <td> <?=$batch['batch_id']?></td>
                                        <td><?=$quantity['SUM(quantity)']?></td>
                                        <td><?=$sold['SUM(quantity)']?></td>
                                        <td><?=$remain?></td>
                                        <td><?=$batch['create_date']?></td>
                                        <td><?php if($batch['status'] == 1){?><span class="label label-success">Active</span><?php }else{?><span class="label label-danger">Completed</span><?php }?></td>
                                        <td><a href="info.php?id=143&bid=<?=$batches['batch_id']?>">Details</a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 141){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Batch Report</h1>
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
                                    <th width="10%">Batch Name</th>
                                    <th width="10%">Batch ID</th>
                                    <th width="10%">Brand</th>
                                    <th width="10%">Quantity Given</th>
                                    <th width="10%">Quantity Sold</th>
                                    <th width="10%">Quantity Remain</th>
                                    <th width="10%">Cost per Frame</th>
                                    <th width="10%">Sold Amount</th>
                                    <th width="10%">Stock in Hand Amount</th>
                                    <th width="10%">Total Cost</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getNews('assigned_stock','user_id',$user->data()->id,'batch_id',$_GET['bid']) as $batch){
                                    $stockBatch=$override->get('batch','id',$batch['batch_id']);
                                    $brand=$override->get('frame_brand','id',$batch['brand_id']);
                                    $cost=$override->getNews('stock_batch','batch_id',$batch['batch_id'],'brand_id',$batch['brand_id'])[0];
                                    $quantity=$override->getSumV2('assigned_stock','quantity','batch_id',$batch['batch_id'],'user_id',$user->data()->id)[0];
                                    $sold=$override->getSumV2('frame_sale','quantity','batch_id',$batch['batch_id'],'user_id',$user->data()->id)[0];
                                    $remain=$quantity['SUM(quantity)']-$sold['SUM(quantity)']
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$stockBatch[0]['name']?></a></td>
                                        <td> <?=$stockBatch[0]['batch_id']?></td>
                                        <td> <?=$brand[0]['name']?></td>
                                        <td><?=$batch['quantity']?></td>
                                        <td><?=$sold['SUM(quantity)']?></td>
                                        <td><?=$remain?></td>
                                        <td><?=number_format($cost['cost'])?></td>
                                        <td><?=number_format($cost['cost']*$sold['SUM(quantity)'])?></td>
                                        <td><?=number_format($cost['cost']*$remain)?></td>
                                        <td><?=number_format($cost['cost']*$batch['quantity'])?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 142){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Batch Report Lens</h1>
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
                                    <th width="10%">Batch Name</th>
                                    <th width="10%">Batch ID</th>
                                    <th width="10%">Quantity Given</th>
                                    <th width="10%">Quantity Sold</th>
                                    <th width="10%">Quantity Remain</th>
                                    <th width="10%">Cost per Frame</th>
                                    <th width="10%">Sold Amount</th>
                                    <th width="10%">Stock in Hand Amount</th>
                                    <th width="10%">Total Cost</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getNews('assigned_stock_lens','user_id',$user->data()->id,'batch_id',$_GET['bid']) as $batch){
                                    $stockBatch=$override->get('batch','id',$batch['batch_id']);
                                    $cost=$override->get('stock_batch_lens','batch_id',$batch['batch_id'])[0];
                                    $quantity=$override->getSumV2('assigned_stock_lens','quantity','batch_id',$batch['batch_id'],'user_id',$user->data()->id)[0];
                                    $sold=$override->getSumV2('lens_sale','quantity','batch_id',$batch['batch_id'],'user_id',$user->data()->id)[0];
                                    $remain=$quantity['SUM(quantity)']-$sold['SUM(quantity)']
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$stockBatch[0]['name']?></a></td>
                                        <td> <?=$stockBatch[0]['batch_id']?></td>
                                        <td> <?=$brand[0]['name']?></td>
                                        <td><?=$batch['quantity']?></td>
                                        <td><?=$sold['SUM(quantity)']?></td>
                                        <td><?=$remain?></td>
                                        <td><?=number_format($cost['cost'])?></td>
                                        <td><?=number_format($cost['cost']*$sold['SUM(quantity)'])?></td>
                                        <td><?=number_format($cost['cost']*$remain)?></td>
                                        <td><?=number_format($cost['cost']*$batch['quantity'])?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 143){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Batch Report Accessories</h1>
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
                                    <th width="10%">Batch Name</th>
                                    <th width="10%">Batch ID</th>
                                    <th width="10%">Quantity Given</th>
                                    <th width="10%">Quantity Sold</th>
                                    <th width="10%">Quantity Remain</th>
                                    <th width="10%">Cost per Frame</th>
                                    <th width="10%">Sold Amount</th>
                                    <th width="10%">Stock in Hand Amount</th>
                                    <th width="10%">Total Cost</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getNews('assigned_stock_accessories','user_id',$user->data()->id,'batch_id',$_GET['bid']) as $batch){
                                    $stockBatch=$override->get('batch','id',$batch['batch_id']);
                                    $cost=$override->get('stock_batch_accessories','batch_id',$batch['batch_id'])[0];
                                    $quantity=$override->getSumV2('assigned_stock_accessories','quantity','batch_id',$batch['batch_id'],'user_id',$user->data()->id)[0];
                                    $sold=$override->getSumV2('accessories_sale','quantity','batch_id',$batch['batch_id'],'user_id',$user->data()->id)[0];
                                    $remain=$quantity['SUM(quantity)']-$sold['SUM(quantity)']
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$stockBatch[0]['name']?></a></td>
                                        <td> <?=$stockBatch[0]['batch_id']?></td>
                                        <td><?=$batch['quantity']?></td>
                                        <td><?=$sold['SUM(quantity)']?></td>
                                        <td><?=$remain?></td>
                                        <td><?=number_format($cost['cost'])?></td>
                                        <td><?=number_format($cost['cost']*$sold['SUM(quantity)'])?></td>
                                        <td><?=number_format($cost['cost']*$remain)?></td>
                                        <td><?=number_format($cost['cost']*$batch['quantity'])?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 15){?>
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
                                    <th width="10%">Customer Name</th>
                                    <th width="10%">Invoice</th>
                                    <th width="10%">Batch</th>
                                    <th width="10%">Brand</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Cost</th>
                                    <th width="10%">Issued Date</th>
                                    <th width="20"> Note</th>
                                    <th width="10">Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('frame_sale','user_id',$user->data()->id) as $sale){
                                    $brand=$override->get('frame_brand','id',$sale['brand_id']);
                                    $batch=$override->get('batch','id',$sale['batch_id']);
                                    $cost=$override->getNews('stock_batch','batch_id',$sale['batch_id'],'brand_id',$sale['brand_id'])[0];
                                    if($sale['customer_id']){$cname=$override->get('customer','id',$sale['customer_id'])[0]['name'];}else{$cname=$sale['client_name'];}
                                   ?>
                                    <tr>
                                        <td><a href="#"><?=$cname?></a></td>
                                        <td> <?=$sale['invoice']?></td>
                                        <td><?=$batch[0]['name'].' ('.$batch[0]['batch_id'].')'?></td>
                                        <td><?=$brand[0]['name']?></td>
                                        <td><?=$sale['quantity']?></td>
                                        <td><?=number_format($cost['cost']*$sale['quantity'])?></td>
                                        <td><?=$sale['sale_date']?></td>
                                        <td><?=$sale['note']?></td>
                                        <td><a href="add.php?id=12&sid=<?=$sale['id']?>">Returned</a> </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 16 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Customers</h1>
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
                                    <th width="20%">Business Name / Name</th>
                                    <th width="10%">Tin</th>
                                    <th width="10%">Phone NUmber</th>
                                    <th width="10%">Email Address</th>
                                    <th width="40%">Location</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('customer') as $customer){
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$customer['name']?></a></td>
                                        <td> <?=$customer['tin']?></td>
                                        <td><?=$customer['phone_number']?></td>
                                        <td><?=$customer['email_address']?></td>
                                        <td><?=$customer['location']?></td>
                                        <td></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 17){?>
                    <div class="col-md-12">
                        <?php if($_GET['typ'] == 'fr'){?>
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Frame Payment Report</h1>
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
                                        <th width="20%">Invoice No</th>
                                        <th width="20%">Delivery Note</th>
                                        <th width="15%">Amount Paid</th>
                                        <th width="15%">Required Amount</th>
                                        <th width="15%">Date</th>
                                        <th width="15%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if($user->data()->position == 1){
                                        $payments=$override->getData('payment');
                                    }elseif ($user->data()->position == 2){
                                        $payments=$override->get('payment','user_id',$user->data()->id);
                                    }
                                    foreach ($payments as $payment){
                                        $sale=$override->get('frame_sale','id',$payment['sale_id'])[0]; ?>
                                        <tr>
                                            <td><a href="#"><?=$sale['invoice']?></a></td>
                                            <td> <?=$sale['delivery_note']?></td>
                                            <td><?=number_format($payment['pay_amount'])?></td>
                                            <td><?=number_format($payment['required_amount'])?></td>
                                            <td><?=$payment['pay_date']?></td>
                                            <td><?php if($payment['pay_amount'] == $payment['required_amount']){?><span class="label label-success">Complete</span><?php }else{?><span class="label label-danger">Pending</span><?php }?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        <?php }elseif ($_GET['typ'] == 'ln'){?>
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Lens Payment Report</h1>
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
                                        <th width="20%">Invoice No</th>
                                        <th width="20%">Delivery Note</th>
                                        <th width="15%">Amount Paid</th>
                                        <th width="15%">Required Amount</th>
                                        <th width="15%">Date</th>
                                        <th width="15%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if($user->data()->position == 1){
                                        $payments=$override->getData('payment_lens');
                                    }elseif ($user->data()->position == 2){
                                        $payments=$override->get('payment_lens','user_id',$user->data()->id);
                                    }
                                    foreach ($payments as $payment){
                                        $sale=$override->get('lens_sale','id',$payment['sale_id'])[0]; ?>
                                        <tr>
                                            <td><a href="#"><?=$sale['invoice']?></a></td>
                                            <td> <?=$sale['delivery_note']?></td>
                                            <td><?=number_format($payment['pay_amount'])?></td>
                                            <td><?=number_format($payment['required_amount'])?></td>
                                            <td><?=$payment['pay_date']?></td>
                                            <td><?php if($payment['pay_amount'] == $payment['required_amount']){?><span class="label label-success">Complete</span><?php }else{?><span class="label label-danger">Pending</span><?php }?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        <?php }?>
                    </div>
                <?php }elseif ($_GET['id'] == 18){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Frame Credit Payment Report</h1>
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
                                    <th width="15%">Invoice No</th>
                                    <th width="15%">Delivery Note</th>
                                    <th width="10%">Amount Paid</th>
                                    <th width="10%">Amount Remaining</th>
                                    <th width="10%">Required Amount</th>
                                    <th width="15%">Date</th>
                                    <th width="10%">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if($user->data()->position == 1){
                                    $payments=$override->get('payment','status',0);
                                }elseif ($user->data()->position == 2){
                                    $payments=$override->getNews('payment','status',0,'user_id',$user->data()->id);
                                }
                                foreach ($payments as $payment){
                                    $sale=$override->get('frame_sale','id',$payment['sale_id'])[0];
                                    $cus=$override->get('customer','id',$sale['customer_id'])[0];
                                    if($sale['customer_id']){$cname=$override->get('customer','id',$sale['customer_id'])[0]['name'];}else{$cname=$sale['client_name'];}?>
                                    <tr>
                                        <td><?=$cname?></td>
                                        <td><a href="#"><?=$sale['invoice']?></a></td>
                                        <td> <?=$sale['delivery_note']?></td>
                                        <td><?=number_format($payment['pay_amount'])?></td>
                                        <td><?=number_format($payment['required_amount'] - $payment['pay_amount'])?></td>
                                        <td><?=number_format($payment['required_amount'])?></td>
                                        <td><?=$payment['pay_date']?></td>
                                        <td><?php if($payment['pay_amount'] == $payment['required_amount']){?><span class="label label-success">Complete</span><?php }else{?><span class="label label-danger">Pending</span><?php }?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Lens Credit Payment Report</h1>
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
                                    <th width="15%">Invoice No</th>
                                    <th width="15%">Delivery Note</th>
                                    <th width="10%">Amount Paid</th>
                                    <th width="10%">Amount Remaining</th>
                                    <th width="10%">Required Amount</th>
                                    <th width="15%">Date</th>
                                    <th width="10%">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if($user->data()->position == 1){
                                    $payments=$override->get('payment_lens','status',0);
                                }elseif ($user->data()->position == 2){
                                    $payments=$override->getNews('payment_lens','status',0,'user_id',$user->data()->id);
                                }
                                foreach ($payments as $payment){
                                    $sale=$override->get('lens_sale','id',$payment['sale_id'])[0];
                                    $cus=$override->get('customer','id',$sale['customer_id'])[0];
                                    if($sale['customer_id']){$cname=$override->get('customer','id',$sale['customer_id'])[0]['name'];}else{$cname=$sale['client_name'];}?>
                                    <tr>
                                        <td><?=$cname?></td>
                                        <td><a href="#"><?=$sale['invoice']?></a></td>
                                        <td> <?=$sale['delivery_note']?></td>
                                        <td><?=number_format($payment['pay_amount'])?></td>
                                        <td><?=number_format($payment['required_amount'] - $payment['pay_amount'])?></td>
                                        <td><?=number_format($payment['required_amount'])?></td>
                                        <td><?=$payment['pay_date']?></td>
                                        <td><?php if($payment['pay_amount'] == $payment['required_amount']){?><span class="label label-success">Complete</span><?php }else{?><span class="label label-danger">Pending</span><?php }?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Accessories Credit Payment Report</h1>
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
                                    <th width="15%">Invoice No</th>
                                    <th width="15%">Delivery Note</th>
                                    <th width="10%">Amount Paid</th>
                                    <th width="10%">Amount Remaining</th>
                                    <th width="10%">Required Amount</th>
                                    <th width="15%">Date</th>
                                    <th width="10%">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if($user->data()->position == 1){
                                    $payments=$override->get('payment_accessories','status',0);
                                }elseif ($user->data()->position == 2){
                                    $payments=$override->getNews('payment_accessories','status',0,'user_id',$user->data()->id);
                                }
                                foreach ($payments as $payment){
                                    $sale=$override->get('accessories_sale','id',$payment['sale_id'])[0];
                                    $cus=$override->get('customer','id',$sale['customer_id'])[0];
                                    if($sale['customer_id']){$cname=$override->get('customer','id',$sale['customer_id'])[0]['name'];}else{$cname=$sale['client_name'];}?>
                                    <tr>
                                        <td><?=$cname?></td>
                                        <td><a href="#"><?=$sale['invoice']?></a></td>
                                        <td> <?=$sale['delivery_note']?></td>
                                        <td><?=number_format($payment['pay_amount'])?></td>
                                        <td><?=number_format($payment['required_amount'] - $payment['pay_amount'])?></td>
                                        <td><?=number_format($payment['required_amount'])?></td>
                                        <td><?=$payment['pay_date']?></td>
                                        <td><?php if($payment['pay_amount'] == $payment['required_amount']){?><span class="label label-success">Complete</span><?php }else{?><span class="label label-danger">Pending</span><?php }?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 19){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Return Frame Report</h1>
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
                                    <th width="10%">Invoice No</th>
                                    <th width="10%">Delivery Note</th>
                                    <th width="10%">Batch</th>
                                    <th width="10%">Brand</th>
                                    <th width="15%">Quantity</th>
                                    <th width="10%">Date</th>
                                    <th width="20%">Reasons</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($override->getData('returned_frame') as $return){
                                    $sale=$override->get('frame_sale','id',$return['sale_id'])[0];
                                    $cus=$override->get('customer','id',$sale['customer_id'])[0];
                                    $batch=$override->get('batch','id',$sale['batch_id'])[0];
                                    $brand=$override->get('frame_brand','id',$sale['brand_id'])[0];
                                    if($sale['customer_id']){$cname=$override->get('customer','id',$sale['customer_id'])[0]['name'];}else{$cname=$sale['client_name'];}?>
                                    <tr>
                                        <td><?=$cname?></td>
                                        <td><a href="#"><?=$sale['invoice']?></a></td>
                                        <td> <?=$sale['delivery_note']?></td>
                                        <td><?=$batch['name'].'( '.$batch['batch_id'].' ) '?></td>
                                        <td><?=$brand['name']?></td>
                                        <td><?=$return['quantity']?></td>
                                        <td><?=$return['return_date']?></td>
                                        <td><?=$return['details']?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 20 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Lens Batch Report</h1>
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
                                    <th width="15%">Batch Name</th>
                                    <th width="10%">Batch ID</th>
                                    <th width="10%">Lens Type</th>
                                    <th width="10%">Lens Category</th>
                                    <th width="10%">Lens Power</th>
                                    <th width="10%">Quantity</th>
                                    <th width="15%">Cost per Lens</th>
                                    <th width="15%">Total Cost</th>
                                    <td width="5%">Action</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('stock_batch_lens','batch_id',$_GET['bid']) as $batch){
                                    $stockBatch=$override->get('batch','id',$batch['batch_id'])[0];
                                    $lensType=$override->get('lens_type','id',$batch['lens_type'])[0];
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$stockBatch['name']?></a></td>
                                        <td> <?=$stockBatch['batch_id']?></td>
                                        <td> <?=$lensType['name']?></td>
                                        <td><?=$batch['lens_cat']?></td>
                                        <td><?=$batch['lens_power']?></td>
                                        <td><?=$batch['quantity']?></td>
                                        <td><?=number_format($batch['cost'])?></td>
                                        <td><?=number_format($batch['cost']*$batch['quantity'])?></td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="id" value="<?=$batch['id']?>">
                                                <input type="submit" name="d_lens_l" value="delete">
                                            </form>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 21){?>
                    <div class="col-md-12">
                        <?php if($_GET['typ'] == 'ac'){?>
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Accessories Payment Report</h1>
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
                                        <th width="20%">Invoice No</th>
                                        <th width="20%">Delivery Note</th>
                                        <th width="15%">Amount Paid</th>
                                        <th width="15%">Required Amount</th>
                                        <th width="15%">Date</th>
                                        <th width="15%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if($user->data()->position == 1){
                                        $payments=$override->getData('payment_accessories');
                                    }elseif ($user->data()->position == 2){
                                        $payments=$override->get('payment_accessories','user_id',$user->data()->id);
                                    }
                                    foreach ($payments as $payment){
                                        $sale=$override->get('accessories_sale','id',$payment['sale_id'])[0]; ?>
                                        <tr>
                                            <td><a href="#"><?=$sale['invoice']?></a></td>
                                            <td> <?=$sale['delivery_note']?></td>
                                            <td><?=number_format($payment['pay_amount'])?></td>
                                            <td><?=number_format($payment['required_amount'])?></td>
                                            <td><?=$payment['pay_date']?></td>
                                            <td><?php if($payment['pay_amount'] == $payment['required_amount']){?><span class="label label-success">Complete</span><?php }else{?><span class="label label-danger">Pending</span><?php }?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        <?php }elseif ($_GET['typ'] == 'ln'){?>
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Lens Payment Report</h1>
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
                                        <th width="20%">Invoice No</th>
                                        <th width="20%">Delivery Note</th>
                                        <th width="15%">Amount Paid</th>
                                        <th width="15%">Required Amount</th>
                                        <th width="15%">Date</th>
                                        <th width="15%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if($user->data()->position == 1){
                                        $payments=$override->getData('payment_lens');
                                    }elseif ($user->data()->position == 2){
                                        $payments=$override->get('payment_lens','user_id',$user->data()->id);
                                    }
                                    foreach ($payments as $payment){
                                        $sale=$override->get('lens_sale','id',$payment['sale_id'])[0]; ?>
                                        <tr>
                                            <td><a href="#"><?=$sale['invoice']?></a></td>
                                            <td> <?=$sale['delivery_note']?></td>
                                            <td><?=number_format($payment['pay_amount'])?></td>
                                            <td><?=number_format($payment['required_amount'])?></td>
                                            <td><?=$payment['pay_date']?></td>
                                            <td><?php if($payment['pay_amount'] == $payment['required_amount']){?><span class="label label-success">Complete</span><?php }else{?><span class="label label-danger">Pending</span><?php }?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        <?php }?>
                    </div>
                <?php }elseif ($_GET['id'] == 22 && $user->data()->position == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Staff</h1>
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
                                    <th width="25%">Username</th>
                                    <th width="25%">Position</th>
                                    <th width="25%">Branch</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('user') as $staff){
                                    $branch=$override->get('branch','id',$staff['branch'])[0]?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td> <?=$staff['firstname'].' '.$staff['lastname']?></td>
                                        <td><?=$staff['username']?></td>
                                        <td><?php if($staff['position']==1){echo 'Administrator';}else{echo 'Sales Personnel';}?></td>
                                        <td><?=$branch['name'] ?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 23){  /*list all accessories here*/?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Accessories Batch Report</h1>
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
                                    <th width="15%">Batch Name</th>
                                    <th width="10%">Batch ID</th>
                                    <th width="10%">Lens Type</th>
                                    <th width="10%">Lens Category</th>
                                    <th width="10%">Lens Power</th>
                                    <th width="10%">Quantity</th>
                                    <th width="15%">Cost per Lens</th>
                                    <th width="15%">Total Cost</th>
                                    <td width="5%">Action</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('stock_batch_lens','batch_id',$_GET['bid']) as $batch){
                                    $stockBatch=$override->get('batch','id',$batch['batch_id'])[0];
                                    $lensType=$override->get('lens_type','id',$batch['lens_type'])[0];
                                    ?>
                                    <tr>
                                        <td><a href="#"><?=$stockBatch['name']?></a></td>
                                        <td> <?=$stockBatch['batch_id']?></td>
                                        <td> <?=$lensType['name']?></td>
                                        <td><?=$batch['lens_cat']?></td>
                                        <td><?=$batch['lens_power']?></td>
                                        <td><?=$batch['quantity']?></td>
                                        <td><?=number_format($batch['cost'])?></td>
                                        <td><?=number_format($batch['cost']*$batch['quantity'])?></td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="id" value="<?=$batch['id']?>">
                                                <input type="submit" name="d_lens_l" value="delete">
                                            </form>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 24){?>

                <?php }?>
            </div>

            <div class="dr"><span></span></div>
        </div>
    </div>
</div>
</body>

</html>
