<?php
use \Bitrix\Main,
    \Bitrix\Main\Loader,
    \Bitrix\Main\Localization\Loc;
use \Uniqcle\D7\Division;

class D7Exception extends CBitrixComponent
{
    protected function checkModules(){
        if(!Loader::includeModule('uniqcle.d7'))
            throw new \Bitrix\Main\LoaderException(Loc::getMessage('UNIQCLE_D7_MODULE_NOT_INSTALLED'));
    }

    public function var1(){
        // return Division::divided(4,2);

        return Division::divided(4,0);
    }

    public function executeComponent(){
        try{
            $this->includeComponentLang('class');

            $this->checkModules();

            $this->arResult = $this->var1();

            $this->includeComponentTemplate();

        } catch(\Uniqcle\D7\DivisionError $e) {

            ShowError($e -> getMessage());

            var_dump($e -> getParam1());

            var_dump($e -> getParam2());
        }
    }
}