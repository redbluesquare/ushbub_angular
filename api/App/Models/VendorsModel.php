<?php
namespace App\Models;
use App\Models\DefaultModel;

class VendorsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	protected $_vendor_id	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('v.*')
  			->from($this->db->quoteName('#__ddc_vendors', 'v'))
  			->group('v.ddc_vendor_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $val, $key)
	{

		if($this->input->get('vendor_id',null,'string')!=null)
		{
			$query->where('(v.ddc_vendor_id = "'.$this->input->get('vendor_id', null,'string').'")');
		}
		
		return $query;
	}
}