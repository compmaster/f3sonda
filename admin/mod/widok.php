<?php if(F3S!=1) exit;

#Pliki do edycji
$may = array('pytania','wyniki','duze','archiwum','okno');
$ile = 0;

#Uprawnienia zapisu
foreach($may as $pole)
{
	if(is_writable('../style/'.$pole.'.html'))
	{
		if($_POST)
		{
			if(file_put_contents('../style/'.$pole.'.html', $_POST[$pole], 2) === false)
			{
				echo '<div class="FAIL">Nie udało się zapisać szablonu '.$pole.'.html</div>';
			}
			else
			{
				echo '<div class="OK">Zapisano szablon '.$pole.'.html</div>';
			}
			$$pole =& $_POST[$pole];
		}
		else
		{
			$$pole = file_get_contents('../style/'.$pole.'.html');
		}
		++$ile;
	}
	else
	{
		echo '<div class="FAIL">Brak uprawnień zapisu do pliku '.$pole.'.html. Ustaw chmod 666!</div>';
	}
}

#Wymagaj praw wszystkich plików
if($ile < 5) return;

?><form method="post">
<table>
<tr>
	<th colspan="2">Zmiana wyglądu - dla zaawansowanych</th>
</tr>
<tr>
	<td colspan="2" style="padding: 8px">Tutaj możesz zmienić kod HTML elementów sondy. Aby wygodniej edytować pliki skórki, użyj zewnętrznego edytora z kolorowaniem składni. Aby zmienić wykres, format daty, kolory i komunikaty, udaj się do działu <a href="?a=opcje">Ustawienia</a>.</td>
</tr>
<tr>
	<td style="width: 30%; text-align: left"><b>Pytanie i odpowiedzi</b><p>
		<kbd>{PYTANIE}</kbd> - pytanie<br>
		<kbd>{ODPOWIEDZI}</kbd> - opcje wyboru<br>
		<kbd>{AKCJA}</kbd> - akcja formularza</p></td>
	<td>
		<textarea name="pytania" cols="50" rows="12" style="width: 99%" spellcheck="false"><?php
		echo htmlspecialchars($pytania) ?></textarea></td>
</tr>
<tr>
	<td style="text-align: left"><b>Małe wyniki sondy</b><p>
		<kbd>{PYTANIE}</kbd> - pytanie<br>
		<kbd>{WYKRES}</kbd> - wykres z wynikami<br>
		<kbd>{WYNIKI}</kbd> - adres dużych wyników<br>
		<kbd>{ARCHIWUM}</kbd> - adres archiwum<br>
		<kbd>{ILE}</kbd> - ilość głosów</p></td>
	<td>
		<textarea name="duze" cols="50" rows="12" style="width: 99%" spellcheck="false"><?php
		echo htmlspecialchars($wyniki) ?></textarea></td>
</tr>
<tr>
	<td style="text-align: left"><b>Pełne wyniki sondy</b><p>
		<kbd>{PYTANIE}</kbd> - pytanie<br>
		<kbd>{WYKRES}</kbd> - wykres z wynikami<br>
		<kbd>{ARCHIWUM}</kbd> - adres archiwum<br>
		<kbd>{DATA}</kbd> - data utworzenia sondy<br>
		<kbd>{ILE}</kbd> - ilość głosów</p></td>
	<td>
		<textarea name="wyniki" cols="50" rows="12" style="width: 99%" spellcheck="false"><?php
		echo htmlspecialchars($duze) ?></textarea></td>
</tr>
<tr>
	<td style="text-align: left"><b>Archiwum sondaży</b><p>
		<kbd>{NAZWA}</kbd> - nazwa lub pytanie<br>
		<kbd>{ILE}</kbd> - ilość głosów<br>
		<kbd>{DATA}</kbd> - data utworzenia sondy<br><br>
		<kbd>&lt;!-- SONDY --&gt;</kbd> 2 razy!</p></td>
	<td>
		<textarea name="archiwum" cols="50" rows="12" style="width: 99%" spellcheck="false"><?php
		echo htmlspecialchars($archiwum) ?></textarea></td>
</tr>
<tr>
	<td style="text-align: left"><b>Kod HTML okienka</b><p>
		<kbd>{TYTUŁ}</kbd> - tytuł strony<br>
		<kbd>{BODY}</kbd> - treść strony</p></td>
	<td>
		<textarea name="okno" cols="50" rows="12" style="width: 99%" spellcheck="false"><?php
		echo htmlspecialchars($okno) ?></textarea></td>
</tr>
<tr>
	<th colspan="2">
		<input type="submit" value="Zapisz pliki" style="font-weight: bold">
		<input type="reset" value="Cofnij zmiany">
	</th>
</tr>
</table>
</form>