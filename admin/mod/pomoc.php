<?php if(F3S!=1) exit ?>

<table>
<tr>
	<th colspan="2">Pomoc</th>
</tr>
<tr>
	<td style="width:25%; padding:5px 8px; text-align:left; vertical-align:top; line-height:150%">
		<h3>Spis treści</h3>
		<a href="#ogolne" onclick="pokaz('ogolne')">1. O skrypcie</a><br>
		<a href="#inst" onclick="pokaz('inst')">2. Instalacja</a><br>
		<a href="#konf" onclick="pokaz('konf')">3. Konfiguracja</a><br>
		<a href="#zaa" onclick="pokaz('zaa')">4. Zaawansowane</a><br>
		<a href="#mb" onclick="pokaz('mb')">5. Możliwe błędy</a>
	</td>
	<td class="txt" style="padding:7px; text-align:left; vertical-align:top">
		<div id="ogolne">

<h3>Co to jest F3Sonda?</h3>
<p>F3Sonda jest polskim skryptem umożliwiającym w łatwy sposób tworzenie sondaży oraz zarządzanie nimi z poziomu panelu administracyjnego. Dostępny całkowicie bez opłat.</p>
<h3>Ważniejsze możliwości</h3>
<p>1. Panel administracyjny i ustawienia<br>
2. Tworzenie i edytowanie sondaży<br>
3. Archiwum starszych sondaży<br>
4. Tworzenie sondaży z możliwością wielu odpowiedzi<br>
5. Banowanie użytkowników<br>
6. Zapisywanie adresów IP głosujących<br>
7. Możliwość użycia różnych wykresów<br>
8. Edycja szablonów z panelu admina<br>
9. Ochrona przed wielokrotnym głosowaniem</p>
<h3>Błędy i problemy</h3>
<p>Potrzebujesz pomocy? Opisz problem na forum serwisu <a href="https://github.com/compmaster">COMPMaster</a>. Jeśli podczas użytkowania skryptu zauważysz błąd, zgłoś go na forum. Podaj szczegóły.</p>
<h3>Wykonuj kopię zapasową</h3>
<p>Wystarczy, że skopiujesz katalog sondy na komputer za pomocą programu FTP. Wykonuj kopię co najmniej raz na miesiąc, aby zapobiec utracie danych w razie awarii serwera.</p>

	</div>
	<div id="inst">

<h3>Pierwsze użycie</h3>
<p>Po ściągnięciu skryptu i zalogowaniu się do panelu admina dokładnie przejrzyj ustawienia. Następnie zmodyfikuj testowy sondaż lub utwórz nowy.</p>
<h3>Jak wstawić sondaż?</h3>
<p>W miejscu, w którym chcesz dołączyć sondę, wstaw kod:</p>
<?php highlight_string('<?php
include \'sonda/sonda.php\';
echo sonda();
?>'); ?><br>
<p><b>UWAGA!</b> Pamiętaj, aby podać właściwą ścieżkę do folderu ze skryptem. Skrypt jest dostosowany pod strony, które mają już w dokumencie określony wygląd lub dołączony arkusz CSS. Jeśli tak nie jest, należy ręcznie ustawić czcionki, rozmiar, kolory...</p>
<p>Możesz wyświetlić konkretny sondaż i wymusić wyświetlenie wyników. Oto przykłady:</p>
<?php highlight_string('<?php
include \'sonda/sonda.php\';
echo sonda(5); //sondaż o identyfikatorze 5
echo sonda(2, true); //wyniki sondażu o ID 2
?>');
?>

	</div>
	<div id="konf">

<h3>Prawa dostępu do plików!</h3>
<p>Ustawienie odpowiednich praw dostępu do plików na serwerze jest konieczne, aby skrypt mógł je modyfikować. Brak odpowiednich uprawnień uniemożliwi głosowanie w sondażach. Atrybuty możesz zmienić m.in. w programach FileZilla i Total Commander.</p>
<ul style="margin: 3px 0 15px -20px"><li>Katalogi: cfg, list, style - <b>777</b></li><li>Pliki w powyższych katalogach - <b>666</b></li></ul>
<h3>Do czego służą te pliki?</h3>
<ul style="margin: 5px 0 15px -20px"><li>sonda.php - główna biblioteka, którą dołączasz do twojej strony</li><li>okno.php - dokument, który zawsze jest otwierany w nowym oknie</li><li>Pliki w admin/mod - znajdują się tam moduły panelu admina</li><li>Plik list/index.php - indeks wszystkich sond, warunkuje ich wykrycie</li><li>Katalog list - znajdują się w nich informacje o sondach</li></ul>
<h3>Zmiana wyglądu</h3>
<p>W F3Sonda szablony poszczególnych elementów znajdują się w katalogu style. Najlepiej edytować je w edytorze stron WWW z kolorowaniem składni. W panelu admina znajduje się też prosty edytor tych plików z objaśnieniami. Odwiedź także dział Ustawienia.</p>

	</div>
	<div id="zaa">

<h3>Ścieżki i kodowanie UTF-8</h3>
<p>Jeżeli nie podasz ścieżki do folderu skryptu, zostanie wykryta automatycznie. Nie zawsze jest to możliwe. Możesz ręcznie podać właściwą ścieżkę do plików oraz początek adresu archiwum, wyników i głosowania, gdzie prowadzą łącza &lt;a&gt; w przeglądarce:</p>
<?php highlight_string('<?php
$folder = \'inny/katalog/\';     // Pamiętaj o ukośniku / na końcu
$adres  = \'/sonda/\';           // Względem głównego katalogu
include $folder . \'sonda.php\';
echo sonda(null, false, false, $folder, $adres);
?>'); ?>
<p>Jeżeli używasz kodowania UTF-8, zmień stronę kodową w ustawieniach. Serwer dokona konwersji znaków. Możesz też podać kodowanie jako 6 parametr funkcji sonda().</p>
<?php highlight_string('<?php
$folder = __DIR__.\'/sonda/\';
$adres  = \'/sonda/\';
include $folder . \'sonda.php\';
echo sonda(null, false, false, $folder, $adres, \'UTF-8\');
?>'); ?>
<p>UWAGA! Folder <b>sonda</b> musi być w katalogu pliku, do którego wstawisz powyższy kod. Jeżeli jest inaczej, podaj właściwe ścieżki ręcznie jak w pierwszym przykładzie!</p>
	</div>
	<div id="mb">

<h3>Nie widać sond, wyświetla się błąd</h3><p>
1. Nieprawidłowa instalacja skryptu - brak plików na serwerze<br>
2. Błędna ścieżka do katalogu skryptu w include()<br>
3. Błędy podczas wgrywania plików na serwer<br>
4. Brak uprawnień odczytu dla plików<br>
5. Niepoprawne wywołanie funkcji sonda()</p>
<h3>Zalogowanie się do admina jest niemożliwe</h3><p>
1. Nieuwzględnienie wielkości liter w haśle<br>
2. Złe hasło - upewnij się, że wpisujesz prawidłowe<br>
3. Nieprawidłowo przesłane pliki na serwer<br>
4. Twoja przeglądarka nie przyjmuje ciasteczek<br>
5. Naciśnij F5, aby wymusić ponowne pobranie strony</p>
<h3>Nie da się głosować</h3><p>
1. Brak uprawnień zapisu do plików - <a href="#konf" onclick="pokaz('konf')">czytaj więcej</a><br>
2. Użytkownik już głosował w danym sondażu</p>
<h3>Blokowanie przed ponownym głosowaniem nie działa</h3><p>
1. Użytkownik ma wyłączone ciasteczka w przeglądarce<br>
2. Użytkownik ma zmienny adres IP lub zmienia proxy</p>
	</div>
	</td>
</tr>
</table>

<script type="text/javascript">
function pokaz(co)
{
	document.getElementById('ogolne').style.display = 'none';
	document.getElementById('inst').style.display = 'none';
	document.getElementById('konf').style.display = 'none';
	document.getElementById('zaa').style.display = 'none';
	document.getElementById('mb').style.display = 'none';
	document.getElementById(co).style.display = '';
}
if(location.hash) pokaz(location.hash.substr(1));
else pokaz('ogolne');
onpopstate = function() {pokaz(location.hash.substr(1)||'ogolne')}
</script>