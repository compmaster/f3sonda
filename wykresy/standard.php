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
	$out = '';
	foreach($odp as $x)
	{
		$out .= '<div>'.$x[0].' <b>'.round($x[1]/$ile*100, $cfg['cyfr']).'%</b></div>';
		$out .= '<div style="margin: 3px 0 6px 0" title="'.$x[1].'"><div style="width: '.max(1,ceil($x[1]/$max*100)).'%; background-color: '.(empty($x[2])?$cfg['color']:$x[2]).'; font-size: 10px">&nbsp;</div></div>';
	}
	return $out;
}