<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_discount
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::load('RedshopHelperAdminConfiguration');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

?>
<div class="mod_discount_main">
	<div class="mod_discount_title"><?php echo JText::_("COM_REDSHOP_DISCOUNT_DETAIL");?></div>
	<div class="mod_discount_main_value">
		<?php
		for ($i = (count($data) - 1); $i >= 0; $i--)
		{
			for ($j = 0; $j <= $i; $j++)
			{
				?>
				<div class="mod_discount_spacer"><?php echo "&nbsp;&nbsp;";?></div>
			<?php
			}
			$data[$i]->condition == '1' ? $cond = '-' : ($data[$i]->condition == '3' ? $cond = '+' : $cond = '');
			$amount = $data[$i]->amount;
			$data[$i]->discount_type == '1' ? $disc = '%' : $disc = REDCURRENCY_SYMBOL;
			$discount_amount = $data[$i]->discount_amount;
			?>
			<div
				class="mod_discount_value"><?php echo $cond . "&nbsp;" . $amount . "&nbsp; = " . $discount_amount . "&nbsp;" . $disc; ?></div>
		<?php
		}
		?>
	</div>
</div>
