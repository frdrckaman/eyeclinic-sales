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
        elseif (Input::get('assign_stock_frame')) {
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
        elseif (Input::get('assign_stock_lens')) {
            $validate = $validate->check($_POST, array(
                'lens_type' => array(
                    'required' => true,
                ),
                'batch_id' => array(
                    'required' => true,
                ),
                'lens_category' => array(
                    'required' => true,
                ),
                'lens_power' => array(
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
                $batch=$override->selectData5('stock_batch_lens','batch_id',Input::get('batch_id'),'lens_type',Input::get('lens_type'),'lens_power',Input::get('lens_power'),'lens_cat',Input::get('lens_category'),'status',1);
                $stocks_batch=$override->getSumV5('assigned_stock_lens','quantity','batch_id',Input::get('batch_id'),'lens_type',Input::get('lens_type'),'lens_cat',Input::get('lens_category'),'lens_power',Input::get('lens_power'),'status',1);
                $nw_st = Input::get('quantity') + $stocks_batch[0]['SUM(quantity)'];
                if($batch[0]['quantity'] >= $nw_st){
                    try {
                        $stocks = $override->selectData4('assigned_stock_lens','lens_type', Input::get('lens_type'),'batch_id',Input::get('batch_id'),'lens_cat',Input::get('lens_category'),'user_id', Input::get('user_id'));
                        if($stocks){
                            $qnt= $stocks[0]['quantity'] + Input::get('quantity');
                            $user->updateRecord('assigned_stock_lens',array('quantity'=>$qnt),$stocks[0]['id']);
                            $successMessage = 'Lens Stock Assigned Successful';
                        }else{
                            $user->createRecord('assigned_stock_lens', array(
                                'user_id' => Input::get('user_id'),
                                'batch_id' => Input::get('batch_id'),
                                'lens_type' => Input::get('lens_type'),
                                'lens_cat' => Input::get('lens_category'),
                                'lens_power' => Input::get('lens_power'),
                                'quantity' => Input::get('quantity'),
                                'assign_on' => date('Y-m-d'),
                                'status' => 1,
                                'admin_id'=>$user->data()->id
                            ));
                            $successMessage = 'Lens Stock Assigned Successful';
                        }
                        $user->createRecord('assigned_stock_lens_rec', array(
                            'user_id' => Input::get('user_id'),
                            'batch_id' => Input::get('batch_id'),
                            'lens_type' => Input::get('lens_type'),
                            'lens_cat' => Input::get('lens_category'),
                            'lens_power' => Input::get('lens_power'),
                            'quantity' => Input::get('quantity'),
                            'assign_on' => date('Y-m-d'),
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
                'batch_type' => array(
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
                        'batch_type' => Input::get('batch_type'),
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
        elseif (Input::get('add_batch_stock_frame')){
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
                $batch=$override->selectData('batch','id',Input::get('batch_id'),'batch_type',1,'status',1);
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
                        $successMessage = 'Frame Stock Batch Successful Added';

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
        elseif (Input::get('add_batch_stock_lens')){
            $validate = $validate->check($_POST, array(
                'lens_type' => array(
                    'required' => true,
                ),
                'batch_id' => array(
                    'required' => true,
                ),
                'lens_category' => array(
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
                $batch=$override->selectData('batch','id',Input::get('batch_id'),'batch_type',2,'status',1);
                $stocks_batch=$override->getSumV('stock_batch_lens','quantity','batch_id',Input::get('batch_id'));
                $nw_st = Input::get('quantity') + $stocks_batch[0]['SUM(quantity)'];
                if($nw_st <= $batch[0]['quantity']){
                    try {
                        $user->createRecord('stock_batch_lens', array(
                            'batch_id' => Input::get('batch_id'),
                            'lens_type' => Input::get('lens_type'),
                            'lens_cat' => Input::get('lens_category'),
                            'lens_power' => Input::get('lens_power'),
                            'quantity' => Input::get('quantity'),
                            'cost' => Input::get('price'),
                            'create_on' => date('Y-m-d'),
                            'status' => 1,
                            'user_id'=>$user->data()->id
                        ));
                        $successMessage = 'Lens Stock Batch Successful Added';
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
                'cash' => array(
                    'required' => true,
                ),
                'invoice_no' => array(
                    'required' => true,
                ),
                'delivery_note' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {$avl=0;$sld=0;
                $assigned_stock=$override->selectData('assigned_stock','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'),'user_id',$user->data()->id);
                $stocks_sold=$override->getSumV3('frame_sale','quantity','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'),'user_id',$user->data()->id);
                $avl=$assigned_stock[0]['quantity'] - $stocks_sold[0]['SUM(quantity)'];
                $price=$override->getNews('stock_batch','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'))[0];
                $exp=Input::get('quantity')*$price['cost'];
//                $invoice = $random->get_rand_numbers(6);
//                $user->updateRecord('frame_sale',array('invoice'=>$invoice),3);
//                $checkInvNo = $override->get('frame_sale','invoice',$invoice);
//                while($override->unique('frame_sale','invoice',$invoice) == true){
//                    $invoice = $random->get_rand_numbers(6);
//                }

                if(Input::get('quantity') <= $avl){
                    if(Input::get('cash') < $exp || Input::get('cash') > $exp){
                        $errorMessage='Payment amount is less or greater than Expected amount';
                    }else{
                        try {
                            $user->createRecord('frame_sale', array(
                                'client_name' => Input::get('client_name'),
                                'client_phone' => Input::get('client_phone'),
                                'batch_id' => Input::get('batch_id'),
                                'brand_id' => Input::get('brand_id'),
                                'quantity' => Input::get('quantity'),
                                'pay_type' => Input::get('pay_type'),
                                'sale_date' => date('Y-m-d'),
                                'invoice' => Input::get('invoice_no'),
                                'delivery_note' => Input::get('delivery_note'),
                                'note' => Input::get('note'),
                                'status' => 1,
                                'user_id'=>$user->data()->id
                            ));
                            $lid=$override->lastRow('frame_sale','id')[0];


                            $user->createRecord('payment', array(
                                'pay_amount' => Input::get('cash'),
                                'required_amount' => $exp,
                                'pay_date' => date('Y-m-d'),
                                'status' => 1,
                                'sale_id' => $lid['id'],
                                'user_id'=>$user->data()->id
                            ));
                            $user->createRecord('payment_rec', array(
                                'pay_amount' => Input::get('cash'),
                                'pay_date' => date('Y-m-d'),
                                'sale_id' => $lid['id'],
                                'user_id'=>$user->data()->id
                            ));
                            $successMessage = 'Frame Successful Sold';

                        } catch (Exception $e) {
                            die($e->getMessage());
                        }
                    }

                }else{
                    $errorMessage='Insufficient Amount, it must be less or equal to stock batch amount';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('lens_sale')){
            $validate = $validate->check($_POST, array(
                'batch_id' => array(
                    'required' => true,
                ),
                'lens_type' => array(
                    'required' => true,
                ),
                'lens_category' => array(
                    'required' => true,
                ),
                'lens_power' => array(
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
                'cash' => array(
                    'required' => true,
                ),
                'invoice_no' => array(
                    'required' => true,
                ),
                'delivery_note' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {$avl=0;$sld=0;
                $assigned_stock=$override->selectData5('stock_batch_lens','batch_id',Input::get('batch_id'),'lens_type',Input::get('lens_type'),'lens_power',Input::get('lens_power'),'lens_cat',Input::get('lens_category'),'status',1);
                $stocks_sold=$override->getSumV5('assigned_stock_lens','quantity','batch_id',Input::get('batch_id'),'lens_type',Input::get('lens_type'),'lens_cat',Input::get('lens_category'),'lens_power',Input::get('lens_power'),'status',1);
                $avl=$assigned_stock[0]['quantity'] - $stocks_sold[0]['SUM(quantity)'];
                //start here check if its necessary to use lens_power & lens_cat
                $price=$override->selectData4('stock_batch_lens','batch_id',Input::get('batch_id'),'lens_type',Input::get('lens_type'),'lens_cat',Input::get('lens_category'),'lens_power',Input::get('lens_power'))[0];
                $exp=Input::get('quantity')*$price['cost'];
//                $invoice = $random->get_rand_numbers(6);
//                $user->updateRecord('frame_sale',array('invoice'=>$invoice),3);
//                $checkInvNo = $override->get('frame_sale','invoice',$invoice);
//                while($override->unique('frame_sale','invoice',$invoice) == true){
//                    $invoice = $random->get_rand_numbers(6);
//                }
                if(Input::get('quantity') <= $avl){
                    if(Input::get('cash') < $exp || Input::get('cash') > $exp){
                        $errorMessage='Payment amount is less or greater than Expected amount';
                    }else{
                        try {
                            $user->createRecord('lens_sale', array(
                                'client_name' => Input::get('client_name'),
                                'client_phone' => Input::get('client_phone'),
                                'batch_id' => Input::get('batch_id'),
                                'lens_type' => Input::get('lens_type'),
                                'lens_cat' => Input::get('lens_category'),
                                'lens_power' => Input::get('lens_power'),
                                'quantity' => Input::get('quantity'),
                                'pay_type' => Input::get('pay_type'),
                                'sale_date' => date('Y-m-d'),
                                'invoice' => Input::get('invoice_no'),
                                'delivery_note' => Input::get('delivery_note'),
                                'note' => Input::get('note'),
                                'status' => 1,
                                'user_id'=>$user->data()->id
                            ));
                            $lid=$override->lastRow('lens_sale','id')[0];


                            $user->createRecord('payment_lens', array(
                                'pay_amount' => Input::get('cash'),
                                'required_amount' => $exp,
                                'pay_date' => date('Y-m-d'),
                                'status' => 1,
                                'sale_id' => $lid['id'],
                                'user_id'=>$user->data()->id
                            ));
                            $user->createRecord('payment_lens_rec', array(
                                'pay_amount' => Input::get('cash'),
                                'pay_date' => date('Y-m-d'),
                                'sale_id' => $lid['id'],
                                'user_id'=>$user->data()->id
                            ));
                            $successMessage = 'Lens Successful Sold';

                        } catch (Exception $e) {
                            die($e->getMessage());
                        }
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
        elseif (Input::get('frame_sale_cus')){
            $validate = $validate->check($_POST, array(
                'batch_id' => array(
                    'required' => true,
                ),
                'brand_id' => array(
                    'required' => true,
                ),
                'customer' => array(
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
                'invoice_no' => array(
                    'required' => true,
                ),
                'delivery_note' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {$avl=0;$sld=0;
                $assigned_stock=$override->selectData('assigned_stock','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'),'user_id',$user->data()->id);
                $stocks_sold=$override->getSumV3('frame_sale','quantity','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'),'user_id',$user->data()->id);
                $avl=$assigned_stock[0]['quantity'] - $stocks_sold[0]['SUM(quantity)'];
                $price=$override->getNews('stock_batch','batch_id',Input::get('batch_id'),'brand_id',Input::get('brand_id'))[0];
                $exp=Input::get('quantity')*$price['cost'];
//                $invoice = $random->get_rand_numbers(6);
//                $user->updateRecord('frame_sale',array('invoice'=>$invoice),3);
//                $checkInvNo = $override->get('frame_sale','invoice',$invoice);
//                while($override->unique('frame_sale','invoice',$invoice) == true){
//                    $invoice = $random->get_rand_numbers(6);
//                }
                if(Input::get('quantity') <= $avl){
                    try {
                        $user->createRecord('frame_sale', array(
                            'client_name' => '',
                            'client_phone' => Input::get('client_phone'),
                            'batch_id' => Input::get('batch_id'),
                            'brand_id' => Input::get('brand_id'),
                            'quantity' => Input::get('quantity'),
                            'pay_type' => Input::get('pay_type'),
                            'sale_date' => date('Y-m-d'),
                            'invoice' => Input::get('invoice_no'),
                            'delivery_note' => Input::get('delivery_note'),
                            'customer_id' => Input::get('customer'),
                            'note' => Input::get('note'),
                            'status' => 1,
                            'user_id'=>$user->data()->id
                        ));

                        $lid=$override->lastRow('frame_sale','id')[0];
                        if(Input::get('cash') == $exp){$status=1;}else{$status=0;}
                        $user->createRecord('payment', array(
                            'pay_amount' => Input::get('cash'),
                            'required_amount' => $exp,
                            'pay_date' => date('Y-m-d'),
                            'status' => $status,
                            'customer_id' => Input::get('customer'),
                            'sale_id' => $lid['id'],
                            'user_id'=>$user->data()->id
                        ));
                        $user->createRecord('payment_rec', array(
                            'pay_amount' => Input::get('cash'),
                            'pay_date' => date('Y-m-d'),
                            'sale_id' => $lid['id'],
                            'customer_id' => Input::get('customer'),
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
        elseif (Input::get('lens_sale_cus')){
            $validate = $validate->check($_POST, array(
                'batch_id' => array(
                    'required' => true,
                ),
                'lens_type' => array(
                    'required' => true,
                ),
                'lens_category' => array(
                    'required' => true,
                ),
                'lens_power' => array(
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
                'invoice_no' => array(
                    'required' => true,
                ),
                'delivery_note' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {$avl=0;$sld=0;
                $assigned_stock=$override->selectData5('stock_batch_lens','batch_id',Input::get('batch_id'),'lens_type',Input::get('lens_type'),'lens_power',Input::get('lens_power'),'lens_cat',Input::get('lens_category'),'status',1);
                $stocks_sold=$override->getSumV5('assigned_stock_lens','quantity','batch_id',Input::get('batch_id'),'lens_type',Input::get('lens_type'),'lens_cat',Input::get('lens_category'),'lens_power',Input::get('lens_power'),'status',1);
                $avl=$assigned_stock[0]['quantity'] - $stocks_sold[0]['SUM(quantity)'];
                //start here check if its necessary to use lens_power & lens_cat
                $price=$override->selectData4('stock_batch_lens','batch_id',Input::get('batch_id'),'lens_type',Input::get('lens_type'),'lens_cat',Input::get('lens_category'),'lens_power',Input::get('lens_power'))[0];
                $exp=Input::get('quantity')*$price['cost'];
//                $invoice = $random->get_rand_numbers(6);
//                $user->updateRecord('frame_sale',array('invoice'=>$invoice),3);
//                $checkInvNo = $override->get('frame_sale','invoice',$invoice);
//                while($override->unique('frame_sale','invoice',$invoice) == true){
//                    $invoice = $random->get_rand_numbers(6);
//                }
                if(Input::get('quantity') <= $avl){
                    try {
                        $user->createRecord('lens_sale', array(
                            'client_name' => '',
                            'client_phone' => Input::get('client_phone'),
                            'batch_id' => Input::get('batch_id'),
                            'lens_type' => Input::get('lens_type'),
                            'lens_cat' => Input::get('lens_category'),
                            'lens_power' => Input::get('lens_power'),
                            'quantity' => Input::get('quantity'),
                            'pay_type' => Input::get('pay_type'),
                            'sale_date' => date('Y-m-d'),
                            'invoice' => Input::get('invoice_no'),
                            'delivery_note' => Input::get('delivery_note'),
                            'note' => Input::get('note'),
                            'status' => 0,
                            'customer_id' => Input::get('customer'),
                            'user_id'=>$user->data()->id
                        ));
                        $lid=$override->lastRow('lens_sale','id')[0];


                        $user->createRecord('payment_lens', array(
                            'pay_amount' => Input::get('cash'),
                            'required_amount' => $exp,
                            'pay_date' => date('Y-m-d'),
                            'status' => 1,
                            'sale_id' => $lid['id'],
                            'user_id'=>$user->data()->id
                        ));
                        $user->createRecord('payment_lens_rec', array(
                            'pay_amount' => Input::get('cash'),
                            'pay_date' => date('Y-m-d'),
                            'sale_id' => $lid['id'],
                            'user_id'=>$user->data()->id
                        ));
                        $successMessage = 'Lens Successful Sold';

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
        elseif (Input::get('add_customer')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'tin' => array(
                    'required' => true,
                ),
                'phone_number' => array(
                    'required' => true,
                    'unique' => 'customer'
                ),
                'email_address' => array(
                    'required' => true,
                    'unique' => 'customer'
                ),
                'location' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $salt = $random->get_rand_alphanumeric(32);
                $password = '12345678';

                try {
                    $user->createRecord('customer', array(
                        'name' => Input::get('name'),
                        'tin' => Input::get('tin'),
                        'phone_number' => Input::get('position'),
                        'email_address' => Input::get('email_address'),
                        'location' => Input::get('location'),
                        'status' => 1,
                    ));
                    $successMessage = 'Customer Account Created Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('pay_sale')){
            $validate = $validate->check($_POST, array(
                'customer' => array(
                    'required' => true,
                ),
                'payment_batch' => array(
                    'required' => true,
                ),
                'amount' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $pid=$override->get('payment','id',Input::get('payment_batch'))[0];
                $py=$pid['pay_amount']+Input::get('amount');
                if($py <= $pid['required_amount']){
                    if($py==$pid['required_amount']){$status=1;}else{$status=0;}
                    try {
                    $user->updateRecord('payment', array(
                        'pay_amount' => $py,
                        'status' => $status,
                    ),$pid['id']);
                    $user->createRecord('payment_rec', array(
                        'pay_amount' => Input::get('amount'),
                        'pay_date' => date('Y-m-d'),
                        'sale_id' => $pid['sale_id'],
                        'user_id'=>$user->data()->id
                    ));
                        $successMessage = 'Customer Payment Added Successful';

                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{
                    $errorMessage='Payment Exceed the required Amount';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('pay_sale_lens')){
            $validate = $validate->check($_POST, array(
                'customer' => array(
                    'required' => true,
                ),
                'payment_batch' => array(
                    'required' => true,
                ),
                'amount' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $pid=$override->get('payment_lens','id',Input::get('payment_batch'))[0];
                $py=$pid['pay_amount']+Input::get('amount');
                if($py <= $pid['required_amount']){
                    if($py==$pid['required_amount']){$status=1;}else{$status=0;}
                    try {
                        $user->updateRecord('payment_lens', array(
                            'pay_amount' => $py,
                            'status' => $status,
                        ),$pid['id']);
                        $user->createRecord('payment_lens_rec', array(
                            'pay_amount' => Input::get('amount'),
                            'pay_date' => date('Y-m-d'),
                            'sale_id' => $pid['sale_id'],
                            'user_id'=>$user->data()->id
                        ));
                        $successMessage = 'Customer Payment Added Successful';

                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{
                    $errorMessage='Payment Exceed the required Amount';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('frame_return')){
            $validate = $validate->check($_POST, array(
                'quantity' => array(
                    'required' => true,
                ),
                'details' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {

                if(Input::get('qty') >= Input::get('quantity')){
                    try {
                        $qnty=Input::get('qty') - Input::get('quantity');
                        $user->updateRecord('frame_sale', array(
                            'quantity' => $qnty,
                        ),Input::get('sid'));

                        $user->createRecord('returned_frame', array(
                            'quantity' => Input::get('quantity'),
                            'details' => Input::get('details'),
                            'return_date' => date('y-m-d'),
                            'sale_id' => Input::get('sid'),
                            'status' => 1,
                            'user_id'=>$user->data()->id
                        ));

                        $successMessage = 'Frame Successful returned';

                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{
                    $errorMessage='Quantity returned must be less or equal to sold amount';
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
                <?php if($_GET['id'] == 1 && $user->data()->position == 1){?>
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
                                        <select name="position" style="width: 100%;">
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
                <?php }elseif ($_GET['id'] == 2 && $user->data()->position == 1){?>
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
                <?php }elseif ($_GET['id'] == 3 && $user->data()->position == 1){?>
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
                <?php }elseif ($_GET['id'] == 4 && $user->data()->position == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Assign Frame Stock</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Staff</div>
                                    <div class="col-md-9">
                                        <select name="user_id"  style="width: 100%;" required>
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
                                        <select name="batch_id"  style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getNews('batch','batch_type',1,'status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
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

                                <div class="footer tar">
                                    <input type="submit" name="assign_stock_frame" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 5 && $user->data()->position == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Frame Stock Batch</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" style="width: 100%;" required>
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
                                        <select name="brand_id" id="s2_1" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getNews('batch','batch_type',1,'status',1) as $brand){?>
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
                                    <input type="submit" name="add_batch_stock_frame" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 6 && $user->data()->position == 1){?>
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
                                    <div class="col-md-3">Batch Type</div>
                                    <div class="col-md-9">
                                        <select name="batch_type" style="width: 100%;" required>
                                            <option value="">Select Type</option>
                                            <option value="1">Frame</option>
                                            <option value="2">Lens</option>
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
                            <h1>Sales Frame to Cash Customers</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getNews('batch','batch_type',1,'status',1) as $batch){?>
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
                                        <select name="pay_type"  style="width: 100%;" required>
                                            <option value="">Select Method</option>
                                            <option value="1">Cash</option>
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
                                    <div class="col-md-3">Invoice No.:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="invoice_no" id="invoice"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Delivery Note No.:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="delivery_note" id="d_note"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Cash Amount:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="cash" id="cash"/>
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
                <?php }elseif ($_GET['id'] == 8 && $user->data()->position == 1){?>
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
                <?php }elseif ($_GET['id'] == 9){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Sales Frame to Credit Customer</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getNews('batch','batch_type',1,'status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
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
                                    <div class="col-md-3">Select Customer</div>
                                    <div class="col-md-9">
                                        <select name="customer"  style="width: 100%;" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach ($override->getData('customer') as $customer){?>
                                                <option value="<?=$customer['id']?>"><?=$customer['name']?></option>
                                            <?php }?>
                                        </select>
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
                                        <select name="pay_type"  style="width: 100%;" required>
                                            <option value="">Select Method</option>
                                            <option value="1">Cash</option>
                                            <option value="2">Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Quantity:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="quantity" id="quantity"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Invoice No.:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="invoice_no" id="invoice"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Delivery Note No.:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="delivery_note" id="d_note"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Cash Amount:</div>
                                    <div class="col-md-9">
                                        <input value="0" class="validate[required]" type="number" name="cash" id="cash"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Note:</div>
                                    <div class="col-md-9"><textarea name="note" placeholder="Sales notes..."></textarea></div>
                                </div>
                                <div class="footer tar">
                                    <input type="submit" name="frame_sale_cus" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 10 && $user->data()->position == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Customer</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Name/Business Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="name" id="Business"/>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">TIN:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="tin" id="tin"/>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Phone NUmber:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="phone_number" id="phone"/>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">E-mail Address:</div>
                                    <div class="col-md-9"><input value="" class="validate[required,custom[email]]" type="text" name="email_address" id="email" />  <span>Example: someone@nowhere.com</span></div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Location:</div>
                                    <div class="col-md-9"><textarea name="location" placeholder="Customer Location..."></textarea></div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_customer" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 11){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Customer Payment For Frame</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Customer</div>
                                    <div class="col-md-9">
                                        <select name="customer" id="s2_1" style="width: 100%;" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach ($override->getNewsNoRepeat('payment','customer_id','status',0,'user_id',$user->data()->id) as $customer){
                                                $cname=$override->get('customer','id',$customer['customer_id'])[0];?>
                                                <option value="<?=$cname['id']?>"><?=$cname['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Payment Batch</div>
                                    <div class="col-md-9">
                                        <span><img src="img/loaders/loader.gif" id="wait" title="loader.gif"/></span>
                                        <select name="payment_batch" id="s2_2" style="width: 100%;" required>

                                        </select>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Amount:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="amount" id="amount"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="pay_sale" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 12 ){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Returned Frame</h1>
                        </div>
                        <div class="block-fluid">
                            <?php $data=$override->get('frame_sale','id',$_GET['sid'])[0];
                            if($data['customer_id']){$cname=$override->get('customer','id',$data['customer_id'])[0]['name'];}else{$cname=$data['client_name'];}?>
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Invoice No.:</div>
                                    <div class="col-md-9">
                                        <input value="<?=$data['invoice']?>"  type="text" name="invoice_no" id="invoice" disabled/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Delivery Note No.:</div>
                                    <div class="col-md-9">
                                        <input value="<?=$data['delivery_note']?>"  type="text" name="delivery_note" id="d_note" disabled/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Client Name:</div>
                                    <div class="col-md-9">
                                        <input value="<?=$cname?>"  type="text" name="client_name" id="client_name" disabled/>
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
                                    <div class="col-md-9"><textarea name="details" class="validate[required]" placeholder="Why frames are returned..."></textarea></div>
                                </div>
                                <div class="footer tar">
                                    <input type="hidden" name="qty" value="<?=$data['quantity']?>">
                                    <input type="hidden" name="sid" value="<?=$_GET['sid']?>">
                                    <input type="submit" name="frame_return" value="Submit" class="btn btn-default">
                                </div>
                            </form>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 13){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Lens Stock Batch</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getNews('batch','batch_type',2,'status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens</div>
                                    <div class="col-md-9">
                                        <select name="lens_type" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getData('lens_type') as $lensType){?>
                                                <option value="<?=$lensType['id']?>"><?=$lensType['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens Type</div>
                                    <div class="col-md-9">
                                        <select name="lens_category" style="width: 100%;" >
                                            <option value="">Select</option>
                                            <option value="cylinder">Cylinder</option>
                                            <option value="sphere">Sphere</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Lens Power:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="lens_power" id="lens_power"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Quantity:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="quantity" id="quantity"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Price per Lens:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="price" id="price"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_batch_stock_lens" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 14){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Assign Lens Stock</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Staff</div>
                                    <div class="col-md-9">
                                        <select name="user_id"  style="width: 100%;" required>
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
                                        <select name="batch_id"  style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getNews('batch','batch_type',2,'status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens</div>
                                    <div class="col-md-9">
                                        <select name="lens_type" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getData('lens_type') as $lensType){?>
                                                <option value="<?=$lensType['id']?>"><?=$lensType['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens Type</div>
                                    <div class="col-md-9">
                                        <select name="lens_category" style="width: 100%;" >
                                            <option value="">Select</option>
                                            <option value="cylinder">Cylinder</option>
                                            <option value="sphere">Sphere</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens Power</div>
                                    <div class="col-md-9">
                                        <select name="lens_power" id="s2_i" style="width: 100%;" >
                                            <option value="">Select</option>
                                            <?php foreach ($override->getDataTable('stock_batch_lens','lens_power') as $power){?>
                                                <option value="<?=$power['lens_power']?>"><?=$power['lens_power']?></option>
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
                                    <input type="submit" name="assign_stock_lens" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 15){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Sales Lens to Cash Customers</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getNews('batch','batch_type',2,'status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens</div>
                                    <div class="col-md-9">
                                        <select name="lens_type" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getData('lens_type') as $lensType){?>
                                                <option value="<?=$lensType['id']?>"><?=$lensType['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens Type</div>
                                    <div class="col-md-9">
                                        <select name="lens_category" style="width: 100%;" >
                                            <option value="">Select</option>
                                            <option value="cylinder">Cylinder</option>
                                            <option value="sphere">Sphere</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens Power</div>
                                    <div class="col-md-9">
                                        <select name="lens_power" id="s2_i" style="width: 100%;" >
                                            <option value="">Select</option>
                                            <?php foreach ($override->getDataTable('stock_batch_lens','lens_power') as $power){?>
                                                <option value="<?=$power['lens_power']?>"><?=$power['lens_power']?></option>
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
                                        <select name="pay_type"  style="width: 100%;" required>
                                            <option value="">Select Method</option>
                                            <option value="1">Cash</option>
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
                                    <div class="col-md-3">Invoice No.:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="invoice_no" id="invoice"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Delivery Note No.:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="delivery_note" id="d_note"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Cash Amount:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="cash" id="cash"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Note:</div>
                                    <div class="col-md-9"><textarea name="note" placeholder="Sales notes..."></textarea></div>
                                </div>
                                <div class="footer tar">
                                    <input type="submit" name="lens_sale" value="Submit" class="btn btn-default">
                                </div>
                            </form>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 16){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Sales Lens to Credit Customer</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Batch</div>
                                    <div class="col-md-9">
                                        <select name="batch_id" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getNews('batch','batch_type',2,'status',1) as $batch){?>
                                                <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens</div>
                                    <div class="col-md-9">
                                        <select name="lens_type" style="width: 100%;" required>
                                            <option value="">Select</option>
                                            <?php foreach ($override->getData('lens_type') as $lensType){?>
                                                <option value="<?=$lensType['id']?>"><?=$lensType['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens Type</div>
                                    <div class="col-md-9">
                                        <select name="lens_category" style="width: 100%;" >
                                            <option value="">Select</option>
                                            <option value="cylinder">Cylinder</option>
                                            <option value="sphere">Sphere</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Lens Power</div>
                                    <div class="col-md-9">
                                        <select name="lens_power" id="s2_i" style="width: 100%;" >
                                            <option value="">Select</option>
                                            <?php foreach ($override->getDataTable('stock_batch_lens','lens_power') as $power){?>
                                                <option value="<?=$power['lens_power']?>"><?=$power['lens_power']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Customer</div>
                                    <div class="col-md-9">
                                        <select name="customer"  style="width: 100%;" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach ($override->getData('customer') as $customer){?>
                                                <option value="<?=$customer['id']?>"><?=$customer['name']?></option>
                                            <?php }?>
                                        </select>
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
                                        <select name="pay_type"  style="width: 100%;" required>
                                            <option value="">Select Method</option>
                                            <option value="1">Cash</option>
                                            <option value="2">Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Quantity:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="quantity" id="quantity"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Invoice No.:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="invoice_no" id="invoice"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Delivery Note No.:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="delivery_note" id="d_note"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Cash Amount:</div>
                                    <div class="col-md-9">
                                        <input value="0" class="validate[required]" type="number" name="cash" id="cash"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Note:</div>
                                    <div class="col-md-9"><textarea name="note" placeholder="Sales notes..."></textarea></div>
                                </div>
                                <div class="footer tar">
                                    <input type="submit" name="lens_sale_cus" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 17){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Customer Payment For Lens</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Customer</div>
                                    <div class="col-md-9">
                                        <select name="customer" id="s2_2" style="width: 100%;" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach ($override->getNewsNoRepeat('payment_lens','customer_id','status',0,'user_id',$user->data()->id) as $customer){
                                                $cname=$override->get('customer','id',$customer['customer_id'])[0];?>
                                                <option value="<?=$cname['id']?>"><?=$cname['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Select Payment Batch</div>
                                    <div class="col-md-9">
                                        <span><img src="img/loaders/loader.gif" id="wait" title="loader.gif"/></span>
                                        <select name="payment_batch" id="cus" style="width: 100%;" required>

                                        </select>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Amount:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="amount" id="amount"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="pay_sale_lens_lens" value="Submit" class="btn btn-default">
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
    $(document).ready(function(){
        $('#wait').hide();
        $('#s2_1').change(function(){
            var getUid = $(this).val();
            $('#wait').show();
            $.ajax({
                url:"process.php?cnt=pay",
                method:"GET",
                data:{getUid:getUid},
                success:function(data){
                    $('#s2_2').html(data);
                    $('#wait').hide();
                }
            });

        });
        $('#s2_2').change(function(){
            var getUid = $(this).val();
            $('#wait').show();
            $.ajax({
                url:"process.php?cnt=payLens",
                method:"GET",
                data:{getUid:getUid},
                success:function(data){
                    $('#cus').html(data);
                    $('#wait').hide();
                }
            });

        });
    });
</script>
</body>

</html>

