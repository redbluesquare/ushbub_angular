<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\CategoriesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class CategoriesController extends DefaultController
{
	
	public function index()
	{
		$category = $this->getInput()->getString('task',null);
		$model = new CategoriesModel($this->getInput(), $this->getContainer()->get('db'));
		if($category != null)
		{
			$items = $model->listItems($category);
		}
		else 
		{
			$items = $model->listItems();
		}
		return $items;
	}
	
	public function edit()
	{
		$val = $this->getInput()->getString('id');
		$item = null;
		if($val!=null)
		{
			$model = new CategoriesModel($this->getInput(), $this->getContainer()->get('db'));
			if($val > 0)
			{
				$item = $model->getItemById($val);
			}
			elseif(is_string($val))
			{
				$item = $model->getItemByAlias($val);
			}
		}
		
		return $item;
	}

}