<?
	echo time();
?>

<?
	
	include 'simplevk-master/src/bd.php';

	require_once 'simplevk-master/autoload.php';
	use DigitalStar\vk_api\VK_api as vk_api;


	const VK_KEY = 'vk1.a.7kfvPwWjxRsiDs13GVHJ1VtVeMtCeKDK9JKXTqBKJuPR0hvGzmPEaH7wMx3AnVTUdJV_o021_IEQWIUnw5qifWYG5rWmNmkJliqNuc5zNS27ageUtTnRFGtJF9O3cKLacoto5Y9f1Ul50FIxQ-7ZGD4W14pC6zdnKxrMu--KBz9QSUbYJ5_y2gc90nsbWW8qkgkGXcIalWde1hZWGIMg-w';  // Токен сообщества
	const ACCESS_KEY = 'ArizonaSpaceVkBot'; // Тот самый ключ из сообщества
	const VERSION = '5.131'; // Версия API VK


	$vk = vk_api::create(VK_KEY, VERSION)->setConfirm(ACCESS_KEY);
	
	$button1 = [
		[	
			$vk -> buttonOpenLink('TEST1', 'https://panel.arz-supreme.space') 
		]
	];
	
	$dop_menu_list = [
					[
						$vk->buttonText('Мои аккаунты', 'green', ['command' => 'myAccountShow']),
						$vk->buttonText('Мои машины', 'green', ['command' => 'myCarsShow']),
						$vk->buttonText('Назад в меню', 'green', ['command' => 'menuShow']),
					],
					[
						$vk -> buttonText('История наказаний', 'red', ['command' => 'myErrorLog']),
					],
					[
						$vk->buttonText('Получить аццепт [Администратору]', 'white', ['command' => 'acceptMyAccount']),
					],
					[
						$vk->buttonText('Выбрать основной аккаунт', 'blue', ['command' => 'selectAccount']),
					]
				];
				
	//$vk -> sendButton(411223050, 'asdads', $button1);
	
	$BD_bot = new mysqli('45.144.67.109', 'USER_PANEL', 'P42B7jZ6QqOh522Z', 'VK');
	$BD_bot -> query("SET character_set_results = utf8"); 
	$BD_bot -> query("SET NAMES 'utf8'");

	$a = $BD_bot -> query("SELECT `vkId` FROM users");
	
	while ($sql = mysqli_fetch_array( $a ) )
	{
		$BD_bot -> query("INSERT INTO items (`itemId`, `vkId`, `Name`) VALUES (0, $sql[vkId], 'Оплата налогов на имущество!')");
	}
	
	$msg = '&#10071; Добавленая новая фича, инвентарь бота!<br>Вы сможете покупать/получать на раздачах различные предметы<br>В честь открытия нового ОФФИЦАЛЬНОГО ФОРУМА: https://forum.arizona-supreme.site<br>Я выдам всем в инвентарь оплату налогов через бота ( 1 шт. )<br>Сам инвентарь находится в Начать - Дополнительно<br>Конкурс на 2 ПРЕМИУМ ФУЛЛ: https://vk.com/supreme_both?w=wall-206998864_115';
	
	$vk -> sendAllDialogs($msg);
	
	$BD_bot -> close();
	
	/*$BD_bot = new mysqli('45.144.67.109', 'USER_PANEL', 'P42B7jZ6QqOh522Z', 'VK');
	
	$a = $BD_bot -> query('SELECT `vkId` FROM users'); 
	
	while ($data = mysqli_fetch_array( $a ) )
	{
		$msg = 'Внимание! Уважаемый %a_full%,<br>у нас проходит конкурс на 3 PREMIUM LIGHT (14 дней)<br>Не пропусти -> https://vk.com/supreme_both?w=wall-206998864_9<br><br>Уже сегодня в 10:00 по мск будут итоги!';
		$vk -> sendMessage($data[0], $msg);
	}
	
	$BD_bot -> close();
	*/
?>
