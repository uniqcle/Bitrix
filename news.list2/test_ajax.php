<?php include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$order_count = (int) $_POST['order-count']; 
$order_id = (int) $_POST['order-id']; 

CModule::IncludeModule("catalog"); 

Add2BasketByProductID($order_id, $order_count); 

 