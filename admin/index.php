<?php
define('F3S',1);

#Ochrona przed CSRF
if($_POST && isset($_SERVER['HTTP_REFERER']))
{
	$pos = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
	if($pos < 3 OR $pos > 8) exit;
}

#Ochrona przed register_globals
if(ini_get('register_globals'))
{
	foreach(array_keys($_REQUEST) as $x) unset($$x);
}

#Ochrona przed magic_quotes
if(ini_get('magic_quotes_gpc'))
{
	$gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	function xxx(&$x) { $x = stripslashes($x); }
	array_walk_recursive($gpc, 'xxx');
}

#Zmienne krytyczne - konfiguracja i dane do zalogowania
$haslo = $ochrona = ''; $cfg = array();

#Wczytaj ustawienia
require '../cfg/admin.php';
require '../cfg/config.php';

#Prefix ciastek
define('PRE', $cfg['ciacho']);

#Czyszczenie zmiennych
function clean($val,$max=0)
{
	if($max) $val = substr($val,0,$max);
	return trim(htmlspecialchars($val, 2));
}

#Lista plików lub folderów jako opcje OPTION pola SELECT
function listBox($dir,$co,$ch)
{
	if(!is_dir($dir)) return '';
	$out = '';

	#Folders
	if($co == 1)
	{
		foreach(scandir($dir) as $x)
		{
			if(is_dir($dir.'/'.$x) && $x[0]!='.')
			{
				$out.= '<option'.(($ch==$x)?' selected="selected"':'').'>'.$x.'</option>';
			}
		}
	}
	#Pliki
	else
	{
		foreach(scandir($dir) as $x)
		{
			if(is_file($dir.'/'.$x))
			{
				$x = str_replace('.php', '', $x);
				$out.= '<option'.(($ch==$x)?' selected="selected"':'').'>'.$x.'</option>';
			}
		}
	}
	return $out;
}

#Kodowanie
header('Content-Type: text/html; charset=utf-8');

#Zalogowany
if(isset($_COOKIE[PRE.'PA']) && $_COOKIE[PRE.'PA'] == $haslo)
{
	#Glowny szablon panelu admina
	$tpl = explode('{MAIN}', file_get_contents('../style/admin.html'));

	#Brak sekcji MAIN
	if(!isset($tpl[1])) exit('Szablon admin.html ma błędy. Brak sekcji {MAIN}.');

	#Nie bedziemy wysylac naglowkow ani ciasteczek, mozemy sobie na to pozwolic
	echo $tpl[0];

	#Zaladuj modul PA
	switch(isset($_GET['a']) ? $_GET['a'] : '')
	{
		case 'sondy': require './mod/sondy.php'; break;
		case 'sonda': require './mod/sonda.php'; break;
		case 'opcje': require './mod/opcje.php'; break;
		case 'pomoc': require './mod/pomoc.php'; break;
		case 'widok': require './mod/widok.php'; break;
		default: require './mod/start.php';
	}

	#Stopka i koniec HTML
	echo $tpl[1];
}
else
{
	#Zaloguj
	if($_POST)
	{
		if(empty($haslo))
		{
			if(isset($_POST['H'][4]) && $_POST['H'] != 'admin')
			{
				require '../saver.php';
				try
				{
					$f = new Saver('../cfg/admin.php');
					$f -> add('ochrona', $ochrona=uniqid());
					$f -> add('haslo', $haslo=md5($ochrona.$_POST['H']));
					$f -> save();
					setcookie(PRE.'PA', $haslo, 0, '', '', 0, 1);
					header('Location: index.php');
					return 1;
				}
				catch(Exception $e)
				{
					$txt = 'BŁĄD: '.$e->getMessage();
				}
			}
			else
			{
				$txt = 'Hasło jest za łatwe. Ustaw mocniejsze';
			}
		}
		elseif($haslo == md5($ochrona.$_POST['H']))
		{
			setcookie(PRE.'PA', $haslo, 0, '', '', 0, 1);
			header('Location: index.php');
			return 1;
		}
		else
		{
			$txt = 'Hasło nie pasuje. Spróbuj ponownie';
		}
	}

	#Pierwsze logowanie
	elseif(empty($haslo))
	{
		$txt = 'Ustaw nowe hasło dostępu';
	}

	#Logowanie wtórne
	else
	{
		$txt = 'Podaj hasło i naciśnij Enter';
	}

	#Formularz logowania
	echo str_replace('{POLECENIE}', $txt, file_get_contents('../style/login.html'));
}