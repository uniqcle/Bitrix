<?php require($_SERVER["DOCUMENT_ROOT"]. '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;

Loader::includeModule('highloadblock');

$arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById(6)->fetch();
$obEntity = Bitrix\HighloadBlock\HighloadBlockTable::compileEntity($arHLBlock);

$strEntityDataClass = $obEntity->getDataClass();

$url = $_SERVER["DOCUMENT_ROOT"] . "/local/integration/data/data.xml";

$xmlObj = simplexml_load_file($url);

    foreach($xmlObj as $product){

        //ДОБАВЛЕНИЕ В ИНФОБЛОК VIKRASKA
        $rsData = $strEntityDataClass::getList(array(
            "select" => array('UF_NAME', 'UF_DESCRIPTION'),
            "filter" => [],
            "order" => []
        ));

        if ($data = $rsData->Fetch());
        else {
            //ветка, когда элемент не найден
            foreach ($product->VIKRASKA->children() as $vikraska) {

                // Массив полей для добавления
                $data = array(
                    "UF_NAME" => $vikraska->attributes(),
                    "UF_DESCRIPTION" => $vikraska
                );


                $result = $strEntityDataClass::add($data);
            }

        }
    }



