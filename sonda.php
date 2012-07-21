<?php /* F3Sonda 2.1 (C) 2005-2012 COMPMaster */

function sonda($id = null, $wyniki = false, $duzy = false, $folder = null, $url = null, $kod = null)
{
	static $cfg,$dir;
	$sonda = $odp = array();

	#Wczytaj opcje i znajdź katalog skryptu
	if(empty($cfg))
	{
		if($folder === null)
		{
			$old = getcwd();
			$dir = str_replace('\\', '/', substr(dirname(__FILE__).'/',strlen($old)+1));
		}
		else
		{
			$dir = $folder;
		}
		if(file_exists($dir.'cfg/config.php'))
		{
			require $dir.'cfg/config.php';
		}
		else
		{
			return 'Błędny katalog skryptu!';
		}
	}

	#Względny adres URL
	if($url === null) $url = $dir;

	#ID sondy - jeśli nie podano, wczytaj aktualną
	$id = is_int($id) && $id>0 ? $id : $cfg['id'];

	#Wczytaj plik sondy
	if($id>0 && file_exists($dir.'list/'.$id.'.php'))
	{
		require $dir.'list/'.$id.'.php';
	}
	else
	{
		return sprintf('Sonda %d nie istnieje!', $id);
	}

	#Wyniki sondy wymuszone lub gdy internauta głosował
	if($wyniki || isset($_COOKIE[$cfg['ciacho'].$id]))
	{
		if($sonda['g'] < 1)
		{
			return $cfg['txtBrak'];
		}
		if($cfg['sort'] == 1)
		{
			usort($sonda['o'],create_function('$a,$b','return $b[1]<$a[1]?-1:$a[1]<$b[1];'));
		}
		elseif($cfg['sort'] == 2)
		{
			shuffle($sonda['o']);
		}
		if($duzy)
		{
			$w = $cfg['duze'];
			$s = 'style/duze.html';
		}
		else
		{
			$w = $cfg['wykres'];
			$s = 'style/wyniki.html';
		}
		if(file_exists(sprintf('%swykresy/%s.php',$dir,$w)))
		{
			include sprintf('%swykresy/%s.php',$dir,$w);
			$wykres = sonda_wykres($sonda['o'], $sonda['g'], $cfg);
		}
		else
		{
			return sprintf('Wykres %s nie istnieje!', $w);
		}

		#Wczytaj szablon, podstaw zmienne
		$t = file_get_contents($dir.$s);
		$t = str_ireplace(
			array('{PYTANIE}', '{WYKRES}', '{DATA}', '{ARCHIWUM}', '{WYNIKI}', '{ILE}'),
			array($sonda['p'], $wykres, date($cfg['data'], $sonda['d']),
			$url.'okno.php?co=archiwum', $url.'okno.php?id='.$id, $sonda['g']), $t);
	}

	#Formularz głosowania - pytanie wielokrotnego wyboru lub z 1 wyborem
	else
	{
		if($sonda['t'] == 2)
		{
			foreach($sonda['o'] as $k=>$x)
			{
				$odp[] = '<label><input type="checkbox" name="odp[]" value="'.$k.'" /> '.$x[0].'</label>';
			}
		}
		else
		{
			foreach($sonda['o'] as $k=>$x)
			{
				$odp[] = '<label><input type="radio" name="odp" value="'.$k.'" /> '.$x[0].'</label>';
			}
		}
		$odp[] = '<input type="hidden" name="id" value="'.$id.'" />';

		#Wczytaj szablon, podstaw zmienne
		$t = file_get_contents($dir.'style/pytania.html');
		$t = str_ireplace(
			array('{PYTANIE}','{ODPOWIEDZI}','{AKCJA}','{WYNIKI}'),
			array($sonda['p'],join('<br />',$odp),$url.'glos.php',$url.'okno.php?id='.$id), $t);
	}

	#Strona kodowa
	if($kod)
	{
		if($kod != 'UTF-8') $t = iconv('UTF-8', $kod, $t);
	}
	else if(isset($cfg['kod']))
	{
		$t = iconv('UTF-8', $cfg['kod'], $t);
	}

	#Token CSRF
	if(isset($cfg['csrf']))
	{
		if(session_id() === '')
		{
			if (!headers_sent())
			{
				session_start();
			}
			if (session_id() === '')
			{
				return 'Błąd: włączono dodatkowe zabezpieczenie przed CSRF, ale wymagana jest obsługa sesji PHP. Wstaw &lt;?php session_start() ?&gt; przed &lt;html&gt; lub zmień session.auto_start na 1 w php.ini.';
			}
		}
		$_SESSION['CSRF_TOKEN'] =  md5(uniqid(rand(), true));
		$t = str_ireplace('</form>','<input type="hidden" name="csrf" value="'.$_SESSION['CSRF_TOKEN'].'" /></form>',$t);
	}

	#Zwróć HTML
	return $t.'<script type="text/javascript" src="'.$url.'sonda.js"></script>';
}