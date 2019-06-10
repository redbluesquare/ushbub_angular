<?php
namespace App\Models;
use App\Models\DefaultModel;

class BalancesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('b.*')
			->from('#__ddc_balances as b')
		  	->rightJoin('#__ddc_accounts as a on a.ddc_account_id = b.account_id')
		  	->leftjoin('#__ddc_targets as t on t.account_id = a.ddc_account_id')
		  	->group('b.ddc_balance_id,a.ddc_account_id')
		  	->order('b.record_date DESC');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $user_id, $at,$an)
	{
		if((int)$id > 0)
		{
			$query->where('b.ddc_balance_id = '.(int)$id);
		}
		if((int)$user_id > 0)
		{
			$query->where('a.user_id = '.$user_id);
		}
		if((int)$at > 0)
		{
			$query->where('a.ddc_account_id = '.$at);
		}
		if((int)$an > 0)
		{
			$query->where('a.account_nature = '.$an);
		}
		return $query;
	}	
}