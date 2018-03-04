<?php
namespace App\Models;
use App\Models\DefaultModel;

class ShoppingcartdetailsModel extends DefaultModel
{
	protected $_published 	= null;
	protected $_scd_id		= null;
	protected $_location	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('scd.*')
  			->select('vp.vendor_product_name, vp.ddc_vendor_product_id, vp.product_weight, vp.product_weight_uom, vp.product_params, vp.distrib_cat_id,vp.vendor_id')
  			->select('c.title as product_category')
  			->select('i.image_link, i.linked_table, i.link_id, i.details as image_details')
  			->from($this->db->quoteName('#__ddc_shoppingcart_details', 'scd'))
  			->rightJoin('#__ddc_vendor_products as vp on vp.ddc_vendor_product_id = scd.product_id')
  			->rightJoin('#__categories as c on c.id = vp.category_id')
  			->leftJoin('#__ddc_shoppingcart_headers as sch on sch.ddc_shoppingcart_header_id = scd.shoppingcart_header_id')
  			->leftJoin('#__ddc_images as i on (vp.ddc_vendor_product_id = i.link_id) AND (i.linked_table = "ddc_products")')
  			->group('scd.ddc_shoppingcart_detail_id')
  			->where('sch.session_id = "'.$this->input->get('usertoken',null,'string').'" AND scd.state = 1');
		return $query;
	}	
	protected function _buildWhere(&$query, $pc = null, $id = null ,$product_id = null)
	{
		if($id != null)
		{
			$query->where('scd.shoppingcart_header_id = '.(int)$id);
		}
		if($product_id != null)
		{
			$query->where('scd.product_id = '.(int)$product_id);
		}
		if($this->input->get('state',null)!=null)
		{
			$query->where('sch.state = "'.(int)$this->input->get('state',null).'"');
		}
		else
		{
			$query->where('(sch.state < "3") AND (sch.state > -1)');
		}
		return $query;
	}	
}