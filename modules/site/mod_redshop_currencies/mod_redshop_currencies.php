<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_currencies
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$db = JFactory::getDbo();
$text_before = $params->get('text_before', '');
$currencies = $params->get('product_currency', '');

$currenciess = array();
JLoader::import('redshop.library');

if ($currencies)
{
	$query = $db->getQuery(true)
		->select($db->qn(array('currency_id', 'currency_code', 'currency_name')))
		->from($db->qn('#__redshop_currency'))
		->where($db->qn('currency_code') . ' IN (' . implode(',', RedshopSiteHelper::quote($currencies)) . ')')
		->order($db->qn('currency_name'));
	$db->setQuery($query);
	$currenciess = $db->loadObjectList();
}

for ($i = 0, $in = count($currenciess); $i < $in; $i++)
{
	$currencies[$currenciess[$i]->currency_code] = $currenciess[$i]->currency_name;
}

$session = JFactory::getSession();
$jinput = JFactory::getApplication()->input;

$productCurrency = $jinput->post->get('product_currency', '');

if (!empty($productCurrency))
{
	$session->set('product_currency', $productCurrency);
}
?>
<?php echo $text_before; ?>
<form action="" method="post">
	<br/>
	<?php
	echo JHTML::_('select.genericlist', $currenciess, 'product_currency', 'class="inputbox span12" size="1" ', 'currency_code', 'currency_name', $session->get('product_currency'));
	?>
	<input class="button btn btn-small" type="submit" name="submit" value="<?php echo JText::_('MOD_REDSHOP_CURRENCIES_CHANGE_CURRENCY'); ?>"/>
</form>
