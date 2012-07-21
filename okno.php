<?php

#Kodowanie
header('Content-Type: text/html; charset=utf-8');

#ID sondy
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

#Jeżeli wybrano akcję... jeśli nie, idź do ELSE
if(isset($_GET['co']))
{
	if($_GET['co'] == 'archiwum')
	{
		$TITLE = 'Archiwum sondaży';
		$cfg = $sondy = array();

		#Opcje i indeks sond
		require './cfg/config.php';
		require './list/index.php';

		#Czy szablon istnieje
		if(!file_exists('style/archiwum.html')) exit('Szablon archiwum.html nie istnieje!');

		#Podziel szablon
		list($OUT,$mid,$kon) = explode('<!-- SONDY -->',file_get_contents('style/archiwum.html'));
		if(!$mid || !$kon) exit('Szablon archiwum.html jest uszkodzony!');

		#Wypisz sondy
		foreach($sondy as $id=>$x)
		{
			$OUT .= str_replace(
				array('{NAZWA}', '{ILE}', '{DATA}', '{ADRES}'),
				array($cfg['arch']==2 ? $x[0] : $x[1], $x[2],
				date($cfg['data'],$x[3]), '?co=sonda&amp;id='.$id), $mid);
		}
		$OUT .= $kon;
	}
	else
	{
		#Wyniki lub formularz
		require './sonda.php';
		$TITLE = 'Archiwalny sondaż';
		$OUT = sonda($id, isset($cfg['dawne']), true);
	}
}
else
{
	#Wyniki - zawsze duże
	require './sonda.php';
	$TITLE = 'Wyniki sondażu';
	$OUT = sonda($id, true, true);
}

#Szablon
$T = file_get_contents('style/okno.html');
$T = str_replace('{TYTUŁ}', $TITLE, $T);
echo str_replace('{BODY}', $OUT, $T);