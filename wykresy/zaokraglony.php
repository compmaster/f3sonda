<?php
function sonda_wykres(array $odp, $ile, &$cfg)
{
	if(isset($cfg['max']))
	{
		$max = 0;
		foreach($odp as $x) if($max < $x[1]) $max = $x[1];
	}
	else
	{
		$max = $ile;
	}
	$out = '<table style="width: 100%; border-spacing: 1px 2px">';
	foreach($odp as $x)
	{
		$out .= '<tr><td>'.$x[0].' <b>'.round($x[1]/$ile*100, $cfg['cyfr']).'%</b></td><td style="width: 20px; text-align: right"><b>'.$x[1].'</b></td></tr>';
		$out .= '<tr><td colspan="2"><div style="width: '.max(2,ceil($x[1]/$max*100)).'%; background: '.(empty($x[2])?$cfg['color']:$x[2]).'; font-size: 10px; margin-bottom: 4px; border-radius: 8px">&nbsp;</div></td></tr>';
	}
	return $out.'</table>';
}