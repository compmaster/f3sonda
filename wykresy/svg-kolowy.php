<?php
function sonda_wykres(array $odp, $ile, &$cfg)
{
	$suma = 0;
	foreach($odp as $x)
	{
		$suma += $x[1];
	}
	$pol = $suma / 2;
	$out = '<div style="text-align:center;margin:15px 0"><svg version="1.1" viewBox="0 0 100 100" style="display:inline-block;width:50%;margin:0 8px;min-width:200px">';
	$leg = '<table style="display:inline-block;vertical-align:top;border-spacing:5px 4px">';
	$px = 50;
	$py = 0;
	$a = 0;
	foreach($odp as $o)
	{
		if($o[1] === 0) continue;
		if($o[1] === $suma)
		{
			$x = 49.9;	// 100% fix
			$y = 0;
		}
		else
		{
			$a += $o[1] * M_PI / $pol;
			$x = 50 + sin($a) * 50;
			$y = 50 - cos($a) * 50;
		}
		$proc = round($o[1] / $ile * 100, $cfg['cyfr']);
		$duzy = $o[1] > $pol ? '1' : '0';
		$out .= '<path stroke="black" stroke-width="0.1" d="M 50 50 L '.$px.' '.$py.' A 50 50 0 '.$duzy.' 1 '.$x.' '.$y.' Z" fill="'.$o[2].'"><title>'.$o[0].' ('.$proc.'%)</title></path>';
		$leg .= '<tr><td style="background:'.$o[2].';padding:0 7px"></td><td style="text-align:left;padding:0">'.$o[0].'</td><td style="text-align:right;padding:0">'.$proc.'%</td></tr>';
		$px = $x;
		$py = $y;
	}
	return $out.'</svg>'.$leg.'</table></div>';
}