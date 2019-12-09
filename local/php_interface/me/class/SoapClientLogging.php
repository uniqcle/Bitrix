<?php
/**
 * Для работы класса требуется включенные расширения php: soap и dom
 */
class SoapClientLogging extends \SoapClient
{
	function __doRequest($request, $location, $action, $version, $one_way = 0)
	{
		$this->eventBeforeRequest($action, $request); #событие ДО отправки
		$response = parent::__doRequest($request, $location, $action, $version, $one_way);
		$this->eventAfterRequest($action, $response); #событие ПОСЛЕ отправки

		#форматируем
		$domxml = new \DOMDocument('1.0');
		$domxml->preserveWhiteSpace = false;
		$domxml->formatOutput = true;
		$domxml->loadXML(html_entity_decode($request));
		$requestFormattedXml = $domxml->saveXML();
		$domxml->loadXML(html_entity_decode($response));
		$responseFormattedXml = $domxml->saveXML();

		#выбираем функцию которую хотим использовать для записи. В данном случае самописная ulogging
		if (function_exists('\ulogging')) {
			$logName = 'soap';
			\ulogging('--------------------------------------------------------', $logName);
			\ulogging('Дата и время: ' . date('d.m.Y H:i:s'), $logName);
			\ulogging('Request:', $logName);
			\ulogging($requestFormattedXml, $logName);
			\ulogging('Response:', $logName);
			\ulogging($responseFormattedXml, $logName);
		}
		return $response;
	}

	function eventBeforeRequest($action, $request)
	{
		#можем использовать обработки запроса
	}

	function eventAfterRequest($action, $response)
	{
		#можем использовать для обработки результата
	}
}