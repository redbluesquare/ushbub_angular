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
			for($i=0;$i<count($items);$i++)
			{
				if($items[$i]->image_link==null)
				{
					$items[$i]->image_link = 'images/ddcshopbox/picna_ushbub.png';
				}
				if($items[$i]->cat_image==null)
				{
					$items[$i]->cat_image = 'images/ddcshopbox/picna_ushbub.png';
				}
			}
		}
		else 
		{
			$items = $model->listItems();
			for($i=0;$i<count($items);$i++)
			{
				if($items[$i]->image_link==null)
				{
					$items[$i]->image_link = 'images/ddcshopbox/picna_ushbub.png';
				}
				if($items[$i]->cat_image==null)
				{
					$items[$i]->cat_image = 'images/ddcshopbox/picna_ushbub.png';
				}
			}
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