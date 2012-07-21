<?php
class Saver
{
	private $in = '';
	public $file;

	function __construct($file)
	{
		$this->file = (strpos($file,'/')) ? $file : './cfg/'.$file.'.php';
	}
	
	#Dodaj zmienną
	function add($var,$val)
	{
		if(is_array($val))
		{
			$this->in .= '$'.$var.'='.var_export($val,1).';';
		}
		else
		{
			$this->in.='$'.$var.'='.((is_numeric($val))?$val:'\''.$this->escape($val).'\'').';';
		}
	}

	#Dodaj stałą
	function addConst($n,$v)
	{
		$this->in.='define(\''.$n.'\','.((is_numeric($v))?$v:'\''.$this->escape($v).'\'').');';
	}

	#Zapisz
	function save(&$data=null, $var='cfg')
	{
		if($data) $this->add($var, $data);
		if(file_put_contents($this->file, '<?php '.$this->in, 2))
		{
			return true;
		}
		else
		{
			throw new Exception('Brak uprawnień zapisu do '.$this->file);
			return false;
		}
	}
	
	#Add slashes
	private function escape($x)
	{
		return str_replace( array('\\','\''), array('\\\\','\\\''), $x);
	}
}