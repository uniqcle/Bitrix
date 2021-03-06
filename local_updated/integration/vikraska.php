<?php require($_SERVER["DOCUMENT_ROOT"]. '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;

Loader::includeModule('highloadblock'); 

$arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById(9)->fetch();
$obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
$strEntityDataClass = $obEntity->getDataClass();

$urlRoot = __DIR__ . '/data/data.xml';

$xmlObject = simplexml_load_file($urlRoot);


foreach($xmlObject as $item){

    $rsData = $strEntityDataClass::getList(array(
        "select" => array('UF_NAME', 'UF_DESCRIPTION'),
        "filter" => [],
        "order" => []
    ));

    //1 вар.
    if ($data = $rsData->Fetch());
    else {
        //ветка, когда элемент не найден
        foreach ($item->VIKRASKA->children() as $vikraska) {

            // Массив полей для добавления
            $data = array(
                "UF_NAME" => $vikraska->attributes(),
                "UF_DESCRIPTION" => $vikraska
            );


            $result = $strEntityDataClass::add($data);

            echo "Элемент добавлен </br>";

        }
    }
}


