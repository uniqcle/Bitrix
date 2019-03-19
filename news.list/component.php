<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
 
 
 if( CModule::IncludeModule("iblock") ){

 	// debug($arParams); 

 
     $arSelect = ["ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DETAIL_PAGE_URL", "DETAIL_PICTURE", "PROPERTY_*"];

     $arFilter = [
     	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"], 
     	"IBLOCK_ID" => $arParams["IBLOCK_ID"], 
     	"ACTIVE"=>"Y", 
     	"PROPERTY_TOVARVSEGMENTENOVINKI_VALUE" => "Да"
     ];


     $result = CIBlockElement::GetList(
       [ "SORT" => "DESC"],  
       $arFilter,  
       false,  
       ["nPageSize" => false],  
       $arSelect
     );

   
     while($obj = $result->GetNextElement()){
      
       $arItem = $obj->GetFields(); 

    
       $arItem['PROPERTIES'] = $obj->GetProperties(); 

      
       if( $arItem['DETAIL_PICTURE'] ){

	       	$arFileTmp = CFile::ResizeImageGet(
	       		$arItem['DETAIL_PICTURE'], 
	       		Array("width"=>$arParams["SMALL_WIDTH"], "height"=>$arParams["SMALL_HEIGHT"]), 
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

     }


}   


$arResult["ITEMS"] = $arItems; 

 

$this->IncludeComponentTemplate();




?>