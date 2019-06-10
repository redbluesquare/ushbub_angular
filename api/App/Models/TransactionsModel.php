<?php
namespace App\Models;
use App\Models\DefaultModel;

class TransactionsModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('t.*')
			->select('at.account_name as account_to_name')
			->select('af.account_name as account_from_name')
			->from($this->db->quoteName('#__ddc_transactions', 't'))
			->rightjoin('#__ddc_accounts as at on at.ddc_account_id = t.account_to')
			->rightjoin('#__ddc_accounts as af on af.ddc_account_id = t.account_from')
			->group('t.ddc_transaction_id')
			->order('t.record_date DESC');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $user_id, $at_id)
	{
		if((int)$id > 0)
		{
			$query->where('t.ddc_transaction_id = '.(int)$id);
		}if((int)$user_id > 0)
		{
			$query->where('t.user_id = '.(int)$this->input->get('user_id', 0));
		}
		return $query;
	}

	public function isValidAccount($account_id){
		$query = $this->db->getQuery(true);
		$query->select('a.*')
		->from($this->db->quoteName('#__ddc_accounts', 'a'))
		->where('a.ddc_account_id = '.$account_id.' AND a.user_id = '.$this->input->get('user_id', 0));
		$this->db->setQuery($query);
		$result = false;
		if($this->db->setQuery($query)->loadObject()!=null){
			$result = true;
		}
		return $result;
	}

	public function transationSummary($account_to=0,$from_date='',$to_date='',$transaction_type=6,$state=1){
		$query = $this->db->getQuery(true);
		$query->select('a_to.ddc_account_id as account_to')
			->select('(SELECT sum(tt.transaction_value) FROM #__ddc_transactions as tt WHERE (tt.record_date BETWEEN "'.$from_date.'" AND "'.$to_date.'") AND (tt.state = 1) AND tt.account_to = a_to.ddc_account_id GROUP BY tt.account_to) as transaction_total')
			->select('(SELECT sum(ta.target_balance) FROM #__ddc_targets as ta WHERE (ta.target_date BETWEEN "'.$from_date.'" AND "'.$to_date.'") AND (ta.state = 1) AND ta.account_id = t.account_to GROUP BY ta.account_id) as target_value')
			->select('a_to.account_name as account_to_name')
			->from($this->db->quoteName('#__ddc_accounts', 'a_to'))
			->leftjoin('#__ddc_transactions as t on t.account_to = a_to.ddc_account_id AND t.state = '.$state)
			->group('a_to.ddc_account_id')
			->order('t.record_date ASC')
			->where('a_to.user_id = '.$this->input->get('user_id', 0).' AND a_to.account_type = '.$transaction_type);
		if($account_to>0){
			$query->where('a.ddc_account_id = '.$account_to);
		}
		$this->db->setQuery($query);
		$result = $this->db->setQuery($query)->loadObjectList();
		return $result;
	}
}