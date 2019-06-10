<?php
namespace App\Models;
use App\Models\DefaultModel;

class VendorproductsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_product_type = 2;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('vp.ddc_vendor_product_id, vp.vendor_id, vp.vendor_product_name, vp.vendor_product_alias, vp.product_description_small, vp.product_description, vp.product_weight, vp.product_weight_uom, vp.product_length, vp.product_width, vp.product_height, vp.product_params, vp.product_base_uom, vp.product_type, vp.distrib_cat_id, vp.category_id')
			->select('c.id, c.title as product_category')
			->select('pp.product_id, pp.product_price, pp.product_currency')
  			->select('i.image_link, i.details as image_details')
  			->from($this->db->quoteName('#__ddc_vendor_products', 'vp'))
			->leftJoin('#__ddc_images as i on (vp.ddc_vendor_product_id = i.link_id AND linked_table = "vendor_products")')
			->leftJoin('#__ddc_vendors as v on v.ddc_vendor_id = vp.vendor_id')
			->leftJoin('#__ddc_product_prices as pp on pp.product_id = vp.ddc_vendor_product_id')
			->leftJoin('#__ddc_categories as c on c.id = vp.category_id')
  			->group('vp.ddc_vendor_product_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id=null, $vendor=null, $cat=null)
	{
		if((int)$id > 0)
		{
			$query->where('vp.ddc_vendor_product_id = "'.$id.'"');
		}
		elseif($id != null){
			$query->where('vp.vendor_product_alias = "'.$id.'"');
		}
		if((int)$vendor > 0)
		{
			$query->where('vp.vendor_id = "'.$vendor.'"');
		}
		elseif($vendor != null){
			$query->where('v.alias = "'.$vendor.'"');
		}
		if((int)$cat > 0)
		{
			$query->where('vp.category_id = "'.$cat.'"');
		}
		return $query;
	}

	public function product_connectors($category = null)
	{
		$query = $this->db->getQuery(true);
		$query->select('p.product_params')
		->from($this->db->quoteName('#__ddc_products', 'p'))
		->leftJoin('#__ddc_categories as c on c.id = p.cat_id')
		->group('p.product_params')
		->where('p.product_params != ""');
		if($this->input->get('product_type', null,'string') != null)
		{
			$query->where('p.product_type = '.$this->input->get('product_type', null,'string'));
		}
		if($category != null)
		{
			$query->where('c.alias = "'.$category.'"');
		}
		$result = $this->db->setQuery($query, $this->limitstart, $this->limit)->loadObjectList();
		return $result;
	}
}