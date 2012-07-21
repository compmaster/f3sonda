<?php if(!$_POST || empty($_POST['id'])) exit;

#Wykrywacz ataku CSRF bada nagłówek Referer
if(isset($_SERVER['HTTP_REFERER']))
{
	$pos = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
	if($pos < 3 OR $pos > 8) exit;
}

#Wczytaj plik konfiguracyjny i wymagane biblioteki
require './cfg/config.php';
require './saver.php';

#Utwórz tablice
$IP = $sonda = $sondy = array();
$id = (int)$_POST['id'];

#Dodatkowe zabezpieczenie przed CSRF
if(isset($cfg['csrf']))
{
	session_start();
	if(empty($_SESSION['CSRF_TOKEN']) || $_SESSION['CSRF_TOKEN']!==$_POST['csrf'])
	{
		unset($_SESSION['CSRF_TOKEN']);
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			header('HTTP/1.0 419 Page Expired');
			exit($_SESSION['CSRF_TOKEN'] = md5(uniqid(rand(), true)));
		}
		else
		{
			header('HTTP/1.0 403 Forbidden');
			exit('Sesja wygasła. Wróć i zagłosuj ponownie.');
		}
	}
}

#Kodowanie
header('Content-Type: text/html; charset=utf-8');

#Głosowanie wyłączone
if(empty($cfg['on']))
{
	exit($cfg['txtOff']);
}

#Czy istnieje ciacho
if(isset($_COOKIE[$cfg['ciacho'].$id]))
{
	exit($cfg['txtJuz']);
}

#Czarna lista
if(strpos($cfg['bany'],$_SERVER['REMOTE_ADDR']) !== false)
{
	exit($cfg['txtBan']);
}

#Stara sonda lub nie istnieje
if($id!=$cfg['id'] && empty($cfg['dawne']) || !is_writable('list/'.$id.'.php'))
{
	printf('Sonda %d nie istnieje!', $id); exit;
}

#Pozwalam głosować - wczytaj indeks i sondę
require './list/index.php';
require './list/'.$id.'.php';

#Ustawa hazadowa - zbadaj spójność
if(empty($sonda) OR empty($sondy))
{
	exit('Błąd wewnętrzny! Naciśnij F5, aby ponowić.');
}

#Indeks IP - wczytaj
if(isset($cfg['loguj']) && file_exists('list/IP'.$id.'.php'))
{
	require './list/IP'.$id.'.php';
}

#Dopisz głosy w zależności od typu ankiety
if($sonda['t'] == 2)
{
	$i = 0;
	$odp = array();
	foreach($_POST['odp'] as $x)
	{
		if(isset($sonda['o'][$x]))
		{
			$odp[] = $x;
			++$i;
			++$sonda['o'][$x][1];
		}
	}
	if($i < 1)
	{
		exit('Musisz zaznaczyć co najmniej 1 odpowiedź!');
	}
	$odp = join('I',$odp);
}
elseif(isset($sonda['o'][$_POST['odp']]))
{
	++$sonda['o'][$odp = $_POST['odp']][1];
}
else
{
	exit('Masz zaznaczyć 1 odpowiedź!');
}

#Zwiększ ilość głosów
++$sonda['g'];
++$sondy[$id][2];

#Jeżeli IP zalogowany, upozoruj powodzenie
if(setcookie($cfg['ciacho'].$id, $odp, time()+86400*$cfg['blok'], $cfg['path']))
{
	if(!in_array($_SERVER['REMOTE_ADDR'], $IP))
	{
		try
		{
			$ps = new Saver('list/'.$id.'.php');
			$pi = new Saver('list/index.php');
			$ps->add('sonda',$sonda);
			$pi->add('sondy',$sondy);
			$ps->save();
			$pi->save();
		}
		catch(Exception $e)
		{
			printf('Błąd: %s', $e->getMessage());
			exit;
		}

		#Zapisz IP do indeksu i nie wywalaj błędów
		if(isset($cfg['loguj']))
		{
			try
			{
				$IP[] = $_SERVER['REMOTE_ADDR'];
				$pi = new Saver('list/IP'.$id.'.php');
				$pi->add('IP',$IP);
				$pi->save();
			}
			catch(Exception $e){}
		}
	}
}
else
{
	exit('Nie udało się ustawić ciasteczka!');
}

#W końcu...
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
{
	include 'wykresy/'.$cfg['wykres'].'.php';
	$wykres = sonda_wykres($sonda['o'], $sonda['g'], $cfg);
	$k = dirname($_SERVER['PHP_SELF']).'/okno.php?';
	$u = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'/okno.php');
	$t = file_get_contents($u ? 'style/duze.html' : 'style/wyniki.html');
	echo str_ireplace(array('{PYTANIE}','{WYKRES}','{DATA}','{ARCHIWUM}','{WYNIKI}','{ILE}'),
	array($sonda['p'],$wykres,date($cfg['data'],$sonda['d']),$k.'co=archiwum',$k.'id='.$id,$sonda['g']),$t);
}
else
{
	header('Refresh: 3; url=okno.php?id='.$id);
	echo $cfg['txtOK'];
}