<?
/*
 * Полезные и простые функции для работы с PHP. Намеренно объявляются в глобальном namespace
 * */
if (!function_exists('pre')) {
	function pre($var, $die = false)
	{
		echo '<pre>';
		print_r($var);
		echo '</pre>';
		if ($die)
			die('Debug in PRE');
	}
}

if (!function_exists('vd')) {
	function vd($var, $die = false)
	{
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
		if ($die)
			die('Debug in VD');
	}
}

if (!function_exists('writeEvent')) {
	function writeEvent($dump)
	{
		ulogging($dump, 'writeEvent', true);
	}
}

if (!function_exists('ulogging')) {
	/*
	 * ВНИМАНИЕ! Перед использованием создать папку logs в upload и дать права на записать в папку
	 * */
	function ulogging($input, $logname = 'debug', $dt = false)
	{
		$endLine = "\r\n"; #PHP_EOL не используется, т.к. иногда это нужно конфигурировать это

		$fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/upload/logs/' . $logname . '.txt', "a+");

		if (is_string($input)) {
			$writeStr = $input;
		} else {
			$writeStr = print_r($input, true);
		}

		if ($dt) {
			fwrite($fp, date('d.m.Y H:i:s') . $endLine);
		}

		fwrite($fp, $writeStr . $endLine);

		fclose($fp);
		return true;
	}
}

if(!function_exists('xml2array')){

    function xml2array($xmlObject, $out = []){
        foreach( (array)$xmlObject as $index => $node){
            $out[$index] = (is_object($node)) ? xml2array($node) : $node;
        }
        return $out;
    }

}