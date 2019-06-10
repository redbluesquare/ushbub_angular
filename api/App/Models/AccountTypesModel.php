<?php
namespace App\Models;
use App\Models\DefaultModel;

class AccountTypesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('at.*')
			->from($this->db->quoteName('#__ddc_account_types', 'at'))
			->group('at.ddc_account_type_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $user_id)
	{
		if((int)$id > 0)
		{
			$query->where('at.ddc_account_type_id = '.(int)$id);
		}
		if((int)$user_id > 0)
		{
			$query->where('at.user_id = '.$user_id);
		}
		return $query;
	}	
}