<?php
namespace App\Models;
use App\Models\DefaultModel;

class ShoppingcartheadersModel extends DefaultModel
{
	protected $_published 	= null;
	protected $_location	= null;
	

	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('sch.ddc_shoppingcart_header_id, sch.user_id, sch.session_id, sch.payment_method, sch.shipping_cost')
  			->select('sch.delivery_type, sch.delivery_time, sch.delivery_date, sch.first_name, sch.last_name, sch.email_to')
  			->select('sch.address_line_1, sch.address_line_2, sch.address_line_3, sch.town, sch.county, sch.post_code, sch.mobile_no')
  			->select('sch.telephone_no, sch.country, sch.coupon_id, sch.coupon_amount, sch.comment, sch.created_on, sch.state')
  			->select('u.email')
  			->from($this->db->quoteName('#__ddc_shoppingcart_headers', 'sch'))
  			->leftJoin('#__users as u on u.id = sch.user_id')
  			->group('sch.ddc_shoppingcart_header_id')
  			->where('sch.session_id = "'.$this->input->get('usertoken',null,'string').'"');
		return $query;
	}	
	protected function _buildWhere(&$query, $pc = null, $id = null)
	{
		if($id != null)
		{
			$query->where('sch.ddc_shoppingcart_header_id = '.(int)$id);
		}
		if($this->input->get('email',null,'string')!=null)
		{
			$query->where('u.email = "'.$this->input->get('email',null,'string').'"');
		}
		if($this->input->get('userid',null)!=null)
		{
			$query->where('sch.user_id = "'.(int)$this->input->get('userid',null).'"');
		}
		if($this->input->get('state',null)!=null)
		{
			$query->where('sch.state = "'.(int)$this->input->get('state',null).'"');
		}
		else 
		{
			$query->where('(sch.state < "3") AND (sch.state > -1)');
		}
		return $query;
	}
	
	public function shopcart_items($id = null)
	{
		$modelSch = new ShoppingcartheadersModel();
		$this->shopcart = $modelSch->getItem($id);
		$cart_id = (string)$this->shopcart->ddc_shoppingcart_header_id;
		$token = (string)$this->shopcart->token;
		$first_name = (string)$this->shopcart->first_name;
		$last_name = (string)$this->shopcart->last_name;
		$email_to = (string)$this->shopcart->email_to;
		$telephone_no = (string)$this->shopcart->telephone_no;
		$mobile_no = (string)$this->shopcart->mobile_no;
		$address_1 = (string)$this->shopcart->del_address1;
		$address_2 = (string)$this->shopcart->del_address2;
		$address_3 = (string)$this->shopcart->del_address3;
		$town = (string)$this->shopcart->del_town;
		$county = (string)$this->shopcart->del_county;
		$post_code = (string)$this->shopcart->del_post_code;
		if($this->shopcart->delivery_type==1){$del_type = "Standard";}
		$del_cost = number_format($this->shopcart->shipping_cost,2);
		$del_date = (string) date('d M Y', $this->shopcart->delivery_date);
		$discount = number_format($this->shopcart->coupon_value,2);
		$comments = (string)$this->shopcart->comments;
		$todaysdate =  (string)date('Y-m-d');
		$year =  (string)date('Y');
		$emailheader = null;
		$terms = null;
		$message_body = null;
		$message_foot = null;
		$sitetitle = JFactory::getApplication()->getCfg('sitename');
			
		$emailheader = "USHBUB shopping cart confirmation";
		$terms = (string)"Currently, we offer a single delivery service. Weekdays after 17:00 and every Saturday between 09:00am and 16:00pm.";
		$message_header = <<<EOT
  		<html><body>
  		<div style="width:800px; box-shadow:#ccc 0px 0px 5px;">
  			<div style="background:#410996;display:block;color:#cfcfcf;padding:2px 10px 1px 20px;"><h1 style="padding:10px;">$sitetitle</h1></div>
			<div style="background:#ffffff;display:block;padding:10px;"><h2>$emailheader</h2><hr />
			<table><tbody>
			<tr><td style="width:200px;padding-top:5px;">Shopping Cart #: </td><td style="padding-top:5px;">$cart_id</td></tr>
			<tr><td style="width:200px;padding-top:5px;">Full Name: </td><td style="padding-top:5px;">$first_name $last_name</td></tr>
  			<tr><td style="width:200px;padding-top:5px;">Address: </td><td style="padding-top:5px;">$address_1</td></tr>
  			<tr><td></td><td>$address_2</td></tr>
  			<tr><td></td><td>$town</td></tr>
  			<tr><td></td><td>$county</td></tr>
  			<tr><td></td><td>$post_code</td></tr>
  			<tr><td style="width:200px;padding-top:5px;">Delivery: </td><td style="padding-top:5px;">$del_type, $del_date</td></tr>
  			<tr><td style="width:200px;padding-top:5px;">Comments: </td><td style="padding-top:5px;">$comments</td></tr>
  			</tbody>
  			</table style="width:100%;">
  			<table><tbody>
  			<tr><td colspan="3">We are very excited about our mission to help local businesses and communities and really thank you for placing an order with us. We are now validating the order with the shop(s) and once we have confirmation, we will notify you of confirmed delivery dates and when payment will be taken.<br>
  					<i>Please note that we do our very best to provide up to date prices, however, certain shop prices are based on weight which may vary. Should you have any questions on your purchase, please get in touch.</i>
  				</td>
  			</tr>
  			</tbody></table>
EOT;
		$scdItems = new ShoppingcartdetailsModel();
		$items = $scdItems->listItems();
		$message_body = '<table><tbody>';
		$totalprice = 0;
		$message_body .="<tr><td><b>Product</b></td><td><b>Details</b></td><td></td><td style=\"text-align:center;\"><b>Quantity</b></td><td style=\"text-align:center;\"><b>Price</b></td></tr>";
		foreach($items as $i => $item):
		$vendor_product_name = (string)$item->vendor_product_name;
		$product_quantity = (string)$item->product_quantity;
		$product_price = number_format($item->price,2);
		$product_box = (string)trim($this->getpartjsonfield($item->product_params, 'product_box'));
		$product_weight = (string)$this->ddcnumber($item->product_weight);
		$product_weight_uom = (string)$item->product_weight_uom;
		$image_link = (string)JRoute::_($item->image_link);
		$shop_name = (string)$item->title;
		$shop_postcode = (string)strtoupper($item->shop_postcode);
		$totalprice = number_format($totalprice+($item->product_quantity*$item->price),2);
		$message_body .='<tr class="row'.$i.'" style="border-top:1px solid #cfcfcf;padding:5px;">';
		$message_body .='<td style="width:350px;"><img src="'.JUri::base().$image_link.'" style="float:left;max-height:48px;width:64px;object-fit:contain;padding:3px 5px 5px 2px"><b>'.$vendor_product_name.'</b><br>'.$shop_name.', '.$shop_postcode.'</td>';
		$message_body .='<td style="width:100px;">';
		$message_body .='<i style="margin-top:3px;font-size:0.9em;">Pack quantity: '.$product_box.'<br>';
		$message_body .='Weight: '.$product_weight.' '.$product_weight_uom.'</i>';
		$message_body .='</td>';
		$message_body .='<td style="width:100px;"></td>';
		$message_body .='<td style="text-align:center;width:100px;">'.$product_quantity.'</td>';
		$message_body .='<td style="text-align:right;width:150px;">&pound '.$product_price.'</td>';
		$message_body .='</tr>';
		endforeach;
		if(($totalprice + $del_cost)<$discount)
		{
			$discount = number_format($totalprice + $del_cost,2);
		}
		$totalprice2 = number_format(($totalprice + $del_cost - $discount),2);
		$message_foot .= <<<EOT
			<tfoot>
				<tr style="border-top:1px solid #cfcfcf;padding:5px;">
					<td style="padding-top:5px;"></td>
					<td style="padding-top:5px;"><b>Sub Total</b></td>
					<td style="padding-top:5px;"></td>
		            <td style="padding-top:5px;"></td>
		            <td style="padding-top:5px;text-align:right;"><b>&pound; $totalprice</b></td>
				</tr>
				<tr>
					<td style="padding-top:5px;"></td>
					<td style="padding-top:5px;"><b>Shipping Cost</b></td>
					<td style="padding-top:5px;"></td>
		            <td style="padding-top:5px;"></td>
		            <td style="padding-top:5px;text-align:right;">&pound; $del_cost</td>
				</tr>
				<tr>
					<td style="padding-top:5px;"></td>
					<td style="padding-top:5px;"><b>Discount</b></td>
					<td style="padding-top:5px;"></td>
		            <td style="padding-top:5px;"></td>
		            <td style="padding-top:5px;text-align:right;">- &pound; $discount</td>
				</tr>
				<tr>
					<td style="padding-top:5px;"></td>
					<td style="padding-top:5px;"><b>Total</b></td>
					<td style="padding-top:5px;"></td>
		            <td style="padding-top:5px;"></td>
		            <td style="padding-top:5px;text-align:right;border-top:1px double #cfcfcf;border-bottom:2px double #cfcfcf;"><b>&pound; $totalprice2</b></td>
				</tr>
			</tfoot>
		</table>
		<div style="padding:5px;">
			$terms
		</div>
		</div>
		<div style="background:#410996;width:100%;display:block;min-height:20px;padding:10px;">
			<p style="float:right;color:#ffffff"><a style="color:#ffffff;text-decoration:none;" href="http:\\www.ushbub.co.uk">USHBUB $year</a></p><div style="clear:both;">
		</div>
		</div>
  		</body>
  		</html>
EOT;
			
		$name		= $first_name." ".$last_name;
		$email		= $email_to;
		$subject	= "USHBUB shopping cart #$cart_id";
		$message	= $message_header.$message_body.$message_foot;
	
		$result = array($message,$name, $email, $subject);
			
		return $result;
	}
	 
	public function sendshopcartEmail($id = null, $mailfrom, $fromname, $sitename)
	{
		//save the new booking and send to customer
		$modelSch = new ShoppingcartheadersModel();
		$cart = $modelSch->shopcart_items($id);
		$mail = new \JMail();

		$name		= (string)$cart[1];
		$email		= (string)$cart[2];
		$subject	= (string)$cart[3];
		$body		= (string)$cart[0];
	
		$mail->addRecipient($email,$name);
		$mail->addCC($mailfrom,$fromname);
		$mail->addReplyTo($mailfrom, $fromname);
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($sitename.': '.$subject);
		$mail->isHTML(true);
		$mail->Encoding = 'base64';
		$mail->setBody($body);
		$sent = $mail->Send();
	
		return $sent;
	}
}