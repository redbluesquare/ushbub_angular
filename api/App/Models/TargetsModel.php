<?php
namespace App\Models;
use App\Models\DefaultModel;

class TargetsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('t.*')
			->from('#__ddc_targets as t')
		  	->rightJoin('#__ddc_accounts as a on a.ddc_account_id = t.account_id')
		  	->group('t.ddc_target_id,a.ddc_account_id')
		  	->order('t.target_date DESC');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $user_id, $at,$an)
	{
		if((int)$id > 0)
		{
			$query->where('t.ddc_target_id = '.(int)$id);
		}
		if((int)$user_id > 0)
		{
			$query->where('a.user_id = '.$user_id);
		}
		if((int)$at > 0)
		{
			$query->where('a.ddc_account_id = '.$at);
		}
		return $query;
	}	
}