<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_currencies
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');


$text_before = $params->get('text_before', '');
$currencies = $params->get('product_currency', '');

$currenciess = array();

$db = JFactory::getDbo();
if ($currencies)
{

	$db->setQuery('SELECT currency_id, currency_code, currency_name FROM `#__redshop_currency` WHERE FIND_IN_SET(`currency_code`, \'' . implode(',', $currencies) . '\') ORDER BY `currency_name`');
	$currenciess = $db->loadObjectList();

}

for ($i = 0; $i < count($currenciess); $i++)
{
	$currencies[$currenciess[$i]->currency_code] = $currenciess[$i]->currency_name;
}

$session = JFactory::getSession();
$post = JRequest::get('post');
$get = JRequest::get('get');
if (isset($post['product_currency']))
	$session->set('product_currency', $post['product_currency']);
?>
<!-- Currency Selector Module -->
<?php echo $text_before; ?>
<form action="" method="post">
	<br/>
	<?php
	echo JHTML::_('select.genericlist', $currenciess, 'product_currency', 'class="inputbox" size="1" ', 'currency_code', 'currency_name', $session->get('product_currency'));
	?>
	<input class="button" type="submit" name="submit" value="<?php echo 'Change Currency' ?>"/>
</form>