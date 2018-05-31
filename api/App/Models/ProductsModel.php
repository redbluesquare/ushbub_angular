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
		$query->select('p.product_sku')  
			->select('c.id, c.title as product_category')
			->select('lp.ean,lp.product_type,lp.product_designation')
  			->select('i.image_link, i.details as image_details')
  			->from($this->db->quoteName('#__ddc_products', 'p'))
			->leftJoin('#__ddc_launch_products as lp on (p.product_sku = lp.part_number)')
			->leftJoin('#__ddc_images as i on (p.product_sku = i.product_sku)')
			->leftJoin('#__ddc_categories as c on c.id = p.cat_id')
  			->group('p.product_sku');
		return $query;
	}	
	protected function _buildWhere(&$query, $val)
	{
		if((int)$val > 0)
		{
			$query->where('p.cat_id = "'.$val.'"');
		}

		if($this->_published!=null)
		{
			//$query->where('p.published = "'.(int)$this->_published.'"');
		}
		//$query->where('p.product_type <= "'.$this->input->get('product_type',$this->_product_type).'"');
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