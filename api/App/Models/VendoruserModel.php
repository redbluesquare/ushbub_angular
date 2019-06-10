<?php
namespace App\Models;
use App\Models\DefaultModel;

class VendoruserModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_vendor_id	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('v.ddc_vendor_id')
		 	->from($this->db->quoteName('#__ddc_vendors', 'v'))
			->leftJoin('#__ddc_user_vendor as uv on uv.vendor_id = v.ddc_vendor_id')
  			->group('v.ddc_vendor_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $user)
	{
		if($id!=null)
		{
			$query->where('(v.ddc_vendor_id = "'.$id.'")');
		}
		if($user!=null)
		{
			$query->where('(uv.user_id = "'.$user.'")');
		}	
		return $query;
	}
}