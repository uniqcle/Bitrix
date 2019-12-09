<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

class FirstComponent extends CBitrixComponent {

    // Обработка параметров
    function onPrepareComponentParams($params){

        if ($params['CACHE_TYPE'] == 'Y' || $params['CACHE_TYPE'] == 'A') {
            $params['CACHE_TIME'] = intval($params['CACHE_TIME']);
        } else {
            $params['CACHE_TIME'] = 0;
        }

        #проверка входных параметров
        $params['IBLOCK_ID'] = isset($params['IBLOCK_ID']) && intval($params['IBLOCK_ID']) > 0 ? intval($params['IBLOCK_ID']) : 0;

        $params['SLIDER_COUNT'] = isset($params['SLIDER_COUNT']) && intval($params['SLIDER_COUNT']) > 0 ? intval($params['SLIDER_COUNT']) : 1;

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
        } catch (Exception $e) {
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

        $rsItems = CIBlockElement::GetList(
            ['ID' => $this->arParams['SLIDER_ORDER']],
            ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['IBLOCK_ID']],
            false,
            ['nTopCount' => $this->arParams['SLIDER_COUNT']],
            ['ID', 'NAME', 'PROPERTY_SLIDER', 'PROPERTY_SLIDER_TXT']
        );

        while($arItem = $rsItems->Fetch()){
            $this->arResult['ITEMS'][$arItem['ID']] = $arItem;

            $fileSlider = CFile::GetPath($arItem['PROPERTY_SLIDER_VALUE']);
            $fileSliderTxt = CFile::GetPath($arItem['PROPERTY_SLIDER_TXT_VALUE']);

            $this->arResult['ITEMS'][$arItem['ID']]['SLIDER'] = $fileSlider;
            $this->arResult['ITEMS'][$arItem['ID']]['SLIDER_TXT'] = $fileSliderTxt;
        }



        if (!$this->arResult['ITEMS']) {
            $this->AbortResultCache();
        }

    }


}

