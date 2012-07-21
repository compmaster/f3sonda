<?php if(F3S!=1) exit;

#Zapis ustawień
if($_POST)
{
	$u = array(
		'id'     => (int)$_POST['id'],
		'on'     => isset($_POST['on']) ? 1 : null,
		'dawne'  => isset($_POST['dawne']) ? 1 : null,
		'loguj'  => isset($_POST['loguj']) ? 1 : null,
		'paleta' => isset($_POST['paleta']) ? 1 : null,
		'csrf'   => isset($_POST['csrf']) ? 1 : null,
		'kod'    => empty($_POST['kod']) ? null : clean($_POST['kod']),
		'max'    => empty($_POST['max']) ? null : 1,
		'blok'   => is_numeric($_POST['blok']) ? (int)$_POST['blok'] : 90,
		'ciacho' => preg_match('/^[a-zA-Z0-9_]{0,20}$/', $_POST['ciacho']) ? $_POST['ciacho'] : 'F3_',
		'data'   => clean($_POST['data']),
		'path'   => clean($_POST['path']),
		'bany'   => clean($_POST['bany']),
		'color'  => clean($_POST['color']),
		'wykres' => clean($_POST['wykres']),
		'duze'   => clean($_POST['duze']),
		'sort'   => (int)$_POST['sort'],
		'cyfr'   => (int)$_POST['cyfr'],
		'arch'   => (int)$_POST['arch'],
		'txtOK'  => $_POST['txtOK'],
		'txtBrak'=> $_POST['txtBrak'],
		'txtJuz' => $_POST['txtJuz'],
		'txtBan' => $_POST['txtBan'],
		'txtOff' => $_POST['txtOff']
	);

	include '../saver.php';
	try
	{
		if(isset($_POST['nowykod']) || !empty($_POST['nowehaslo']))
		{
			$adm = new Saver('../cfg/admin.php');
			$adm->add('ochrona', isset($_POST['nowykod']) ? $kod = uniqid() : $ochrona);
			$adm->add('haslo', empty($_POST['nowehaslo']) ? $haslo : md5($kod.$_POST['nowehaslo']));
			$adm->save();
		}
		$main = new Saver('../cfg/config.php');
		$main->save($u);
		echo '<div class="OK">(: Zapisano ustawienia :)</div>';
	}
	catch(Exception $e)
	{
		echo '<div class="FAIL">Nie można zapisać ustawień. Powód: '.$e->getMessage().'</div>';
	}
}
else
{
	$u =& $cfg;
}

?><form method="post">
<div style="float: right; width: 640px">
<table style="text-align: left">
<colgroup>
	<col style="width: 50%">
	<col style="width: 50%">
</colgroup>
<tbody id="ogolne">
<tr>
	<th colspan="2">Ogólne ustawienia</th>
</tr>
<tr>
	<td>
		<b>Aktualna sonda:</b><br>
		<small>Sonda, która ma być aktualnie wyświetlana.</small>
	</td>
	<td>
		<select name="id"><?php

		$sondy = array();
		if(file_exists('../list/index.php'))
		{
			include '../list/index.php';
		}
		else
		{
			$sondy[0] = array('-- brak --');
		}
		foreach($sondy as $id=>$x)
		{
			echo '<option value="'.$id.'"'.($u['id'] == $id?' selected':'').'>'.$x[0].'</option>';
		}
		unset($sondy,$x,$id) 
		
		?></select>
	</td>
</tr>
<tr>
	<td>
		<b>Nazwa ciasteczek:</b><br>
		<small>Prefiks nazw zapisywanych plików na komputerach użytkowników. Ustaw własną!</small>
	</td>
	<td>
		<input name="ciacho" value="<?php echo $u['ciacho'] ?>" size="9">
		Tylko: litery, cyfry, _ -
	</td>
</tr>
<tr>
	<td>
		<b>Ścieżka ciasteczek:</b><br>
		<small>Ciastka będą dostępne dla tego katalogu, np. /strona/</small>
	</td>
	<td><input name="path" value="<?php echo $u['path'] ?>"></td>
</tr>
<tr>
	<td>
		<b>Sposób wyświetlania wyników:</b><br>
		<small>Wybierz pliki wgrane do katalogu 'wykresy'</small>
	</td>
	<td>
		<select name="wykres"><?php
			$o1 = $o2 = '';
			foreach(scandir('../wykresy') as $x)
			{
				if($x[0]!='.' && substr($x,-4)=='.php')
				{
					$x = substr($x, 0, -4);
					$o1.='<option'.(@$u['wykres']==$x?' selected':'').'>'.$x.'</option>';
					$o2.='<option'.(@$u['duze']==$x?' selected':'').'>'.$x.'</option>';
				}
			}
		echo $o1.'</select> dla małych<br><select name="duze">'.$o2.'</select>' ?> dla pełnych
	</td>
</tr>
<tr>
	<td>
		<b>Format dat:</b><br>
		<small>Patrz w podręczniku PHP: <a href="http://php.net/date" target="_blank">date()</a></small>
	</td>
	<td>
		<input name="data" value="<?php echo $u['data'] ?>" style="font-family:monospace">
	</td>
</tr>
<tr>
	<td>
		<b>Strona kodowa:</b><br>
		<small>Wybierz kodowanie znaków, jakiego używasz.</small>
	</td>
	<td>
		<select name="kod" style="font-family:monospace">
			<option value="">UTF-8</option>
			<option<?php if(isset($u['kod']) && $u['kod']=='ISO-8859-2') echo ' selected'?>>ISO-8859-2</option>
			<option<?php if(isset($u['kod']) && $u['kod']=='windows-1250') echo ' selected'?>>Windows-1250</option>
		</select>
	</td>
</tr>
<tr>
	<td>
		<b>Co wyświetlać w archiwum?</b><br>
		<small>Określa, czy w archiwum na liście mają pojawiać się pytania sondaży, czy ich nazwy.</small>
	</td>
	<td>
		<label><input type="radio" name="arch" value="1"<?php if($u['arch']==1)echo' checked'?>>
		Pytania</label><br>
		<label><input type="radio" name="arch" value="2"<?php if($u['arch']!=1)echo' checked'?>>
		Nazwy sondaży</label>
	</td>
</tr>
</tbody>
<tbody id="teksty">
<tr>
	<th colspan="2">Teksty i błędy</th>
</tr>
<tr>
	<td><b>Głos został zaliczony:</b></td>
	<td><input name="txtOK" size="50" value="<?php echo clean($u['txtOK']) ?>"></td>
</tr>
<tr>
	<td><b>Brak głosów (wyniki):</b></td>
	<td><input name="txtBrak" size="50" value="<?php echo clean($u['txtBrak']) ?>"></td>
</tr>
<tr>
	<td><b>Użytkownik już głosował:</b></td>
	<td><input name="txtJuz" size="50" value="<?php echo clean($u['txtJuz']) ?>"></td>
</tr>
<tr>
	<td><b>Zabanowany użytkownik głosuje:</b></td>
	<td><input name="txtBan" size="50" value="<?php echo clean($u['txtBan']) ?>"></td>
</tr>
<tr>
	<td><b>Głosowanie wyłączone:</b></td>
	<td><input name="txtOff" size="50" value="<?php echo clean($u['txtOff']) ?>"></td>
</tr>
</tbody>
<tbody id="sonda">
<tr>
	<th colspan="2">Ustawienia sondy i wyników</th>
</tr>
<tr>
	<td><b>Głosowanie aktywne:</b></td>
	<td><input type="checkbox" name="on"<?php if(isset($u['on']))echo' checked'?>></td>
</tr>
<tr>
	<td>
		<b>Pozwól głosować w starych sondach:</b><br>
		<small>Głosowanie w sondach archiwalnych.</small>
	</td>
	<td><input type="checkbox" name="dawne"<?php if(isset($u['dawne']))echo' checked'?>></td>
</tr>
<tr>
	<td>
		<b>Zapisuj adresy IP głosujących:</b><br>
		<small>Zwiększa ochronę przed ponownym głosowaniem.</small>
	</td>
	<td><input type="checkbox" name="loguj"<?php if(isset($u['loguj']))echo' checked'?>></td>
</tr>
<tr>
	<td>
		<b>Dodatkowa ochrona przed CSRF:</b><br>
		<small>Włącza dodatkowe zabezpieczenie przed atakiem <a href="https://owasp.org/www-community/attacks/csrf" target="_blank">CSRF</a>. Wymaga dodania &lt;?php session_start() ?&gt; przed &lt;html&gt; na twojej stronie.</small>
	</td>
	<td><input type="checkbox" name="csrf"<?php if(isset($u['csrf']))echo' checked'?>></td>
</tr>
<tr>
	<td>
		<b>Blokada przed ponownym głosowaniem:</b><br>
		<small>Podaj czas ważności blokady (ciasteczka).</small>
	</td>
	<td>
		<input name="blok" type="number" size="5" value="<?php echo $u['blok'] ?>"> dni
	</td>
</tr>
<tr>
	<td><b>Kolor słupków:</b><br><small>Domyślny kolor słupków.</small></td>
	<td><input type="color" name="color" size="25" value="<?php echo $u['color'] ?>"></td>
</tr>
<tr>
	<td>
		<b>Rysuj słupki względem:</b><br>
		<small>Wybierz sposób obliczania długości słupków.</small>
	</td>
	<td>
		<label><input type="radio" name="max" value="1"<?php if(isset($u['max']))echo' checked'?>>
		Największego słupka</label><br>
		<label><input type="radio" name="max" value="0"<?php if(empty($u['max']))echo' checked'?>>
		Ilości głosów</label>
	</td>
</tr>
<tr>
	<td><b>Sortuj wyniki po ilości głosów:</b></td>
	<td>
		<label><input type="radio" name="sort" value="1"<?php if($u['sort']==1)echo' checked'?>>
		Włącz</label>
		<label><input type="radio" name="sort" value="2"<?php if($u['sort']==2)echo' checked'?>>
		Tasuj</label>
		<label><input type="radio" name="sort" value="0"<?php if(empty($u['sort']))echo' checked'?>>
		Wyłącz</label>
	</td>
</tr>
<tr>
	<td>
		<b>Zaokrąglij procent do:</b><br>
		<small>Wpisz 0, aby wyświetlać liczby całkowite.</small>
	</td>
	<td>
		<input type="number" name="cyfr" size="5" value="<?php echo $u['cyfr'] ?>"> miejsc po przecinku
	</td>
</tr>
<tr>
	<td>
		<b>Lista zabanowanych:</b><br>
		<small>Wpisz adresy IP użytkowników, którzy nie będą mogli głosować. Każdy w osobnej linii!</small>
	</td>
	<td>
		<textarea rows="2" cols="25" name="bany"><?php echo $u['bany'] ?></textarea>
	</td>
</tr>
</tbody>
<tbody id="panel">
<tr>
	<th colspan="2">Panel administracyjny</th>
</tr>
<tr>
	<td colspan="2">Te dane są bardzo ważne ze względu na możliwość dostania się do panelu administracyjnego niepożądanych osób.</td>
</tr>
<tr>
	<td>
		<b>Nowe hasło:</b><br>
		<small>1. Nie używaj zbyt prostego hasła.<br>
		2. Ustaw takie hasło, które zapamiętasz.<br>
		3. Nic nie wpisuj, aby zostawić aktualne!</small>
	</td>
	<td>
		<input name="nowehaslo" value="" type="password">
	</td>
</tr>
<tr>
	<td>
		<b>Kod ochrony:</b><br>
		<small>Utrudnia zdobycie hasła z ciasteczka.</small>
	</td>
	<td>
		<label><input name="nowykod" type="checkbox"> Wygeneruj nowy kod ochrony</label>
	</td>
</tr>
<tr>
	<td>
		<b>Paleta kolorów HTML 5:</b><br>
		<small>Dostępna tylko w nowych wersjach przeglądarek!</small>
	</td>
	<td>
		<label><input type="checkbox" name="paleta"<?php if(isset($u['paleta']))echo' checked'?>>
		Użyj systemowej palety kolorów</label>
	</td>
</tr>
</tbody>
</table>
</div>

<div style="float: left; width: 160px; margin-right: 15px; line-height: 20px">
<table>
<tr>
	<th style="line-height: normal">Menu ustawień</th>
</tr>
<tr>
	<td style="text-align: left">
		× <a href="javascript:pokaz('ogolne')">Opcje ogólne</a><br>
		× <a href="javascript:pokaz('sonda')">Głosowanie i wyniki</a><br>
		× <a href="javascript:pokaz('panel')">Panel administracji</a><br>
		× <a href="javascript:pokaz('teksty')">Teksty i błędy</a>
	</td>
</tr>
<tr>
	<td style="text-align: left">
		× <a href="javascript:pokaz(0)">Pokaż wszystko</a><br>
		× <a href="javascript:document.forms[0].reset()">Cofnij zmiany</a>
	</td>
</tr>
<tr>
	<td style="padding: 8px 0">
		<input type="submit" value="Zapisz ustawienia" style="width: 130px">
	</td>
</tr>
<tr>
	<th>Informacje</th>
</tr>
<tr>
	<td style="padding: 5px">
		Głosowanie jest <b><?php if(empty($u['on'])) echo 'nie' ?>aktywne</b>
	</td>
</tr>
</table>
</div>
</form>

<script type="text/javascript">
function pokaz(co)
{
	var d = co ? 'none' : '';
	document.getElementById('ogolne').style.display = d;
	document.getElementById('panel').style.display = d;
	document.getElementById('sonda').style.display = d;
	document.getElementById('teksty').style.display = d;
	if(co) document.getElementById(co).style.display = '';
}
pokaz('ogolne')
</script>