<?php
namespace App\Models;
use App\Models\DefaultModel;

class CategoriesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('p.*')
  			->select('v.*')
  			->select('i.*')
  			->select('pp.*')
  			->from($this->db->quoteName('#__ddc_products', 'p'))
  			->rightJoin('#__ddc_vendors as v on (p.vendor_id = v.ddc_vendor_id)')
  			->leftJoin('#__ddc_images as i on (p.ddc_product_id = i.link_id) AND (i.linked_table = "ddc_products")')
  			->rightJoin('#__ddc_product_prices as pp on (p.ddc_product_id = pp.product_id)')
  			->group('p.ddc_product_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $val)
	{
		if($val > 0)
		{
			$query->where('p.ddc_product_id = '.$val);
		}
		elseif(is_string($val))
		{
			$query->where('p.product_alias = "'.$val.'"');
		}
		if($this->input->get('postcode', null)!=null)
		{
			$query->where('v.post_code LIKE "%'.$this->input->get('postcode', null).'%"');
		}
		$query->where('v.state = "1"');
		
		
		
		return $query;
	}	
}