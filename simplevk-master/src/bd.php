<?
	Class Config
	{
		const BD_HOST = 'triniti.ru-hoster.com';
		const BD_USER = 'sammaAod';
		const BD_NAME = 'sammaAod';
		const BD_PASS = 'Ersatoptoptoptoptop';
	};
	
	function BD_CREATE()
	{
		return (new mysqli(Config::BD_HOST, Config::BD_USER, Config::BD_PASS, Config::BD_NAME));
	}
?>