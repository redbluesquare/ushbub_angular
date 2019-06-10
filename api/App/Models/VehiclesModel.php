<?php
namespace App\Models;
use App\Models\DefaultModel;

class VehiclesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_vendor_id	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('uv.*')
  			->from($this->db->quoteName('#__ddc_user_vehicles', 'uv'))
  			->group('uv.id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $user_id)
	{
		if($id!=null)
		{
			$query->where('(uv.id = "'.$id.'")');
		}
		if($user_id!=null)
		{
			$query->where('(uv.user_id = "'.$user_id.'")');
		}
		return $query;
	}

	public function getProductTypes($id = null, $vc_id= null, $pc = null){
		//Get the competition users
		$query = $this->db->getQuery(true);
		$query->select('v.ddc_vendor_id, v.title, v.alias, v.post_code')
			->select('c.id, c.title as product_category')
			->select('i.image_link, i.details')
			->from($this->db->quoteName('#__ddc_vendors', 'v'))
			->leftJoin('#__ddc_vendor_products as vp on vp.vendor_id = v.ddc_vendor_id')
			->leftJoin('#__ddc_categories as c on c.id = vp.category_id')
			->leftJoin('#__ddc_images as i on (i.link_id = c.id) AND (linked_table = "category_table")')
			->group('v.ddc_vendor_id, vp.category_id');
		if((int)$id > 0){
			$query->where('v.ddc_vendor_id = "'.(int)$id.'"');
		}
		if((int)$vc_id > 0){
			$query->where('vp.category_id = "'.(int)$vc_id.'"');
		}
		if($pc != null){
			$query->where('v.post_code LIKE "'.$pc.'%"');
		}
		$this->db->setQuery($query);
		$response = $this->db->loadObjectList();

		return $response;
	}
}