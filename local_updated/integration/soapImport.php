<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");


$exchangeSoap = new \SoapConnect(SKLBOX_1C_URL, SKLBOX_1C_LOGIN, SKLBOX_1C_PASS);

$result = $exchangeSoap->GetCity();

pre($exchangeSoap);