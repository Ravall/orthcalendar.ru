<?php
class Sancta_Api
{	
	private static function _request($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //do not output directly, use variable
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); //do a binary transfer
		curl_setopt($ch, CURLOPT_FAILONERROR, 1); //stop if an error occurred
		$info = curl_exec($ch);
		if (curl_errno($ch)) {
			return false;
		}
		return json_decode($info);
	}

	public static function getDay($day)
	{
		$url = Config_Interface::get('api', 'url')
		         . "calendar/{$day->getDay()}/?format=json";
		return self::_request($url);
	}

	public static function getEventInfo($eventId)
	{
		$url = Config_Interface::get('api', 'url')
		         . "event/{$eventId}/?format=json";
		return self::_request($url);
	}
}