<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

//Установка
$APPLICATION->SetPageProperty('title', 'Заголовок окна браузера');
$APPLICATION->SetTitle("Тайтл страницы");   


//Вывод
$APPLICATION->ShowTitle(); //$APPLICATION->SetPageProperty('title', 'Заголовок окна браузера');   
echo '<hr>'; 
$APPLICATION->ShowTitle( false ); //Вывод $APPLICATION->SetTitle("Тайтл страницы");  

?>

 


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>