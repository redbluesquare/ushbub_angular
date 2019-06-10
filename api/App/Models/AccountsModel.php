<?php
namespace App\Models;
use App\Models\DefaultModel;

class AccountsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('a.*')
			->select('at.account_type as accounttype')
			->select('max(b.record_date) as record_date')
			->select('c.currency_name, c.currency_code_3, c.currency_symbol')
			->select('(SELECT bb.balance FROM #__ddc_balances as bb WHERE bb.record_date = max(b.record_date) AND (bb.state = 1) AND bb.account_id = a.ddc_account_id GROUP BY bb.account_id) as balance')
			->from($this->db->quoteName('#__ddc_accounts', 'a'))
			->rightjoin('#__ddc_account_types as at on a.account_type = at.ddc_account_type_id')
			->leftjoin('#__ddc_currencies as c on a.currency_id = c.ddc_currency_id')
			->leftJoin('#__ddc_balances as b on b.account_id = a.ddc_account_id')
			->group('a.ddc_account_id')
			->order('a.account_type ASC, a.account_name ASC');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $user_id, $at_id)
	{
		if((int)$id > 0)
		{
			$query->where('a.ddc_account_id = '.(int)$id);
		}
		if((int)$user_id > 0)
		{
			$query->where('a.user_id = '.$user_id);
		}
		if((int)$at_id > 0)
		{
			$query->where('a.account_type = '.$at_id);
		}
		if($this->input->get('account_nature', 0) > 0 && $this->input->get('account_nature', 0) < 5){
			$query->where('at.account_nature = '.$this->input->get('account_nature', 0));
		}
		if($this->input->get('account_nature', 0)== 6){
			$query->where('at.account_nature <= 2');
		}
		return $query;
	}	
}