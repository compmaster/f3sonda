<?php if(F3S!=1) exit;

#Aktualna sonda
if(file_exists('../list/'.$cfg['id'].'.php'))
{
	$sonda = array();
	include '../list/'.$cfg['id'].'.php';
	$name = $sonda['n'];
	$id = $cfg['id'];
}
else
{
	$name = 'brak danych';
	$id = '0';
}

#Uprawnienia
$checklist = array('cfg','list');
$bad = false;

foreach($checklist as $dir)
{
	$path = '../'.$dir.'/';
	if(!is_readable($path) || !is_writable($path))
	{
		@chmod($path, 0777);
		if(!is_writable($path)) $bad = true;
	}
	foreach(scandir($path) as $file)
	{
		if(substr($file,-4)==='.php' && !(is_readable($path.$file) && is_writable($path.$file)))
		{
			@chmod($path.$file, 0666);
			if(!is_writable($path.$file)) $bad = true;
		}
	}
}
if($bad)
{
	echo '<div class="FAIL">Niektóre pliki na serwerze mają błędne uprawnienia dostępu. <a href="?a=pomoc#konf">Kliknij tutaj</a>, aby uzyskać pomoc i naprawić problem.</div>';
}
?>
<table>
<tr>
	<th colspan="2">Strona główna - podsumowanie</th>
</tr>
<tr>
	<td style="width: 349px">
		<b>Aktualna sonda:</b><small><br>(kliknij aby edytować)</small>
		<div style="padding: 8px"><a href="?a=sonda&amp;id=<?php echo $id ?>"><?php echo $name ?></a></div>
	</td>
	<td style="text-align: left; width: 350px">Witaj w panelu administracyjnym skryptu F3Sonda. W tym miejscu możesz zapoznać się z dokumentacją, dostosować ustawienia oraz w łatwy sposób zarządzać sondażami. Aby kontynuować, wybierz odpowiedni moduł z menu.</td>
</tr>
<tr>
	<th colspan="2">Informacje</th>
</tr>
<tr>
	<td style="text-align: left">
		<b>System:</b> <?php echo PHP_OS ?><br>
		<b>Twój adres IP:</b> <?php echo $_SERVER['REMOTE_ADDR'] ?><br>
		<b>Wersja skryptu:</b> 2.1
	</td>
	<td style="text-align: left">
		<b>Oprogramowanie serwera:</b> <?php echo $_SERVER['SERVER_SOFTWARE'] ?>
	</td>
</tr>
</table>