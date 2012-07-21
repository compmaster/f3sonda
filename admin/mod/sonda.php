<?php if(F3S!=1) exit;

#ID sondy
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
$sonda = array();
$color = isset($cfg['color']) ? clean($cfg['color']) : '#339999';

#Czy sonda istnieje
if($id)
{
	if(file_exists('../list/'.$id.'.php'))
	{
		include '../list/'.$id.'.php';
	}
	else
	{
		printf('<div class="FAIL">Sonda %d nie istnieje! Zostanie utworzona nowa.</div>', $id);
		$id = 0;
	}
}

#Zapisz
if($_POST)
{
	$sondy = array();
	$sonda = array(
		'n' => clean($_POST['n'] ? $_POST['n'] : $_POST['p']),
		'p' => $_POST['p'],
		'o' => array(),
		't' => (int)$_POST['t'],
		'g' => (int)$_POST['w'],
		'd' => isset($sonda['d']) ? $sonda['d'] : time()
	);

	#Wczytaj bazę
	@include '../list/index.php';

	#Reset wyników
	if(isset($_POST['reset']))
	{
		$_POST['g'] = array_fill(0, count($_POST['o']), 0);
		$id && $sondy[$id][2] = 0;
	}

	#Odpowiedzi
	for($i=0, $ile=count($_POST['o']); $i<$ile; ++$i)
	{
		if($_POST['o'][$i]!='')
		{
			$color = preg_match('/[A-Z0-9#]/i',$_POST['c'][$i]) ? $_POST['c'][$i] : null;
			$sonda['o'][] = array($_POST['o'][$i], (int)$_POST['g'][$i], $color);
		}
	}

	#Nowy ID
	if($id)
	{
		$sondy[$id] = array($sonda['n'],$sonda['p'],$sonda['g'],$sonda['d']);
	}
	else
	{
		end($sondy);
		$id = key($sondy) + 1;
		$sondy[$id] = array($sonda['n'],$sonda['p'],0,$_SERVER['REQUEST_TIME']);
	}

	require '../saver.php';
	try
	{
		$index = new Saver('../list/index.php');
		$index -> add('sondy', $sondy);
		$index -> save();
		$plik = new Saver('../list/'.$id.'.php');
		$plik -> add('sonda', $sonda);
		$plik -> save();

		#Ustaw jako aktualną
		if(isset($_POST['ustaw']) && $id != $cfg['id'])
		{
			$cfg['id'] = $id;
			$plik = new Saver('../cfg/config.php');
			$plik -> add('cfg', $cfg);
			$plik -> save();
		}

		#Czyść IP
		if(isset($_POST['del'])) @unlink('../list/IP'.$id.'.php');

		#Powrót do listy sond
		echo '<div class="OK">Sonda została zapisana.</div>';
		include 'mod/sondy.php';
		return;
	}
	catch(Exception $e)
	{
		echo '<div class="FAIL">Nie można zapisać sondy: '.$e->getMessage().'</div>';
	}
}
elseif(!$id || !$sonda)
{
	$sonda = array(
		'n' => '',
		'p' => '',
		'o' => array( array('',0,$color), array('',0,$color), array('',0,$color) ),
		'g' => 0,
		'd' => time(),
		't' => 1
	);
}
$i = 0;
?>
<form method="post">
<table>
<tr>
	<th>
		<?php echo $id ? 'Edytowanie sondy' : 'Ustawienia nowej sondy' ?>
	</th>
</tr>
<tr>
	<td style="vertical-align: top">
	<div style="width: 50%; float: left; text-align: right">

		<fieldset>
		<legend>Odpowiedzi</legend>
		Pytanie: <input type="text" name="p" value="<?php echo clean($sonda['p']) ?>" size="40" autofocus>
		<div id="o">

    <?php foreach($sonda['o'] as $o): ?>

		<div>Odpowiedź <?php echo ++$i ?>.
		<input name="o[]" value="<?php echo clean($o[0]) ?>" size="30">
		<input name="g[]" value="<?php echo (int)$o[1] ?>" type="hidden">
		<input name="c[]" value="<?php echo clean(@$o[2]) ?>" type="color" style="float:right;width:30px">
		</div>
		
		<?php endforeach ?>

		</div>
    <div style="text-align: center; margin: 16px">
			<a href="javascript:dodaj()"><b>Dodaj nową odpowiedź</b></a>
		</div>
    <div style="text-align: left; line-height: 18px">
			1. Aby usunąć odpowiedź, pozostaw puste pole.<br>
			2. Możesz zmienić kolor wykresu. Pierwszy jest kolorem domyślnym i zależy od ustawień globalnych.
		</div>
		</fieldset>

	</div>
	<div style="width: 50%; float: right; text-align: left">

	<fieldset>
		<legend>Pozwól zaznaczyć</legend>
		<label>
		<input type="radio" name="t" value="1"<?php if($sonda['t']!=2)echo' checked'?>> Tylko 1 odpowiedź</label><br>
		<label>
		<input type="radio" name="t" value="2"<?php if($sonda['t']==2)echo' checked'?>> Dowolną ilość odpowiedzi</label>
	</fieldset>
	<fieldset>
		<legend>Opcje dodatkowe</legend>
		<label>
		<input type="checkbox" name="ustaw"<?php if($cfg['id']==$id||!$id)echo' checked'?>>
		Ustaw sondę jako aktualną</label><br>
		<label>
		<input type="checkbox" name="del"> Wyczyść indeks adresów IP</label><br>
		<label>
		<input type="checkbox" name="reset" onclick="if(checked) return confirm('Czy na pewno chcesz wymazać wszystkie głosy w sondzie?')"> Wyzeruj wyniki w sondzie</label>
	</fieldset>

	<fieldset>
		<legend>Nazwa sondy</legend>
		<input name="n" value="<?php echo $sonda['n'] ?>" size="30">
	</fieldset>
	</div>
	</td>
</tr>
<tr>
	<th>
		<input type="hidden" name="w" value="<?php echo $sonda['g'] ?>">
		<input type="submit" value="Zapisz teraz" onclick="return check()">
		<input type="reset" value="Od nowa">
	</th>
</tr>
</table>
</form>
<script type="text/javascript">
function dodaj()
{
	var x = document.createElement('div');
	x.innerHTML = 'Odpowiedź '+(++I)+'. <input name="o[]" size="30"><input type="hidden" name="g[]"><input name="c[]" type="color" style="width:30px">';
	cl(x);
	G.appendChild(x);
	x.getElementsByTagName('input')[0].focus()
}
function check()
{
	if(F.p.value==0) { alert('Wpisz pytanie sondy!'); F.p.focus(); return false }
	var ile = 0, i = 0;
	var odp = G.getElementsByTagName('input');
	while(i<odp.length) { if(odp[i++].value!=0) ile++; }
	if(ile<1) { alert('Wpisz przynajmniej 1 odpowiedź!'); return false }
}
function cl(x)
{
	var t = x.getElementsByTagName('input');
	<?php if(isset($cfg['paleta'])) { ?>
	if(t[2].type == 'color') { t[2].value = t[2].defaultValue||D; return }
	<?php } ?>
	var o = document.createElement('select');
	o.tabIndex = -99;
	o.onchange = function(){ this.style.backgroundColor = this.value||D; this.blur() };
	o.name = 'c[]';
	o.style.width = '30px';
	for(var i in C)
	{
		var k = document.createElement('option');
		k.style.backgroundColor = C[i];
		k.value = C[i];
		o.appendChild(k)
	}
	if(t[2]) { o.value = t[2].defaultValue; x.removeChild(t[2]) }
	o.onchange();
	x.insertBefore(o,t[0].nextSibling)
}
F = document.forms[0];
G = document.getElementById('o');
A = G.getElementsByTagName('div');
I = A.length;
D = '<?php echo $color ?>';
C = [D,'#999900','#FFFF00','#FFCC00','#FF9900','#FF0000','#FF00FF','#CC00FF','#9900FF','#0000FF','#0099FF','#00FFFF','#00CC00','#00FF00','#CC6600','#999999','#000000'];
for(J=0; J<I; J++) cl(A[J])</script>