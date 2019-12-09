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

        $params['COUNT_SLIDER'] = isset($params['COUNT_SLIDER']) && intval($params['COUNT_SLIDER']) > 0 ? intval($params['COUNT_SLIDER']) : 3;

        $params['SLIDER_TIME'] = isset($params['SLIDER_TIME']) && intval($params['SLIDER_TIME']) > 0 ? intval($params['SLIDER_TIME']) : 1000;

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

        $this->arResult['CHECKBOX_PARAM'] =  $this->arParams['CHECKBOX_PARAM'] == 'Y' ? 'Y' : '';
        $this->arResult['SLIDER_TIME'] = $this->arParams['SLIDER_TIME'];

    }



    protected function doAction()
    {

         $this->arResult['ITEMS'] = [];

        $rsItems = CIBlockElement::GetList(
           ['ID' => 'ASC'], //порядок
            ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['IBLOCK_ID']], //фильтр
           false,
           ['nTopCount' => $this->arParams['COUNT_SLIDER']], //pagination
            ['IBLOCK_ID', 'ID', 'DETAIL_PICTURE', 'NAME' ] //selection
        );

        while($sliderItem = $rsItems -> Fetch() ){
            $this->arResult['ITEMS'][$sliderItem['ID']]  = $sliderItem;

            $file = CFile::ResizeImageGet(
                    $sliderItem['DETAIL_PICTURE'],
                    ["width"=>100, "height"=>50],
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    false,
                    false,
                    false,
                    false
            );

            $this->arResult['ITEMS'][$sliderItem['ID']]['DETAIL_PICTURE'] = $file['src'];

        }


        if (!$this->arResult['ITEMS']) {
            $this->AbortResultCache();
        }
    }

}
