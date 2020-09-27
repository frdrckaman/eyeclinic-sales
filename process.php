<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
if($_GET['cnt'] == 'pay'){
    $payments=$override->getNews('payment','status',0,'customer_id',$_GET['getUid']);
    foreach ($payments as $payment){
        $sale=$override->get('frame_sale','id',$payment['sale_id'])[0];
        $batch=$override->get('batch','id',$sale['batch_id'])[0];
        $brand=$override->get('frame_brand','id',$sale['brand_id'])[0];
        $pay=$payment['required_amount']-$payment['pay_amount']?>
        <option value="<?=$payment['id']?>"><?='Batch Name:'.$batch['name'].' | Brand:'.$brand['name'].' | Quantity:'.$sale['quantity']
            .' | Invoice:'.$sale['invoice'].' | Delivery Note:'.$sale['delivery_note'].' | Amount to be Paid:'.number_format($pay)?></option>
<?php }}elseif ($_GET['cnt'] == 'payLens'){?>
    <option value="">Select</option>
    <?php
$payments=$override->getNews('payment_lens','status',0,'customer_id',$_GET['getUid']);
foreach ($payments as $payment){
$sale=$override->get('lens_sale','id',$payment['sale_id'])[0];
$batch=$override->get('batch','id',$sale['batch_id'])[0];
$lensType=$override->get('lens_type','id',$sale['lens_type'])[0];
$pay=$payment['required_amount']-$payment['pay_amount']?>
<option value="<?=$payment['id']?>"><?='Batch Name:'.$batch['name'].' | Lens:'.$lensType['name'].' | Lens Categ: '.$sale['lens_cat'].' | Lens Power:'.$sale['lens_power']. ' | Quantity:'.$sale['quantity']
    .' | Invoice:'.$sale['invoice'].' | Delivery Note:'.$sale['delivery_note'].' | Amount to be Paid:'.number_format($pay)?></option>
<?php }}?>