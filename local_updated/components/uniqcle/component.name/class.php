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
        return $params;
    }

    public function executeComponent(){
        try {
            //StartResultCache return true, если кэш недействительный
            //StartResultCache return false и $arResult - если действителен

            if ($this->startResultCache(false)) {  //Берем из кэша или формируем заного результат, если нет кэша

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



        $rs = CIBlockElement::getList(
            ['rand' => 'asc'],
            ['IBLOCK_ID' => $this->arResult['IBLOCK']['ID'], 'SECTION_ID' => $this->arResult['SECTION']['ID']],
            false,
            ['nTopCount' => $this->arParams['COUNT']]
        );
        while ($ob = $rs->GetNextElement()) {
            $arItem = $ob->GetFields();
            $arItem['PROPERTIES'] = $ob->GetProperties();

            $arItem['DISPLAY_PROPERTIES'] = [];
            foreach ($arItem['PROPERTIES'] as $code => $arProp) {
                $prop = $arItem['PROPERTIES'][$code];
                if (
                    (is_array($prop['VALUE']) && count($prop['VALUE']))
                    || (!is_array($prop['VALUE']) && strlen($prop['VALUE']))
                ) {
                    $arItem['DISPLAY_PROPERTIES'][$code] = CIBlockFormatProperties::GetDisplayValue($arItem, $prop, 'app_banner');
                }
            }

            Iblock\Component\Tools::getFieldImageData(
                $arItem,
                ['PREVIEW_PICTURE', 'DETAIL_PICTURE'],
                Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
                'IPROPERTY_VALUES'
            );

            $this->arResult['ITEMS'][] = $arItem;
        }

        if (!$this->arResult['ITEMS']) {
            $this->AbortResultCache();
        }

    }


}
