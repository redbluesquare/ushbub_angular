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
			$items = array("product"=> $model->listItems($pc));
		}
		else 
		{
			$items = array( "product" => $model->listItems());
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
			}
			elseif(is_string($val))
			{
				$item = $model->getItemByAlias($val);
				$item->product_price = $model->getProductPrice($item->ddc_vendor_product_id);
			}
		}
		
		return $item;
	}
}