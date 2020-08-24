<?php $total = 0;$assigned=0;
    foreach ($override->getData('frame_stock') as $stock){
        $total += $stock['quantity'];
    }
foreach ($override->getData('assigned_stock') as $stock){
    $assigned += $stock['quantity'];
}
?>
<div class="menu">

    <div class="breadLine">
        <div class="arrow"></div>
        <div class="adminControl active">
            Hi, <?=$user->data()->firstname?>
        </div>
    </div>

    <div class="admin">
        <div class="image">
            <img src="img/users/no-image.jpg" class="img-thumbnail"/>
        </div>
        <ul class="control">
            <li><span class="glyphicon glyphicon-comment"></span> <a href="#">Messages</a> <a href="#" class="caption red">12</a></li>
            <li><span class="glyphicon glyphicon-cog"></span> <a href="#">Settings</a></li>
            <li><span class="glyphicon glyphicon-share-alt"></span> <a href="logout.php">Logout</a></li>
        </ul>
        <div class="info">
            <span>Welcom back! Your last visit: <?=$user->data()->last_login?></span>
        </div>
    </div>

    <ul class="navigation">
        <li class="active">
            <a href="dashboard.php">
                <span class="isw-grid"></span><span class="text">Dashboard</span>
            </a>
        </li>
        <li class="active">
            <a href="add.php?id=3">
                <span class="isw-list"></span><span class="text">Add Stock</span>
            </a>
        </li>
        <li>
            <a href="add.php?id=4">
                <span class="isw-archive"></span><span class="text">Assign Stock</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="isw-settings"></span><span class="text">Stock Management</span>
            </a>
        </li>
        <li>
            <a href="add.php?id=1">
                <span class="isw-user"></span><span class="text">Add staff</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="isw-users"></span><span class="text">Manage staff</span>
            </a>
        </li>

        <li>
            <a href="add.php?id=2">
                <span class="isw-graph"></span><span class="text">Add Clinic Branch</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="isw-text_document"></span><span class="text">Manage Clinic Branch</span>
            </a>
        </li>

        <li>
            <a href="#">
                <span class="isw-zoom"></span><span class="text">Reports</span>
            </a>
        </li>

    </ul>

    <div class="dr"><span></span></div>

    <div class="widget-fluid">
        <div id="menuDatepicker"></div>
    </div>

    <div class="dr"><span></span></div>

    <div class="widget">

        <div class="input-group">
            <input id="appendedInputButton" class="form-control" type="text">
            <div class="input-group-btn">
                <button class="btn btn-default" type="button">Search</button>
            </div>
        </div>

    </div>

    <div class="dr"><span></span></div>

    <div class="widget-fluid">

        <div class="wBlock clearfix">
            <div class="dSpace">
                <h3>Total Frames</h3>
                <span class="number"><?=$total?></span>
                <span>5,774 <b>unique</b></span>
                <span>3,512 <b>returning</b></span>
            </div>
<!--            <div class="rSpace">-->
<!--                <h3>Today</h3>-->
<!--                <span class="mChartBar" sparkType="bar" sparkBarColor="white">240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190</span>-->
<!--                <span>&nbsp;</span>-->
<!--                <span>65% <b>New</b></span>-->
<!--                <span>35% <b>Returning</b></span>-->
<!--            </div>-->
        </div>

    </div>

</div>