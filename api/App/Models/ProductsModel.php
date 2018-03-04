<?php
namespace App\Models;
use App\Models\DefaultModel;

class ProductsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_product_type = 2;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		//$query->select('vp.ddc_vendor_product_id, vp.vendor_id, vp.vendor_product_name, vp.vendor_product_alias, vp.product_description_small, vp.product_description, vp.product_weight, vp.product_weight_uom, vp.product_length, vp.product_width, vp.product_height, vp.product_params, vp.product_base_uom, vp.product_type, vp.distrib_cat_id, vp.category_id')
		$query->select('vp.ddc_vendor_product_id, vp.vendor_id, vp.vendor_product_name')  
			//->select('c.id, c.title as product_category')
  			//->select('i.image_link, i.linked_table, i.link_id, i.details as image_details')
  			//->select('pp.product_price, pp.product_id, pp.product_currency')
  			->from($this->db->quoteName('#__ddc_vendor_products', 'vp'))
  			//->leftJoin('#__ddc_images as i on (vp.ddc_vendor_product_id = i.link_id) AND (i.linked_table = "ddc_products")')
  			//->rightJoin('#__ddc_product_prices as pp on (vp.ddc_vendor_product_id = pp.product_id)')
  			//->rightJoin('#__categories as c on vp.category_id = c.id')
  			->group('vp.ddc_vendor_product_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $val)
	{
		if($val > 0)
		{
			$query->where('vp.ddc_vendor_product_id = '.$val);
		}
		elseif(is_string($val))
		{
			$query->where('vp.vendor_product_alias = "'.$val.'"');
		}
		if($this->_published!=null)
		{
			$query->where('vp.published = "'.(int)$this->_published.'"');
		}
		$query->where('vp.product_type <= "'.$this->input->get('product_type',$this->_product_type).'"');
		return $query;
	}	
}