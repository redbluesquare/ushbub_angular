<?php
namespace App\Models;
use App\Models\DefaultModel;

class VendorlocationsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_vendor_id	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('vl.*')
  			->from($this->db->quoteName('#__ddc_vendor_locations', 'vl'))
  			->group('vl.id');
		return $query;
	}	
	protected function _buildWhere(&$query, $val, $key)
	{

		if((int)$val > 0)
		{
			$query->where('(vl.id = "'.(int)$val.'")');
		}
		
		return $query;
	}
}