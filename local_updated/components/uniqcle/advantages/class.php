<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

class AdvantagesComponent extends CBitrixComponent
{

    // Обработка параметров
    function onPrepareComponentParams($params){
        if ($params['CACHE_TYPE'] == 'Y' || $params['CACHE_TYPE'] == 'A') {
            $params['CACHE_TIME'] = intval($params['CACHE_TIME']);
        } else {
            $params['CACHE_TIME'] = 0;
        }

        return $params;
    }

    public function executeComponent(){

         try {
            if ($this->startResultCache(false)) {

                $this->checkModules();
                $this->prepareData();

                $this->doAction();

                $this->includeComponentTemplate();

            }
          } catch (Exception $e) {         //Если произошла к-л ошибка, выводим ошибку
             $this->AbortResultCache();
             $this->arResult['ERROR'] = $e->getMessage();
         }
    }

    protected function checkModules()
    {
        #подключаем нужные модули
        if (!Loader::includeModule('iblock'))
            throw new Exception('Модуль "Инфоблоки" не установлен');
    }

    protected function prepareData()
    {
        #проверки на существования
        $this->arResult['IBLOCK'] = [];
        if ($this->arParams['IBLOCK_ID']) {
            $this->arResult['IBLOCK'] = CIBlock::GetByID($this->arParams['IBLOCK_ID'])->Fetch();
        }
        if (!$this->arResult['IBLOCK']) {
            throw new Exception('Инфоблок не найден');
        }
    }

    protected function doAction()
    {

        $this->arResult['ITEMS'] = [];

        $rs = CIBlockElement::GetList(
            ['CODE' => 'ASC'],                                               // Порядок
            ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['IBLOCK_ID']], // Фильтрация
            false,
            ['nTopCount' => $this->arParams['COUNT']],                      // Кол-во на странице
            ['IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_TEXT', 'CODE']            // Выборка
        );

        //$rs ->SelectedRowsCount();                                         // Кол-во записей, кот. вернулись

        //GetNextElement() возвращает объект
        // - Если используете GetNextElement то $arSelect не учитывается.
        // - Когда используем свойства DETAIL PAGE URL
        // - Когда выбираем св-ва с множественным типом
        // - Когда используем расчетные поля SEO(meta, alt)
        //Такой вариант выборки очень не оптимальный и если у вас много данных выборка будет очень долгая,
        // лучший вариант это сделать выборку через Fetch и указать только нужные поля и свойства в $arSelect

        /* while( $ob = $rs->GetNextElement() ){
            $arFields = $ob->GetFields();// получаем поля инфоблока итерации
            $arProp = $ob->GetProperties();// получаем свойства итерации

            pre($arFields);
        }*/



        // Fetch возвращает массив
        while( $elem = $rs->Fetch() ){

            $this->arResult['ITEMS'][$elem['CODE']]  = $elem;

        }



    }


}