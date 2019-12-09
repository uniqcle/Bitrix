<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
pre('start');
#-------------ТУТ ВАШ КОД
#Что нужно сделать: Загрузить XML в PHP (рекомендую simplexml_load_file). Далее вывести на экран каждый элемент со свойствами
#Цель: Аккуратно окунуть Вас в работу с самим PHP и посмотреть у кого возникнут сложности с чистым PHP. Далее мы имея данные в массивах/обьектах научимся загружать это непосредственно в Bitrix.
#-------------КОНЕЦ КОДА

$rootToFile =  __DIR__.'/data/data.xml';

getXmlToScreen($rootToFile);

function getXmlToScreen($rootToFile){

    if (file_exists($rootToFile)) {
        $xml = simplexml_load_file($rootToFile );

        foreach($xml as $item):

            echo $item->OLDID.'</br>';
            echo $item->NAME.'</br>';
            echo $item->DESCRIPTION.'</br>';

            // <IMAGES>
            foreach($item->IMAGES->children() as $img){
                echo $img.'</br>';
            }

            // <SYKNO>
            echo 'Сукно: </br>';
            foreach($item->SYKNO->children() as $sykno){
                echo 'Аттрибут '.$sykno->attributes(). ' Значение = '. $sykno .' </br>';
            }

            //VYKRASKA
            echo 'Выкраска: </br>';
            foreach($item->VIKRASKA->children() as $vykraska){
                echo ' Аттрибут ' .$vykraska->attributes(). ' Значение '. $vykraska. '</br>';
            }


            // <OFFERS>
            if(isset($item->OFFERS)) {
                foreach ($item->OFFERS as $offer) {

                    foreach ($offer->children() as $item) {
                        echo '</br>' . 'Предложение: </br>';
                        echo $item->SIZE_FIELD . '</br>';
                        echo $item->GAME_TYPE . '</br>';
                        echo $item->TABLE_MATERIAL . '</br>';
                        echo $item->TABLE_TYPE . '</br>';
                        echo $item->QTY_LEGS . '</br>';
                        echo $item->VES . '</br>';
                        echo $item->PRICE . '</br>';
                        echo $item->ART . '</br>';
                    }

                }
            }

            echo '<hr>';

        endforeach;

        // print_r($xml);
    } else {
        exit('Не удалось открыть файл');
    }

}



pre('done.');