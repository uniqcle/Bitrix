<?php

class SoapConnect
{
	private $wsdlConnect, $isDebug = false;
	private $arParams = [];

	function __construct($urlURL, $login, $pass, $isDebug = false, $arParams = [])
	{
		if (empty($login) || empty($pass)) {
			return false;
		}

		if ($isDebug) {
			$this->isDebug = true;
		}

		if ($arParams) {
			$this->arParams = $arParams;
		}

		$connectionParams = [
			'cache_wsdl' => WSDL_CACHE_NONE, #отключаем кеш
			'connection_timeout' => 600,
			"login" => $login,
			"password" => $pass,
			"encoding" => 'UTF-8', #кодировка
			"features" => SOAP_SINGLE_ELEMENT_ARRAYS, #интересный параметр, посмотрите его
			"trace" => 1,
			"exception" => 1,
		];

		#задаем параметры если нужно
		if (!empty($arParams) && is_array($arParams)) {
			foreach ($arParams as $arParamName => $value) {
				$connectionParams[$arParamName] = $value;
			}
		}

		try {
			$this->wsdlConnect = new SoapClientLogging($urlURL, $connectionParams);
		} catch (SoapFault $E) {
			\writeEvent($E->faultstring);
			return false;
		}
		return true;
	}

	/*
	 * Интересная функция! Рекомендую ознакомиться http://php.net/manual/ru/language.oop5.magic.php
	 */
	public function __call($method, $args)
	{
		try {
			$response = call_user_func_array([$this->wsdlConnect, $method], $args);
		} catch (Exception $e) {
			writeEvent($e);
			writeEvent($e->getMessage());
			writeEvent('------------------------------------------------');
			return false;
		}

		$arResponse = $this->convert2Array($response);

		#для данных методов всегда выбираем 1 элемент. Часто разработчики 1с не соблюдают логику
		$getOneAr = [];
		if (in_array($method, $getOneAr)) {
			$arResponse = $arResponse[0];
		}

		return $arResponse;
	}

	protected function convert2Array($response, $isReturn = true)
	{
		$rows = [];
		#
		if ($isReturn) {
			$response = $response->return;
		}

		#
		foreach ($response->column as $key => $value) {
			$column[$key] = $value->Name;
		}

		if (is_array($response->row)) {
			foreach ($response->row as $oneItem) {
				$oneVal = [];
				foreach ($oneItem->Value as $key => $value) {
					if (is_object($value)) {
						if (!is_object($value->enc_value)) {
							$oneVal[$column[$key]] = $value->enc_value;
						} else {
							$oneVal[$column[$key]] = $this->convert2Array($value->enc_value, false);
						}

					} else {
						$oneVal[$column[$key]] = $value;
					}

				}
				$rows[] = $oneVal;
			}
		}
		return $rows;
	}
}