<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<?php if (SHOW_CAPTCHA) : ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td>&nbsp;</td>
		<td align="left">
			<img src="<?php echo JURI::base(true);?>/index.php?tmpl=component&option=com_redshop&view=registration&task=captcha&captcha=security_code&width=100&height=40&characters=5" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right">
			<label for="security_code"><?php echo JText::_('COM_REDSHOP_SECURITY_CODE'); ?></label>
		</td>
	<td>
		<input class="inputbox" id="security_code" name="security_code" type="text" /></td>
	</tr>
</table>
<?php endif; ?>
