<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

require_once( JPATH_SITE.DS.'administrator/components/com_redshop/helpers'.DS.'redshop.cfg.php' );
require_once( JPATH_SITE.DS.'administrator/components/com_redshop/helpers'.DS.'order.php' );

//get module path
$mod_dir = dirname( __FILE__ );
define('CURRENCY_MODULE_PATH',$mod_dir);

$currenciess = array();
$db = JFactory::getDBO();
$session 		 	=& JFactory::getSession();
$post = JRequest::get('post');
if(isset($post['product_currency']))
{
	$session->set('product_currency',$post['product_currency']);
}
	
$currency_mode = $params->get( 'currency_mode', '');
$detect_country_by = $params->get( 'detect_country', '');
$text_before = $params->get( 'text_before', '');
$countryId = 0;
$product_currency = array();
if($currency_mode==1)
{
	if($detect_country_by==1)
	{
		$list = array();
		$auth = $session->get( 'auth');
		$user =& JFactory::getUser();
		$order_functions	= new order_functions();
		if($user->id)
		{
			$uid = $user->id;
			$list = $order_functions->getBillingAddress($uid);
		} 
		else if ($auth['users_info_id']) 
		{
			$uid = -$auth['users_info_id'];
			$list = $order_functions->getBillingAddress($uid);
		}
		if(count($list)>0)
		{
			$query = 'SELECT country_id FROM `#__redshop_country` '
					.'WHERE `country_3_code`="'.$list->country_code.'" '
					;
			$db->setQuery ( $query );
			$countryId = $db->loadResult();		
		}
	}
	else
	{
		require_once(CURRENCY_MODULE_PATH.DS.'ip2locationlite.class.php');
		$ipLite = new ip2location_lite();
		$ipLite->setKey( '35829c0ac829172fe725b6637894bc351ed157f6462ebe69fe3c1d84068fff69' );
		$locations = $ipLite->getCity( '210.211.255.199' );//210.0.255.199//$_SERVER ['REMOTE_ADDR']
//		echo "<pre>";
//		print_r($locations);
		if(count($locations)>0 && isset($locations['countryCode']) && $locations['countryCode'])
		{
			$query = 'SELECT country_id FROM `#__redshop_country` '
					.'WHERE `country_2_code`="'.$locations['countryCode'].'" '
					;
			$db->setQuery ( $query );
			$countryId = $db->loadResult();
		}
	}
	if($countryId!=0)
	{
		$query = 'SELECT currency_id, currency_code, currency_name FROM `#__redshop_currency` '
				.'WHERE 1=1 '
				.'AND FIND_IN_SET(\''.$countryId.'\', `dynamic_country_id`)'
				.'ORDER BY `currency_name` '
				;
		$db->setQuery ( $query );
		$currenciess = $db->loadObjectList();
		
		//Start Code for convert price according to first currency shown in dropdown 
		if($post['product_currency'])
		{
			$session->set('product_currency',$post['product_currency']);
		}else{
			$session->set('product_currency',$currenciess[0]->currency_code);
		}
	}
	if(count($currenciess)<=0)
	{
		$product_currency[] = CURRENCY_CODE;
	}
}
else
{
	$product_currency = $params->get( 'product_currency', '' ) ;
}

if(count($product_currency)>0)
{
	$query = 'SELECT currency_id, currency_code, currency_name FROM `#__redshop_currency` '
			.'WHERE 1=1 '
			.'AND FIND_IN_SET(`currency_code`, \''.implode(',',$product_currency).'\') '
			.'ORDER BY `currency_name` '
			;
	$db->setQuery ( $query );
	$currenciess = $db->loadObjectList();
}

/*for ($i=0;$i<count($currenciess);$i++) 
{
	$product_currency[$currenciess[$i]->currency_code] = $currenciess[$i]->currency_name;
}*/
?>
<!-- Currency Selector Module -->
<?php echo $text_before; ?>
<form action="" method="post">
	<br /><?php	
	echo JHTML::_('select.genericlist',$currenciess,'product_currency','class="inputbox" size="1" ','currency_code','currency_name',$session->get('product_currency'));	?>
    <input class="button" type="submit" name="submit" value="<?php echo JText::_('CHANGE_CURRENCY'); ?>" />
</form>
