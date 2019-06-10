<?php
namespace App\Models;
use App\Models\DefaultModel;

class ProductpricesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_product_type = 2;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('p.*')  
			->from($this->db->quoteName('#__ddc_product_prices', 'p'))
  			->group('p.ddc_product_price_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $product)
	{
		if((int)$id > 0)
		{
			$query->where('p.ddc_product_price_id = "'.$id.'"');
		}
		if((int)$product > 0)
		{
			$query->where('p.product_id = "'.$product.'"');
		}
		return $query;
	}
}