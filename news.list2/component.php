<?php  
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
 



if (CModule::IncludeModule("sale"))

$arFilter = Array(
        "USER_ID" => $USER->GetID(),
    );

$db_sales = CSaleOrder::GetList(array(), $arFilter);

while ($ar_sales = $db_sales->Fetch())
{
    
     

     //debug( $ar_sales["ID"] ); 

     $dbBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $ar_sales["ID"]), false, false, array());

      while ($arItems = $dbBasketItems->Fetch()) {

       $macciw[] = $arItems[PRODUCT_ID];

      }

     // debug( $macciw );  

      foreach( $macciw as $arItem): 

      	$arResultMacciw[] = $arItem; 

      endforeach; 

}

//debug( $arResultMacciw ); 

 
$result = array_unique( $arResultMacciw );






if(!CModule::IncludeModule("iblock"))

return; 

 
foreach( $result as $arItem ): 
 
$arSelect = ["ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DETAIL_PAGE_URL", "DETAIL_PICTURE", "PROPERTY_*"];

$arFilter = Array("ID"=>$arItem );

$res = CIBlockElement::GetList(
	Array("SORT"=>"ASC"), 
	$arFilter, 
	false, 
	Array("nPageSize"=>5), 
	$arSelect
);


while($obj = $res->GetNextElement()){

	$arItem = $obj->GetFields(); 

	//Получаем его свойства
	$arItem['PROPERTIES'] = $obj->GetProperties(); 


	//Получаем данные по товару
	$arItem['SHOP'] = CCatalogProduct::GetByID( $arItem['ID'] );


	   //ОБработка изображений
       if( $arItem['DETAIL_PICTURE'] ){

	       	$arFileTmp = CFile::ResizeImageGet(
	       		$arItem['DETAIL_PICTURE'], 
	       		Array("width"=>50, "height"=>50), 
	       		BX_RESIZE_IMAGE_EXACT , 
	       		false
	       	); 

	       	$arSize = getimagesize($_SERVER["DOCUMENT_ROOT"].$arFileTmp["src"]); 

	       	$arItem["PREVIEW_IMG"] = [
	       		"SRC" => $arFileTmp["src"],
	       		"WIDTH"  => IntVal($arSize[0]),
	       		"HEIGHT" => IntVal($arSize[1]), 
	       	]; 

       }

       

       $arItems[] = $arItem;


/*echo "<pre>"; print_r($arFields["NAME"]); echo "</pre>";
echo "<pre>"; print_r($arFields["DETAIL_PAGE_URL"]); echo "</pre>"; */

}

endforeach; 
 

 
$arResult["ITEMS"] = $arItems; 

 
/* debug($arResult); 
 

die;   */
 
$this->IncludeComponentTemplate();

 

//echo $result->NavPrint();

?>