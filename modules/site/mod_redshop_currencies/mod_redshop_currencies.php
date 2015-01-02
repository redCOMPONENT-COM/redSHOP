<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_currencies
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$db = JFactory::getDbo();
$text_before = $params->get('text_before', '');
$currencies = $params->get('product_currency', '');

$currenciess = array();

if ($currencies)
{
	$query = $db->getQuery(true)
		->select($db->qn(array('currency_id', 'currency_code', 'currency_name')))
		->from($db->qn('#__redshop_currency'))
		->where('FIND_IN_SET(' . $db->qn('currency_code') . ', ' . $db->quote(implode(',', $currencies)) . ')')
		->order($db->qn('currency_name'));
	$db->setQuery($query);
	$currenciess = $db->loadObjectList();
}

for ($i = 0; $i < count($currenciess); $i++)
{
	$currencies[$currenciess[$i]->currency_code] = $currenciess[$i]->currency_name;
}

$session = JFactory::getSession();
$jinput = JFactory::getApplication()->input;

$productCurrency = $jinput->post->get('product_currency', '');

if (!empty($productCurrency))
	$session->set('product_currency', $productCurrency);
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
