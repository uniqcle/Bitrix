<!-- Подключение ядра без визуальной части -->
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$urlRoot = __DIR__ . '/data/data.xml';
//Подключаем объект XML
$xmlObj = simplexml_load_file($urlRoot);

$elemObj = new CIBlockElement;

$countProduct = 0;
$countOffer = 0;


foreach($xmlObj->product as $product) {
    //Для удобства работы переводим объект в массив
    $product = xml2array($product);
    //Заполняем свойства, кот. необходимо предварительно добавить в админке
    $arProps = [
        'SYKNO'    => $product['SYKNO']['VARIANT'],
        'VIKRASKA' => $product['VIKRASKA']['VARIANT'],
        'IMAGES'   => $product['IMAGES']['OPTION'],
    ];

    foreach ($product['IMAGES']['OPTION'] as $img) {
        $arProps['IMAGES'][] = CFile::MakeFileArray($img);
    }

    //Заполняем стандартные поля Элемента
    $arFields = [
        'NAME'              => $product['NAME'],
        'IBLOCK_ID'         => CONST_IBLOCK_ID,  //ID инфоблока
        'IBLOCK_SECTION_ID' => CONST_SECTION_ID, //ID раздела
        'CODE'              => $product['CODE'],
        'DETAIL_TEXT'       => $product['DESCRIPTION'],
        'PROPERTY_VALUES'   => $arProps,
        'ACTIVE'            => 'Y'
    ];

    //Добавили элемент
    if ($elemID = $elemObj->Add($arFields)) {

        //Элемент инфоблока создан.
        //Теперь превращаем его в товар. Для примера зададим товару общее количество и вес.
        $arFields = [
            'ID'                  => $elemID,
            'QUANTITY'            => 1,
            'PURCHASING_CURRENCY' => 'USD',
            'WEIGHT'              => 100
        ];

        //Добавили св-ва торг. каталога
        CCatalogProduct::Add($arFields);

        $offerElem = new CIBlockElement();


        //Обработка торговых предложений
        foreach ($product['OFFERS']['OFFER'] as $offer) {

            $offer = xml2array($offer);

            //Свойство торгового предложения
            $propsOffer = [
                'CML2_LINK'  => $elemID,
                'TABLE_TYPE' => $offer['TABLE_TYPE'],
                'SIZE_FIELD' => $offer['SIZE_FIELD'],
                'QTY_LEGS'   => $offer['QTY_LEGS']
            ];

            //Формирует название торг. предложения
            $nameOffer = implode(',', [
                $offer['TABLE_TYPE'],
                $offer['SIZE_FIELD'],
                $offer['QTY_LEGS']
            ]);


            $arFields = [
                'NAME'            => $nameOffer,
                'IBLOCK_ID'       => CONST_OFFERS_IBLOCK_ID,
                'PROPERTY_VALUES' => $propsOffer,
                'ACTIVE'          => 'Y'

            ];

            if ($elemOfferID = $offerElem->Add($arFields)) {

                //Добавляем свойств торг. каталога для торгового предложения
                $arrOffersFields = [
                    'ID'       => $elemOfferID,
                    'QUANTITY' => 10,
                    'WEIGHT'   => $offer['VES']
                ];

                CCatalogProduct::Add($arrOffersFields);

                // добавляем цены нашему товару
                // собираем массив
                $arFields = Array(
                    "CURRENCY"         => "RUB",       // валюта
                    "PRICE"            => $price,      // значение цены
                    "CATALOG_GROUP_ID" => 1,           // ID типа цены
                    "PRODUCT_ID"       => $elemOfferID,  // ID товара (элемента инфоблока)
                );
                // добавляем цену
                CPrice::Add( $arFields );

                //Теперь добавим количество по складам. Пример для склада с ID 1:
                $arFields = Array(
                    "PRODUCT_ID" => $ID,
                    "STORE_ID"   => $storeID,
                    "AMOUNT"     => $rest,
                );
                CCatalogStoreProduct::Add($arFields);

                $countOffer++;

            } else {

                echo $elemOfferID->LAST_ERROR;

            }

            $countProduct++;

        }

    }
}

echo 'Товаров добавлено '.$countProduct. '</br>';
echo 'Торговых предложений добавлено '.$countOffer. '</br>';


