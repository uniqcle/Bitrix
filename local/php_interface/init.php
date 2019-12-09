<?
/*
 * В данном файле только подключения. Никаких реализаций и функций
 * */

#---------------------------------------------------------------
#ОБЩИЕ ФАЙЛЫ, КОТОРЫЕ МОГУТ ХОДИТЬ У ВАС ОТ ПРОЕКТА К ПРОЕКТУ

#Подключаем автоподключение классов bitrix
require_once(__DIR__ . '/me/autoloadBxClass.php');

#свои мини функции в глобальном неймспейсе
require_once(__DIR__ . '/me/functions.php');

#класс для логгирования soap
require_once(__DIR__ . '/me/class/SoapClientLogging.php');

#класс для подключению к soap
require_once(__DIR__ . '/me/class/SoapConnect.php');

#---------------------------------------------------------------
#ДАЛЕЕ ПОДКЛЮЧАЕМ ВСЕ, ЧТО ОТНОСИТСЯ К КОНКРЕТНО ЭТОМУ ПРОЕКТУ

#констаны
require_once(__DIR__ . '/skillbox/constant.php');

#автоподключение классов Highload блоков
require_once(__DIR__ . '/skillbox/autoloadHighLoadIBlock.php');

#автоподключение классов проекта. Обычно такие пишут для облегчения повторяющихся операций
require_once(__DIR__ . '/skillbox/autoloadProjectClass.php');