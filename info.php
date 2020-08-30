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
    <title>Info - OnaEyeCare</title>
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
                            <?php if($user->data()->accessLevel == 1){foreach ($override->getData('user') as $usr){?>
                                <div class="item clearfix">
                                    <div class="image"><a href="#"><img src="img/users/no-image.jpg" width="32"/></a></div>
                                    <div class="info">
                                        <a href="#" class="name"><?=$usr['firstname'].' '.$usr['lastname']?></a>
                                        <span></span>
                                    </div>
                                </div>
                            <?php }}?>
                        </div>
                        <div class="footer">
                            <a href="add.php?id=1" class="btn btn-default" type="button">Add new</a>
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

            <div class="row">
                <?php if($_GET['id'] == 1){?>
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
                <?php } elseif ($_GET['id'] == 2){?>
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
                                    <th width="20%">Sold</th>
                                    <th width="20%">Remained</th>
                                    <th width="20%">Total</th>
                                    <th width="15%">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $batches = $override->getNoRepeat('assigned_stock','batch_id', 'user_id',$_GET['uid']);
                                foreach ($batches as $batch){
                                foreach ($override->get('assigned_stock','batch_id',$batch['batch_id']) as $aStock){
                                    $staff=$override->get('user','id',$aStock['user_id']);
                                    $batch_name = $override->get('batch','id',$batch['batch_id'])[0]['name'];
                                    $sld=0;
                                    $sold = $override->getSumV('frame_sale','quantity','batch_id',$batch['batch_id'])?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td><a href="#"> <?=$batch_name?></a></td>
                                        <td><?php if($sold[0]['SUM(quantity)']){$sld=$sold[0]['SUM(quantity)'];echo $sold[0]['SUM(quantity)'];}else{echo 0;}?></td>
                                        <td><?=($aStock['quantity']-$sld)?></td>
                                        <td><?=$aStock['quantity']?></td>
                                        <td><a href="info.php?id=3&uid=<?=$staff[0]['id']?>&bid=<?=$batch['batch_id']?>">Details</a> </td>
                                    </tr>
                                <?php }}?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 3){?>
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
                                    <th width="20%">Brand</th>
                                    <th width="20%">Batch</th>
                                    <th width="10%">Credit</th>
                                    <th width="10%">Cash</th>
                                    <th width="20%">Total Sold</th>
                                    <th width="20%">Total Stock</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getNews('assigned_stock','user_id',$_GET['uid'],'batch_id',$_GET['bid']) as $aStock){
                                    $brand=$override->get('frame_brand','id',$aStock['brand_id']);
                                    $batch=$override->get('batch','id',$aStock['batch_id']);
                                    $sold = $override->getSumV('frame_sale','quantity','batch_id',$aStock['batch_id']);
                                    $payCr=0;$payC=0;
                                    $sld = $override->getNews('frame_sale','batch_id',$_GET['bid'],'brand_id',$brand[0]['id']);
                                    if($sld[0]['pay_type'] == 1){
                                        $payC=$override->getSumV3('frame_sale','quantity','batch_id',$aStock['batch_id'],'brand_id',$brand[0]['id'],'pay_type',1)[0]['SUM(quantity)'];
                                    }elseif ($sld[0]['pay_type'] == 2){
                                        $payCr = $override->getSumV3('frame_sale','quantity','batch_id',$aStock['batch_id'],'brand_id',$brand[0]['id'],'pay_type',2)[0]['SUM(quantity)'];
                                    }?>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox"/></td>
                                            <td><a href="#"> <?=$brand[0]['name']?></a></td>
                                            <td><?=$batch[0]['name']?></td>
                                            <td><?=$payCr?></td>
                                            <td><?=$payC?></td>
                                            <td><?=$sold[0]['SUM(quantity)']?></td>
                                            <td><?=$aStock['quantity']?></td>
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