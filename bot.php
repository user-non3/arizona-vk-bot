<?php

date_default_timezone_set('Europe/Moscow');

include 'simplevk-master/src/bd.php';

require_once 'simplevk-master/autoload.php';
use DigitalStar\vk_api\VK_api as vk_api;


const VK_KEY = 'vk1.a.7kfvPwWjxRsiDs13GVHJ1VtVeMtCeKDK9JKXTqBKJuPR0hvGzmPEaH7wMx3AnVTUdJV_o021_IEQWIUnw5qifWYG5rWmNmkJliqNuc5zNS27ageUtTnRFGtJF9O3cKLacoto5Y9f1Ul50FIxQ-7ZGD4W14pC6zdnKxrMu--KBz9QSUbYJ5_y2gc90nsbWW8qkgkGXcIalWde1hZWGIMg-w';  // Токен сообщества
const ACCESS_KEY = 'ArizonaSpaceVkBot';  // Тот самый ключ из сообщества
const VERSION = '5.131'; // Версия API VK


$vk = vk_api::create(VK_KEY, VERSION)->setConfirm(ACCESS_KEY);

$vk->initVars($peer_id, $message, $payload, $vk_id, $type, $data); // Инициализация переменных

$vk_id = $data->object->from_id; // Узнаем ID пользователя, кто написал нам
$message = $data->object->text; // Само сообщение от пользователя

$msg = -1;

$main_menu_btn = 
[
	[
		$vk->buttonText('Профиль', 'blue', ['main' => 'stats']),
		$vk->buttonText('Магазин', 'green', ['main' => 'shop']),
		$vk->buttonText('Дополнительно', 'blue', ['main' => 'dop'])
	],
	[
		$vk->buttonText('GrandRase VIP [ новый VIP ]', 'red', ['main' => 'premiumBot'])
	],
	[
		$vk -> buttonText('Информация', 'white', ['main' => 'info']),
		$vk -> buttonOpenLink('Разработчик бота', 'https://vk.com/escobarro777') 
	]
];

$BD_bot = new mysqli('62.109.24.40', 'USER_PANEL', 'P42B7jZ6QqOh522Z', 'VK');
$BD_bot -> query("SET character_set_results = utf8"); 
$BD_bot -> query("SET NAMES 'utf8'");
							
switch ($data->type)
{
	case 'message_new':
	{
		$id = $peer_id;
		break;
	}
	
	default:
	{
		if ($data->type == 'like_add' or $data->type == 'like_remove')
		{
			$id = $data -> object -> liker_id;
		}
	}
}

$a = $BD_bot -> query('SELECT * FROM users WHERE vkId = \''.$id.'\';'); 
$User = mysqli_fetch_array( $a );

if (!$User[vkId])
{
	$BD_bot -> query("INSERT INTO users (`vkId`) VALUES ($id)"); 
	$User[vkId] = $id;
}

if (isset($User[pId]))
{
	$BD = BD_CREATE();
	
	$a = $BD -> query("SELECT * FROM accounts WHERE ID = $User[pId];"); 
	$PI = mysqli_fetch_array( $a );
	
	$BD -> close();
}

if ($data->type == 'message_new') 
{	
	if ($message == 'secretmenu')
	{
		$world_random = 
		[
			[
				$vk->buttonText('Кейс ELITE', 'red', ['random_case' => 'ELITE']),
				$vk->buttonText('Кейс MIDLE', 'green', ['random_case' => 'MIDLE']),
				$vk->buttonText('Кейс LIGHT', 'blue', ['random_case' => 'LIGHT']),
			],
			
			[
				$vk->buttonText('&#8265; Информация', 'white', ['command' => 'helpCase']),
				$vk->buttonText('&#127890; Инвентарь', 'white', ['command' => 'invCase'])
			],
			
			[
				$vk->buttonText('&#127968; Назад в меню', 'blue', ['command' => 'menuShow'])
			]
		];
				
		$vk->sendButton($peer_id, '%a_full%', $world_random);
	}
	
	if ($message == 'Начать' or $message == 'Меню')
		$vk->sendButton($peer_id, 'Главное меню выведено вам на экран', $main_menu_btn);
		
	if (isset($payload['main']))
	{
		switch ($payload['main'])
		{
			case 'info':
			{
				$msg = '
				&#10024; Краткое F.A.Q &#10024;
				
				&#10071; Как привязать свой аккаунт к ВКонтакте?
				( Вам нужно в игре прописать -> /settings - Привязка Вконтакте)<br>
				&#10071; Как выбрать основной аккаунт? 
				( Меню -> дополнительно -> выбрать основной аккаунт)<br>
				&#10071; Как посмотреть налоги на свои дома/бизы или риэлторские налоги?
				( Меню -> GrandRase VIP -> Выбрать интересующий вас пункт )<br>
				&#10071; Я администратор, как получить аццепт?
				( Выбрать основной аккаунт, после [Меню -> Дополнительно -> Получить аццепт] )<br>
				&#10071; Где взять бонусный баланс?
				( Он выдается за вашу активность моментально! Пример: поставили лайк на пост.)
				';
				
				break;
			}
			case 'dop':
			{
				$dop_menu_list = [
					[
						$vk->buttonText('Мои аккаунты', 'green', ['command' => 'myAccountShow']),
						$vk->buttonText('Мои машины', 'green', ['command' => 'myCarsShow']),
						$vk->buttonText('Назад в меню', 'green', ['command' => 'menuShow']),
					],
					[
						$vk -> buttonText('История наказаний', 'red', ['command' => 'myErrorLog']),
						$vk -> buttonText('Мои предметы', 'red', ['command' => 'showMyInventory'])
					],
					[
						$vk->buttonText('Получить аццепт [Администратору]', 'white', ['command' => 'acceptMyAccount']),
					],
					[
						$vk->buttonText('Выбрать основной аккаунт', 'blue', ['command' => 'selectAccount']),
					]
				];
				
				$vk->sendButton($peer_id, 'Дополнительное меню успешно показано.', $dop_menu_list);
				break;
			}
			
			case 'stats':
			{
				$msg = "&#128302; Статистика аккаунта %a_full% &#128302;<br><br>&#127919; Выбранный аккаунт: ".(!$User[pId] ? '&#10060;<br>(Выбрать аккаунт можно: Начать - Дополнительно - Выбрать основной аккаунт)':"$User[pName] (UID: $User[pId])")."<br>&#10024; Ваш баланс: $User[Balance] 💎<br>👑 GrandRase VIP: ".($PI[VIP] == 9 ? 'Имеется &#9989;':'Не имеется &#10060;');
				
				break;
			}
			
			case 'shop':
			{ 
				/*$vk->buttonText('SUPREME V.I.P', 'green'),
				$vk->buttonText('Премиум BOT', 'green'),
				$vk->buttonText('Аксессуар +12', 'green')*/
				
				$btnshop = [
					[
						$vk->buttonText('&#11015; Реальные деньги (&#128181;) &#11015;', 'white')
					],
					[
						$vk->buttonText('AdminTools', 'red', ['command' => 'buyTools']),
						$vk->buttonText('Покупка логов', 'green', ['command' => 'buyLogs']),
						$vk->buttonText('Админ-права', 'blue', ['command' => 'buyAdm'])
					],
					[
						$vk->buttonText('&#11015; Бонусный баланс (💎) &#11015;', 'white')
					],
				];
				
				
				$a = $BD_bot -> query('SELECT * FROM shop');
				
				while ( $data = mysqli_fetch_array( $a ) )
				{
					$array_shop[] = array(
						ID => $data[0],
						Name => $data[1],
						Cost => $data[2]
					);
				}
				
				for ($i = 0; $i < $q = count($array_shop); $i++)
				{
					if (($q - $i) >= 2)
					{
						$btnshop[] = [
							$vk->buttonText($array_shop[$i][Name], 'green', ['shop' => $array_shop[$i][ID]]),
							$vk->buttonText($array_shop[$i+1][Name], 'green', ['shop' => $array_shop[$i+1][ID]])
						];
						
						$i += 1;
					}
					else
						$btnshop[] = [ $vk->buttonText($array_shop[$i][Name], 'green', ['shop' => $array_shop[$i][ID]]) ];
				}
				
				$btnshop[] = [ $vk->buttonText('Назад', 'white', ['command' => 'menuShow']) ];
				
				$vk->sendButton($peer_id, "&#10024; Ваш баланс: $User[Balance] 💎", $btnshop);
				
				break;
			}
			
			case 'premiumBot':
			{
				if ($PI[VIP] < 9)
					$msg = '&#10060; У вас нет GrandRase VIP или время подписки истекло.'.$PI[VIP];
				
				else
				{
					$vk->sendButton($peer_id, "&#10024; Premium Menu",
					[
						[
							$vk->buttonText('Мои налоги', 'red', ['command' => 'myNalogShow']),
							$vk->buttonText('Риэлторские налоги', 'red', ['command' => 'rieltNalogShow']),
						],
						[
							$vk->buttonText('Оплатить мои налоги [PREMIUM FULL]', 'blue', ['command' => 'myNalogPay']),
						],
						[
							$vk->buttonText('Назад', 'white', ['command' => 'menuShow'])
						]
					]);
				}
				
				break;
			}
		}
	}
	
	$shop = $payload['shop'];
	
	if (isset($shop))
	{
		list($prodId) = sscanf($shop, '%d'); 
		
		$a = $BD_bot -> query("SELECT * FROM shop WHERE ID = $prodId;");
				
		$data = mysqli_fetch_array( $a );
		
		if ($data[Cost] > $User[Balance])
			$msg = "&#10060; Не хватает 💎. [ Необходимо $data[Cost] ]";
		
		else
		{
			switch ($data[Name])
			{
				case 'SUPREME VIP':
				{
					$msg = 'покупка суприм випа';
					
					break;
				}
				case 'PREMIUM LIGHT TEST 1 DAY':
				{
					if ($User[Premium] < 1 or time() > $User[DateActive])
					{
						$BD_bot -> query("UPDATE users SET Balance = `Balance` - $data[Cost], Premium = 1, DateActive = ".(time() + 86400)." WHERE vkId = $peer_id;");
						
						$msg = "&#9989; Вы успешно купили $data[Name] за $data[Cost] 💎";
					}
					
					break;
				}
			}
		}
	}
	
	if (strpos($message, '!инвентарь') !== false)
	{
		list($comman_text, $id) = sscanf($message, '%s %d');
		
		$a = $BD_bot -> query("SELECT * FROM items WHERE ID = $id AND ID > 0;");
		
		if (mysqli_num_rows($a))
		{
			$sql = mysqli_fetch_array ( $a );
			
			if ($sql[vkId] == $peer_id)
			{
				switch ($sql[itemId])
				{
					case 0:
					{
						$BD = BD_CREATE();
					
						$checkban = $BD -> query("SELECT * FROM bannames WHERE Name = BINARY($User[pName]);");
						
						if (!mysqli_num_rows($checkban))
						{
							$checkban = $BD -> query("SELECT `Demorgan` FROM accounts WHERE Name = BINARY($User[pName]);");
							
							if (!mysqli_num_rows($checkdmg))
							{
								$a = $BD -> query("SELECT * FROM autonalog WHERE vkId = $peer_id;");
								
								if (mysqli_num_rows($a))
									$msg = '&#10060; Вы уже в очереди на оплату налогов!';
								
								else
								{
									if ($User[pId])
									{
										$a = $BD -> query("SELECT `ID` FROM houses WHERE Owner = '$User[pName]';"); 
										
										while( $sql = mysqli_fetch_array( $a ) )
										{
											$BD -> query("INSERT INTO autonalog (`vkId`, `Type`, `Id`) VALUES($peer_id, 0, $sql[0])");
										}
										
										$a = $BD -> query("SELECT `ID` FROM businesses WHERE Owner = '$User[pName]';"); 
										
										while( $sql = mysqli_fetch_array( $a ) )
										{
											$BD -> query("INSERT INTO autonalog (`vkId`, `Type`, `Id`) VALUES($peer_id, 1, $sql[0])");
										}
										
										$msg = '&#9989; Ваше имущество поставлено в очередь на оплату.<br>(Будет оплачено '.date("H").':55)<br>После оплаты вы получите уведомление!';
										$BD_bot -> query("DELETE FROM items WHERE ID = $id;");
									}
									else 
										$msg = '&#10060; Для начала выберите основной аккаунт!';
								}
								
								$BD -> close();
							}
							else
								$msg = '&#10060; Вы находится в DEMORGAN!';
						}
						else
							$msg = '&#10060; Ваш аккаунт в активной игровой блокировке!';
						
						break;
					}
				}
			}
			else 
				$msg = '&#10060; Данный предмет пренадлежит не вам &#10071;';
		}
		else
			$msg = '&#10060; Произошла ошибка базы данных &#10071;';
	}
	
	$command = $payload['command'];
	
	if (isset($command))
	{
		
		if (strpos($command, 'selectAccountF') !== false)
		{
			list($name_command, $pName, $pId) = sscanf($command, '%s %s %d');
			
			$msg = '💎 Ваш аккаунт '.$pName.' (UID: '.$pId.')<br>&#9989; Отмечен как основной аккаунт в боте. &#9989;';
			
			$BD_bot -> query("UPDATE users SET pId = '$pId', pName = '$pName' WHERE vkId = $peer_id;");
			
			$vk -> sendButton($peer_id, 'Главное меню - показано.', $main_menu_btn);
		}
			
		switch ($command)
		{
			case 'myNalogPay':
			{
				if ($PI[VIP] < 9)
					$msg = '&#10060; У вас нету GrandRase VIP на аккаунте ☹️';
				
				else
				{
					$BD = BD_CREATE();
					
					$a = $BD -> query("SELECT * FROM autonalog WHERE vkId = $peer_id;");
					
					if (mysqli_num_rows($a))
						$msg = '&#10060; Вы уже в очереди на оплату налогов!';
					
					else
					{
						if ($User[pId])
						{
							$a = $BD -> query("SELECT `ID` FROM houses WHERE Owner = '$User[pName]';"); 
							
							while( $sql = mysqli_fetch_array( $a ) )
							{
								$BD -> query("INSERT INTO autonalog (`vkId`, `Type`, `Id`) VALUES($peer_id, 0, $sql[0])");
							}
							
							$a = $BD -> query("SELECT `ID` FROM businesses WHERE Owner = '$User[pName]';"); 
							
							while( $sql = mysqli_fetch_array( $a ) )
							{
								$BD -> query("INSERT INTO autonalog (`vkId`, `Type`, `Id`) VALUES($peer_id, 1, $sql[0])");
							}
							
							$msg = '&#9989; Ваше имущество поставлено в очередь на оплату.<br>(Будет оплачено '.date("H").':55)<br>После оплаты вы получите уведомление!';
						}
						else 
							$msg = '&#10060; Для начала выберите основной аккаунт!';
					}
					
					$BD -> close();
				}
				
				break;
			}
			case 'rieltNalogShow':
			{
				if ($PI[VIP] <  9)
					$msg = '&#10060; У вас нет GrandRase VIP или время подписки истекло.';
				
				else
				{	
					$BD = BD_CREATE();
				
					$a = $BD -> query("SELECT `Nalog` FROM houses WHERE Owner != 'The_State' AND `Nalog` > 80000;"); 
					
					$msg = '&#10024; Отдел недвижимости:<br><br>';
					
					$i = 1;
					
					while ( $data = mysqli_fetch_array( $a ) )
					{
						$msg = $msg."&#9989; $i. Дом №? до слета ".round((104000-$data[0])/3000)." час(а/ов)<br>";
						
						$i++;
						
					}
					
					$vk -> sendMessage($peer_id, $msg);
					
					
					$msg = '<br><br>&#10024; Отдел коммерции:<br><br>';
					
					$a = $BD -> query("SELECT `Level` FROM businesses WHERE Owner != 'The_State' AND `Level` > 190000;"); 
					
					if (!mysqli_num_rows($a))
						$msg = $msg.'&#10060; Отдел коммерции пуст.';
					
					else
					{
						$i = 1;
						
						while ( $data = mysqli_fetch_array( $a ) )
						{
							$msg = $msg."&#9989; $i. Бизнес №? до слета ".round((250000-$data[0])/3000)." час(а/ов)<br>";
							
							$i++;
						}
					}
					
					$BD -> close();
					
				}
				
				break;
			}
			
			case 'myNalogShow':
			{
				if ($PI[VIP] <  9)
					$msg = '&#10060; У вас нет SOLUTION VIP или время подписки истекло.';
				
				else
				{
					$BD = BD_CREATE();
				
					$a = $BD -> query("SELECT `ID`, `Nalog` FROM houses WHERE Owner = '$User[pName]';"); 
					
					if (!mysqli_num_rows($a))
						$msg = '&#10060; У вас нету домов';
					
					else
					{
						$msg = '&#10024; Ваши налоги на дома:<br><br>';
					
						while ( $data = mysqli_fetch_array( $a ) )
						{
							$msg = $msg."&#9989; $data[0] - $data[1]$ [до слета ".round((104000-$data[1])/3000)." часа]<br>";
						}
					}
					
					$a = $BD -> query("SELECT `ID`, `Level` FROM businesses WHERE Owner = '$User[pName]';"); 
					
					if (!mysqli_num_rows($a))
						$msg = $msg.'<br><br>&#10060; У вас нету бизнесов';
					
					else
					{
						$msg = $msg.'<br><br>&#10024; Ваши налоги на бизнесы:<br><br>';
					
						while ( $data = mysqli_fetch_array( $a ) )
						{
							$msg = $msg."&#9989; $data[0] - $data[1]$ [до слета ".round((250000-$data[1])/6000)." часа]<br>";
						}
					}
					
					/*if (!mysqli_num_rows($a))
					{
						$msg = '&#10060; Аккаунты не найдены';
						$vk -> sendButton($peer_id, 'Главное меню - показано.', $main_menu_btn);
					}*/
					
					$BD -> close();
				}
				
				break;
			}
			
			case 'selectAccount':
			{
				$BD = BD_CREATE();
				
				$a = $BD -> query('SELECT `NickName`, `ID` FROM accounts WHERE VKontakte = \''.$peer_id.'\';'); 
				
				while ( $data = mysqli_fetch_array( $a ) )
				{
					$select_account_btn[] = [ $vk->buttonText($data[0], 'red', ['command' => 'selectAccountF '.$data[0].' '.$data[1]]) ];
				}
				
				if (isset($select_account_btn))
					$vk -> sendButton($peer_id, 'Выберите аккаунт, который будет основным в боте.', $select_account_btn);
				else
					$msg = '&#10060; Аккаунты не найдены';
				
				$BD -> close();
				break;
			}
			case 'menuShow':
			{
				$vk->sendButton($peer_id, 'Главное меню выведено вам на экран', $main_menu_btn);
				break;
			}
			
			case 'myAccountShow':
			{
				$BD = BD_CREATE();
				
				$a = $BD -> query('SELECT `NickName`, `Level` FROM accounts WHERE VKontakte = \''.$peer_id.'\';'); 
				
				$msg = '&#10024; Ваши аккаунты (привязынные к VK):<br><br>';
				
				while ( $data = mysqli_fetch_array( $a ) )
				{
					$msg = $msg."&#9989; $data[0] - $data[1] уровень<br>";
				}
				
				if (!mysqli_num_rows($a))
				{
					$msg = '&#10060; Аккаунты не найдены';
					$vk -> sendButton($peer_id, 'Главное меню - показано.', $main_menu_btn);
				}
				
				$BD -> close();
				break;
			}
			
			case 'buyTools':
			{
				$msg = 'Временно недоступно';
				break;
			}
			
			case 'buyLogs':
			{
				$msg = '%a_full%.
				Стоимость подписки логов на месяц стоит 299 рублей.
				Купить можно -> vk.com/coder_supreme &#9989;';
				
				break;
			}
			
			case 'buyAdm':
			{
				if (random_int(1, 100) < 50)
					$msg = '%a_full%.<br>Стоимость Админ-Прав зависит от выбранного вами уровня.<br>
					1 LVL - 150 рублей
					2 LVL - 250 рублей
					3 LVL - 500 рублей
					4 LVL - 750 рублей
					5 LVL - 1000 рублей
					6 LVL - 1500 рублей
					7 LVL - 4000 рублей<br><br>Купить можно -> vk.com/coder_supreme &#9989;<br>Купить можно -> vk.com/nura_supreme &#9989;';
				else
					$msg = '%a_full%.<br>Стоимость Админ-Прав зависит от выбранного вами уровня.<br>
					1 LVL - 150 рублей
					2 LVL - 250 рублей
					3 LVL - 500 рублей
					4 LVL - 750 рублей
					5 LVL - 1000 рублей
					6 LVL - 1500 рублей
					7 LVL - 4000 рублей<br><br>Купить можно -> vk.com/nura_supreme &#9989;<br>Купить можно -> vk.com/coder_supreme &#9989;';
				
				break;
			}
			
			case 'showMyInventory':
			{
				$a = $BD_bot -> query("SELECT * FROM items WHERE vkId = $peer_id;"); 
		
				if (mysqli_num_rows($a))
				{
					$msg = '&#10024; Ваш инвентарь [БОТА]:<br><br>';
					
					while( $Inv = mysqli_fetch_array( $a ) )
					{
						$msg = $msg."&#9989; №$Inv[ID]. $Inv[Name]<br>";
					}
					
					$msg = $msg.'<br><br>&#10071; Чтобы использовать предмет введите !инвентарь [id предмета указанный перед названия (№?)]';
				}
				else
					$msg = '&#10060; Ваш инвентарь пуст!';
				
				break;
			}
			case 'myErrorLog':
			{
				if ($User[pId])
				{
					$BD = BD_CREATE();
					
					$BD -> query("SET character_set_results = utf8"); 
				
					$a = $BD -> query("SELECT * FROM history WHERE Name = BINARY('$User[pName]') ORDER BY Date DESC"); 

					if (mysqli_num_rows($a))
						$msg = '&#10024; Ваша история наказаний 😲:<br><br>';

					while( $sql = mysqli_fetch_array( $a ) )
					{
						$msg = $msg."&#9989; ($sql[Date]) $sql[msg]<br><br>";
						
						if (strlen($msg) > 3000)
						{
							$vk -> sendMessage($peer_id, $msg);
							$msg = '';
						}
					}
					
					$BD -> close();
				}
				else 
					$msg = '&#9989; Для начала выберите основной аккаунт.';
				
				break;
			}
			
			case 'acceptMyAccount':
			{
				if ($User[pId])
				{
					$BD = BD_CREATE();
					
					$a = $BD -> query("SELECT `NickName`, `Admin`, `Online_status`, `AcceptAdmin` FROM accounts WHERE ID = $User[pId]"); 
					$data = mysqli_fetch_array( $a );
					
					if ($data[1] <= 0)
						$msg = '&#10060; Данная функция доступна только для администрации.';
						
					else if ($data[2]) 
						$msg = '&#10060; Аккаунт '.$data[0].' находится онлайн, для получения аццепта вам необходимо выйти с сервера.';
						
					else if (!$data[3])
					{
						$BD -> query('UPDATE accounts SET AcceptAdmin = 1 WHERE NickName = \''.$data[0].'\' LIMIT 1;');
						$msg = '&#9989; Вы успешно получили аццепт на аккаунт '.$data[0];
					}
						
					else
						$msg = '&#9989; &#10060; Аццепт на аккаунт '.$data[0].' не требуется!';
		
					if ($msg == -1)
						$vk->sendMessage($peer_id, '&#10060; Аккаунты не найдены, возможная причина ваш VK не привязан к аккаунту в игре.');
					
					$BD -> close();
				}
				else
					$msg = '&#9989; Для начала выберите основной аккаунт.';
				
				break;
			}
			
			case myCarsShow:
			{
				if ($User[pId])
				{
					$BD = BD_CREATE();
					
					$a = $BD -> query("SELECT `ID`, `Model` FROM ownable WHERE Owner = '$User[pName]';"); 
					
					$msg = '&#10024; Ваши машины:<br><br>';
					
					$ARIZONA_VEHICLE_NAMES = array("Landstalker","Bravura","Buffalo","Linerunner","Perenniel","Sentinel","Dumper","Firetruck","Trashmaster","Stretch","Manana","Infernus","Voodoo","Pony","Mule","Cheetah","Ambulance","Leviathan","Moonbeam","Esperanto","Taxi","Washington","Bobcat","Mr Whoopee","BF Injection","Hunter","Premier","Enforcer","Securicar","Banshee","Predator","Bus","Rhino","Barracks","Hotknife","Article Trailer","Previon","Coach","Cabbie","Stallion","Rumpo","RC Bandit","Romero","Packer","Monster","Admiral","Squallo","Seasparrow","Pizzaboy","Tram","Article Trailer 2","Turismo","Speeder","Reefer","Tropic","Flatbed","Yankee","Caddy","Solair","Berkley's RC Van","Skimmer","PCJ-600","Faggio","Freeway","RC Baron","RC Raider","Glendale","Oceanic","Sanchez","Sparrow","Patriot","Quad","Coastguard","Dinghy","Hermes","Sabre","Rustler","ZR-350","Walton","Regina","Comet","BMX","Burrito","Camper","Marquis","Baggage","Dozer","Maverick","SAN News Maverick","Rancher","FBI Rancher","Virgo","Greenwood","Jetmax","Hotring Racer","Sandking","Blista Compact","Police Maverick","Boxville","Benson","Mesa","RC Goblin","Hotring Racer","Hotring Racer","Bloodring Banger","Rancher","Super GT","Elegant","Journey","Bike","Mountain Bike","Beagle","Cropduster","Stuntplane","Tanker","Roadtrain","Nebula","Majestic","Buccaneer","Shamal","Hydra","FCR-900","NRG-500","HPV1000","Cement Truck","Towtruck","Fortune","Cadrona","FBI Truck","Willard","Forklift","Tractor","Combine Harvester","Feltzer","Remington","Slamvan","Blade","Freight","Brownstreak","Vortex","Vincent","Bullet","Clover","Sadler","Firetruck LA","Hustler","Intruder","Primo","Cargobob","Tampa","Sunrise","Merit","Utility Van","Nevada","Yosemite","Windsor","Monster A","Monster B","Uranus","Jester","Sultan","Stratum","Elegy","Raindance","RC Tiger","Flash","Tahoma","Savanna","Bandito","Freight Flat Trailer","Streak Trailer","Kart","Mower","Dune","Sweeper","Broadway","Tornado","AT400","DFT-30","Huntley","Stafford","BF-400","Newsvan","Tug","Petrol Trailer","Emperor","Wayfarer","Euros","Hotdog","Club","Freight Box Trailer","Article Trailer 3","Andromada","Dodo","RC Cam","Launch","Police Car (LSPD)","Police Car (SFPD)","Police Car (LVPD)","Police Ranger","Picador","Police SF","Alpha","Phoenix","Glendale Shit","Sadler Shit","Baggage Trailer A","Baggage Trailer B","Tug Stairs Trailer","Boxville","Farm Trailer","Utility Trailer","Mercedes GT63","Mercedes G63AMG","Audi RS6","BMW X5","Chevrolet Corvette","Chevrolet Cruze","Lexus LX","Porsche 911","Porsche Cayenne","Bentley Continental","BMW M8","Mercedes E63s","Mercedes S63 Coupe AMG","Volkswagen Touareg","Lamborghini Urus","Audi Q8","Dodge Challenger","Acura NSX","Volvo V60","Range Rover evoque","Honda Civic Type-R","Lexus Sport-S","Ford Mustang","Volvo XC90","Jaguar F-pace","Kia Optima","BMW Z4","Mercedes Benz S600","BMX X5 E53","Lexus RX450H","Ducati Diavel","Ducati Panigale","Ducati Ducnaked","Kawasaki Ninja ZX-10RR","Western","Rolls Royce Cullinan","Volkswagen Beetle","Bugatti Divo","Bugatti Chiron","Fiat 2020","Mercedes-Benz GLS","Mercedes G65AMG","Lamborghini Aventador SVJ","Range Rover SVA","BMW 5 Series 530i","Mercedes s600","Tesla Model X","Nissan LEAF","Nissan Silvia S15","Subaru Forester 2.0XT","Subaru Legacy RS","Hyundai Sonata 2020","BMW 7 Series E38","Mercedez Benz E-Class W210","Mercedez Benz E-Class W124","Real Tachila","Molniya Makvin","Metr","Buckingham","Infiniti FX50s","Lexus RX 450h","Kia Sportage","Volkswagen Golf R","Audi R8","Toyota Camry","Toyota Camry 3.5","BMW M5 E60","BMW M5 F90","Mercedes Benz Maybach S650","Mercedes AMG GT R","Porshe Panamera Turbo","Volkswagen Passat","Chevrolet Corvette 1980","Dodge Challenger SRT","Ford Mustang Shelby GT500","Aston Martin DB5","BMW M3 GTR","Chevrolet Camaro","Mazda RX7","Mazda RX8","Mitsubishi Eclipse","Ford Mustang 289","Nissan 350Z","BMW M760Li","Aston Martin One-77","Bentley Mulliner Bacalar","Bentley Bentayga","BMW M4 Competition","BMW I8","Genesis G90","Honda Integra Type R","BMW M3 G20","Mercedes-Benz S-Class W223","Dodge Raptor","Ferrari J50","Mercedes-Benz SLR McLaren","Subaru BRZ","LADA Vesta SW Cross","Porsche Taycan Turbo S","Ferrari TW","UAZ Patriot","Volga","Mercedes-Benz X-Class V6","Jaguar XF 2012","RC Shutle","Grand Caravan SXT","Dodge Challenger SRT","Ford Explorer","Ford F-150","Deltaplan","Seashark","Lamborghini Aventador","Ferrari FF");
					
					while ($data = mysqli_fetch_array( $a ))
					{
						$vehiclename = $ARIZONA_VEHICLE_NAMES[($data[1]-400)];
						$msg = $msg."&#9989; Машина [$vehiclename, modelId: $data[1]] (UID: $data[0])<br>";
					}
					
					if (!mysqli_num_rows($a))
					{
						$vk->sendMessage($peer_id, '&#10060; На вашем выбранном аккаунте не найдено транспортных средств.');
						$msg = -1;
					}
					
					$BD -> close();
				}
				else
					$msg = '&#9989; Для начала выберите основной аккаунт.';
				
				break;
			}
		}
	}
	
}

else if ($data -> type == 'like_add')
{	
	if ($data -> object -> object_type == 'post')
	{
		$a = $BD_bot -> query("SELECT * FROM active WHERE postId = ".$data -> object -> object_id." AND vkId = $User[vkId];");
		
		if (!mysqli_num_rows($a))
		{
			$User[Balance] += 25;
			$vk -> sendMessage($User[vkId], "За проявленный актив вам начислено 25 💎<br><br>&#10024; Ваш баланс: $User[Balance] 💎");
			$BD_bot -> query("UPDATE users SET Balance = $User[Balance] WHERE vkId = '$User[vkId]' LIMIT 1;");
			$BD_bot -> query("INSERT INTO active (`postId`, `vkId`) VALUES (".$data -> object -> object_id.", $User[vkId])");
		}
	}
}

else if ($data -> type == 'like_remove')
{
	if ($data -> object -> object_type == 'post')
	{	
		if ($User[Balance] > 15)
			$User[Balance] -= 15;
		else
			$User[Balance] = 0;
		
		$vk -> sendMessage($User[vkId], "Вы убрали лайк!<br>Мы заберем часть бонуса которые выдали за него!<br>Вернуть назад уже никак не получится!<br>&#10024; Ваш баланс: $User[Balance] 💎");
		$BD_bot -> query("UPDATE users SET Balance = $User[Balance] WHERE vkId = '$User[vkId]' LIMIT 1;");
	}
}



	if ($msg != -1)
		$vk->sendMessage($peer_id, $msg);
	
	$BD_bot -> close();
?>
