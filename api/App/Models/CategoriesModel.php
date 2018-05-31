<?php
namespace App\Models;
use App\Models\DefaultModel;

class CategoriesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
		$query->select('c.id,c.title,c.alias, c.description')
			->select('i.image_link')
			->select('ci.image_link as cat_image')
  			->from($this->db->quoteName('#__ddc_categories', 'c'))
			->leftJoin('#__ddc_categories as pc on pc.id = c.parent_category')
			->leftJoin('#__ddc_products as p on p.cat_id = c.id')
			->leftJoin('#__ddc_images as ci on c.id = ci.link_id AND ci.linked_table = "category_table"')
			->leftJoin('#__ddc_images as i on p.product_sku = i.product_sku')
  			->group('c.id');
		return $query;
	}	
	protected function _buildWhere(&$query, $val)
	{
		if((int)$val > 0){
			$query->where('c.parent_category = "'.$val.'"');
		}else{
			$query->where('pc.alias = "'.$val.'"');
		}
		
		$query->where('c.state = "1"');
		
		
		
		return $query;
	}	
}