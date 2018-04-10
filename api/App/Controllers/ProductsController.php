<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProductsModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class ProductsController extends DefaultController
{
	
	public function index()
	{
		$pc = $this->getInput()->getString('pc',null);
		$model = new ProductsModel($this->getInput(), $this->getContainer()->get('db'));
		if($pc != null)
		{
			$items = $model->listItems($pc);
			for($i=0;$i<count($items);$i++)
			{
				$items[$i]->product_price = $model->getProductPrice($items[$i]->ddc_vendor_product_id);
				$items[$i]->product_params = json_decode($items[$i]->product_params);
				if($items[$i]->image_link==null)
				{
					$items[$i]->image_link = 'images/ddcshopbox/picna_ushbub.png';
				}
			}
		}
		else 
		{
			$items = $model->listItems();
			for($i=0;$i<count($items);$i++)
			{
				//$items[$i]->product_price = $model->getProductPrice($items[$i]->ddc_vendor_product_id);
				//$items[$i]->product_params = json_decode($items[$i]->product_params);
				if($items[$i]->image_link==null)
				{
					$items[$i]->image_link = 'images/ddcshopbox/picna_ushbub.png';
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
			$model = new ProductsModel($this->getInput(), $this->getContainer()->get('db'));
			if($val > 0)
			{
				$item = $model->getItemById($val);
				$item->product_price = $model->getProductPrice($item->ddc_vendor_product_id);
				$item->product_params = json_decode($item->product_params);
				if($item->image_link==null)
				{
					$item->image_link = 'images/ddcshopbox/picna_ushbub.png';
				}
			}
			elseif(is_string($val))
			{
				$item = $model->getItemByAlias($val);
				$item->product_price = $model->getProductPrice($item->ddc_vendor_product_id);
				$item->product_params = json_decode($item->product_params);
				if($item->image_link==null)
				{
					$item->image_link = 'images/ddcshopbox/picna_ushbub.png';
				}
			}
		}
		
		return $item;
	}
}