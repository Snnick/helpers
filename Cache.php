<?php
class Cache
{
	private $exist = false;
	private $data = [];
	private $file_path = '';

	function __construct($filename, $dir = 'cache')
    {
		$this->file_path = $dir.'/'.$filename.'.php';
		$this->load();
	}

	private function load()
    {
		if (file_exists($this->file_path))
		{
			$this->data = include($this->file_path);
			if ( $this->get('time') == null || (date('U') - $this->get('time')) > 3600 )
			{
				$this->exist = false;
			}
			else
			{
				$this->exist = true;
			}
		}
		else
		{
			$this->set('time', date('U'));
		}
	}

	private function save()
    {
		$code = '<?php return '. var_export($this->data, true) . ';';
		file_put_contents($this->file_path, $code);
	}

	public function set($name, $value)
    {
		if($name || $value)
		{
			$this->data[$name] = $value;
			$this->save();
		}
	}

	public function get($name){
		if(isset($this->data[$name]))
		{
			return $this->data[$name];
		}
		else
		{
			return null;
		}
	}

	public function remove($name)
    {
		if(isset($name))
		{
			unset($this->data[$name]);
			$this->save();
		}
	}

	public function exist()
    {
		return $this->exist;
	}

	public function delete()
    {
		unlink($this->file_path);
	}
}

$menu = new Cache('menu');

if (!$menu->exist())
{
	// load from db
	$menu->set('1', ['Item 1', 'Item 2', 'Item 3']);
	$menu->set('2', 'Категория 2');
}

$arr = $menu->get('1');
$cat2 = $menu->get('2');
//$menu->delete();

var_dump($arr, $cat2);