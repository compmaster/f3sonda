<?php F3S==1||exit;

#Wczytaj indeks sond
$sondy = array();
@include '../list/index.php';

#Usuwanie
if(isset($_POST['usun']) && isset($_POST['u']) && !empty($sondy))
{
	foreach($_POST['u'] as $id=>$x)
	{
		unset($sondy[$id]);
		unlink('../list/'.$id.'.php');
		@unlink('../list/IP'.$id.'.php');
	}
	require '../saver.php';
	try
	{
		if(empty($sondy[$id]))
		{
			$cfg['id'] = end($sondy) ? key($sondy) : 0;
			$plik = new Saver('../cfg/config.php');
			$plik -> add('cfg', $cfg);
			$plik -> save();
		}
		$index = new Saver('../list/index.php');
		$index -> add('sondy', $sondy);
		$index -> save();
	}
	catch(Exception $e)
	{
		echo '<div class="FAIL">Usuwanie nie powiodło się: '.$e.'</div>';
	}
}

#Odbuduj index
elseif(isset($_POST['napraw']))
{
	$sondy = array();
	foreach(scandir('../list') as $plik)
	{
		if(strpos($plik, '.php') > 0 && is_numeric($id = substr($plik,0,-4)))
		{
			$sonda = array();
			include '../list/'.$plik;
			if($sonda) $sondy[$id] = array($sonda['n'],$sonda['p'],$sonda['g'],$sonda['d']);
		}
	}
	require '../saver.php';
	@copy('../list/index.php', '../list/indexBackup.php');
	try
	{
		$index = new Saver('../list/index.php');
		$index -> add('sondy', $sondy);
		$index -> save();
	}
	catch(Exception $e)
	{
		echo '<div class="FAIL">Nie udało się odbudować indeksu: '.$e.'</div>';
	}
}

#Odwróć kolejność
$sondy = array_reverse($sondy, true);
$i = count($sondy);

?>
<form action="?a=sondy" method="post"><table><tr>
<th>Sonda</th>
<th style="width: 70px">Głosów</th>
<th style="width: 130px">Dodano</th>
<th style="width: 20px"></th></tr>
<?php

foreach($sondy as $id=>$x)
{
  echo '
	<tr>
		<td><b><a href="?a=sonda&amp;id='.$id.'">'.($i--).'. '.$x[0].'</a></b></td>
		<td><a href="..?id='.$id.'">'.$x[2].'</a></td>
		<td>'.date('d.m.Y',$x[3]).'</td>
		<td><input type="checkbox" name="u['.$id.']"></td>
	</tr>';
}
?>
<tr>
	<th colspan="4">
		<input type="submit" name="usun" value="Usuń zaznaczone" onclick="return confirm('Usunąć bezpowrotnie zaznaczone sondy?')">
		<input type="submit" name="napraw" value="Napraw indeks" onclick="return confirm('Funkcja wyszuka na dysku istniejące sondy i wykona kopię zapasową poprzedniego indeksu. Czy chcesz kontynuować?')">
	</th>
</tr>
</table>
</form>