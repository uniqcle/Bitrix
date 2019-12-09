<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$urlRoot = __DIR__ . '/data/data.xml';

$xmlObject = simplexml_load_file($urlRoot);

$elem = new CIBlockElement;

foreach($xmlObject->product as $product){

    $product = xml2array($product);

    //Свойства элемента
    $props = array(
        'SYKNO' => $product['SYKNO']['VARIANT'],
        'IMAGES' => $product['IMAGES']['OPTION'],
        'VIKRASKA' => $product['VIKRASKA']['VARIANT'],
    );

     foreach($product['IMAGES']['OPTION'] as $img){
        $props['IMAGES'][] = CFile::MakeFileArray($img);
    }

    $arFields = [
        'ID_START' => $product['OLDID'],
        'NAME' => $product['NAME'],
        'CODE' => $product['CODE'],
        'IBLOCK_SECTION_ID' => CONST_SECTION_ID,
        'DETAIL_TEXT' => $product['DESCRIPTION'],
        'IBLOCK_ID' => CONST_IBLOCK_ID,
        'PROPERTY_VALUES' => $props,
        'ACTIVE' => 'Y'
    ];


    //Если элемент добавлен
    if( $prodID = $elem->Add($arFields) ) {

        //Добавление полей, кот. отвечают за каталог
        $arFields = [
            'ID' => $prodID,
            'QUANTITY' => 111,
            'WEIGHT' => 100
        ];

        //ДОбавляем свойства торг. каталога
        CCatalogProduct::Add($arFields);


        //Добавляем торговые предложения
        foreach ($product['OFFERS']['OFFER'] as $offer) {


            $offerName = implode(",",
                [
                    $offer->GAME_TYPE,
                    $offer->TABLE_TYPE
                ]
            );

            //Добавляем офер
            $propsOffer = [
                'CML2_LINK' => $prodID,
                'SIZE_FIELD' => $offer->SIZE_FIELD,
            ];

            $arOffersFields = [
                'NAME' => $offerName,
                'IBLOCK_ID' => 5,
                'PROPERTY_VALUES' => $propsOffer,
                'ACTIVE' => 'Y'
            ];




            if ($offerID = $elem->Add($arOffersFields)) {

                pre('Добавлено торг. предложение ' . $offerID);

            } else {
                pre($offerObj->LAST_ERROR);
            }



        }



    }



}

