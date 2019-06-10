<?php
namespace App\Models;
use App\Models\DefaultModel;

class CurrenciesModel extends DefaultModel
{
	protected $_published 	= 1;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('c.*')
			->from($this->db->quoteName('#__ddc_currencies', 'c'))
			->group('c.ddc_currency_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id, $user_id, $at_id)
	{
		if((int)$id > 0)
		{
			$query->where('c.ddc_currency_id = '.(int)$id);
		}
		return $query;
	}	
}