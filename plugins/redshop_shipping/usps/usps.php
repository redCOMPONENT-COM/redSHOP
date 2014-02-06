<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */
//defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/shipping.php';

class plgredshop_shippingusps extends JPlugin
{
	var $payment_code = "usps";
	var $classname = "usps";

	function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table class="admintable">
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_USERNAME') ?></strong></td>
				<td><input type="text" name="USPS_USERNAME" class="inputbox" value="<?php echo USPS_USERNAME ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_USERNAME'), JText::_('COM_REDSHOP_USPS_USERNAME'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_PASSWORD') ?></strong></td>
				<td><input type="text" name="USPS_PASSWORD" class="inputbox" value="<?php echo USPS_PASSWORD ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_PASSWORD'), JText::_('COM_REDSHOP_USPS_PASSWORD'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_SERVER') ?></strong></td>
				<td><input type="text" name="USPS_SERVER" class="inputbox" value="<?php echo USPS_SERVER ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_SERVER'), JText::_('COM_REDSHOP_USPS_SERVER'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_PATH') ?></strong></td>
				<td><input type="text" name="USPS_PATH" class="inputbox" value="<?php echo USPS_PATH ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_PATH'), JText::_('COM_REDSHOP_USPS_PATH'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_SOURCE_ZIP') ?></strong></td>
				<td><input type="text" name="USPS_SOURCE_ZIP" class="inputbox" value="<?php echo USPS_SOURCE_ZIP ?>"/>
				</td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_SOURCE_ZIP'), JText::_('COM_REDSHOP_USPS_SOURCE_ZIP'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_PACKAGESIZE') ?></strong></td>
				<td><select class="inputbox" name="USPS_PACKAGESIZE">
						<option <?php if (USPS_PACKAGESIZE == "Regular") echo "selected=\"selected\"" ?>
							value="Regular">Regular
						<option <?php if (USPS_PACKAGESIZE == "Large") echo "selected=\"selected\"" ?> value="Large">
							Large
						</option>
						<option <?php if (USPS_PACKAGESIZE == "Oversize") echo "selected=\"selected\"" ?>
							value="Oversize">Oversize
						</option>
					</select>
				</td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_PACKAGESIZE'), JText::_('COM_REDSHOP_USPS_PACKAGESIZE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td colspan='3'>
					<hr size='2'/>

				</td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_HANDLINGFEE') ?></strong></td>
				<td><input type="text" name="USPS_HANDLINGFEE" class="inputbox" value="<?php echo USPS_HANDLINGFEE ?>"/>
				</td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_HANDLINGFEE'), JText::_('COM_REDSHOP_USPS_HANDLINGFEE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_PADDING') ?></strong></td>
				<td><input type="text" name="USPS_PADDING" class="inputbox" value="<?php echo USPS_PADDING ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_PADDING'), JText::_('COM_REDSHOP_USPS_PADDING'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_INTLLBRATE') ?></strong></td>
				<td><input type="text" name="USPS_INTLLBRATE" class="inputbox" value="<?php echo USPS_INTLLBRATE ?>"/>
				</td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_INTLLBRATE'), JText::_('COM_REDSHOP_USPS_INTLLBRATE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_USPS_INTLHANDLINGFEE') ?></strong></td>
				<td><input type="text" name="USPS_INTLHANDLINGFEE" class="inputbox"
				           value="<?php echo USPS_INTLHANDLINGFEE ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_USPS_INTLHANDLINGFEE'), JText::_('COM_REDSHOP_USPS_INTLHANDLINGFEE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_MACHINABLE') ?></strong></td>
				<td>
					<label>
						<input name="USPS_MACHINABLE"
						       type="radio" <?php if (USPS_MACHINABLE == 1) echo "checked=\"checked\""; ?> value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_MACHINABLE"
						       type="radio" <?php if (USPS_MACHINABLE == 0) echo "checked=\"checked\""; ?> value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_SHOW_DELIVERY_QUOTE') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHOW_DELIVERY_QUOTE"
						       type="radio" <?php if (USPS_SHOW_DELIVERY_QUOTE == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHOW_DELIVERY_QUOTE"
						       type="radio" <?php if (USPS_SHOW_DELIVERY_QUOTE == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td colspan='3'>
					<hr size='2'/>

				</td>
			</tr>
			<tr class="row0">
				<td></td>
				<td><strong><?php echo "Domestic Shipping Options" ?></strong></td>
				<td></td>
			</tr>
			<tr class="row1">
				<td colspan='3'>
					<hr size='2'/>

				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_EXPRESS_MAIL_PO_TO_PO') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP0" type="radio" <?php if (USPS_SHIP0 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP0" type="radio" <?php if (USPS_SHIP0 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_EXPRESS_MAIL') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP1" type="radio" <?php if (USPS_SHIP1 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP1" type="radio" <?php if (USPS_SHIP1 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_EXPRESS_MAIL_FLAT_RATE_ENVELOPE') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP2" type="radio" <?php if (USPS_SHIP2 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP2" type="radio" <?php if (USPS_SHIP2 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_PRIORITY_MAIL') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP3" type="radio" <?php if (USPS_SHIP3 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP3" type="radio" <?php if (USPS_SHIP3 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_PRIORITY_MAIL_FLAT_RATE_ENVELOPE') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP4" type="radio" <?php if (USPS_SHIP4 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP4" type="radio" <?php if (USPS_SHIP4 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_PRIORITY_MAIL_FLAT_RATE_BOX') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP5" type="radio" <?php if (USPS_SHIP5 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP5" type="radio" <?php if (USPS_SHIP5 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_FIRST_CLASS_MAIL') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP6" type="radio" <?php if (USPS_SHIP6 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP6" type="radio" <?php if (USPS_SHIP6 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_PARCEL_POST') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP7" type="radio" <?php if (USPS_SHIP7 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP7" type="radio" <?php if (USPS_SHIP7 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_BOUND_PRINTED_MATTER') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP8" type="radio" <?php if (USPS_SHIP8 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP8" type="radio" <?php if (USPS_SHIP8 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_MEDIA_MAIL') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP9" type="radio" <?php if (USPS_SHIP9 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP9" type="radio" <?php if (USPS_SHIP9 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_LIBRARY_MAIL') ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHIP10"
						       type="radio" <?php if (USPS_SHIP10 == 1) echo "checked=\"checked\""; ?> value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_SHIP10"
						       type="radio" <?php if (USPS_SHIP10 == 0) echo "checked=\"checked\""; ?> value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td colspan='3'>
					<hr size='2'/>

				</td>
			</tr>
			<tr class="row0">
				<td></td>
				<td><strong><?php echo "International Shipping Options" ?></strong></td>
				<td></td>
			</tr>
			<tr class="row1">
				<td colspan='3'>
					<hr size='2'/>

				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_GLOBAL_EXPRESS_GUARANTEED') ?></strong></td>
				<td>
					<label>
						<input name="USPS_GLOBAL_EXPRESS_GUARANTEED"
						       type="radio" <?php if (USPS_GLOBAL_EXPRESS_GUARANTEED == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_GLOBAL_EXPRESS_GUARANTEED"
						       type="radio" <?php if (USPS_GLOBAL_EXPRESS_GUARANTEED == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_GLOBAL_EXPRESS_GUARANTEED_NON_DOCUMENT_RECTANGULAR') ?></strong>
				</td>
				<td>
					<label>
						<input name="USPS_INTL1" type="radio" <?php if (USPS_INTL1 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_INTL1" type="radio" <?php if (USPS_INTL1 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_GLOBAL_EXPRESS_RECTANGULAR') ?></strong></td>
				<td>
					<label>
						<input name="USPS_INTL2" type="radio" <?php if (USPS_INTL2 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_INTL2" type="radio" <?php if (USPS_INTL2 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_EXPRESS_MAIL_INTERNATIONAL_(EMS)') ?></strong>
				</td>
				<td>
					<label>
						<input name="USPS_INTL3" type="radio" <?php if (USPS_INTL3 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_INTL3" type="radio" <?php if (USPS_INTL3 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_EXPRESS_MAIL_INTERNATIONAL_(EMS)_FLAT_RATE_ENVELOPE') ?></strong>
				</td>
				<td>
					<label>
						<input name="USPS_INTL4" type="radio" <?php if (USPS_INTL4 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_INTL4" type="radio" <?php if (USPS_INTL4 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_PRIORITY_MAIL_INTERNATIONAL') ?></strong></td>
				<td>
					<label>
						<input name="USPS_INTL5" type="radio" <?php if (USPS_INTL5 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_INTL5" type="radio" <?php if (USPS_INTL5 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_PRIORITY_MAIL_INTERNATIONAL_FLAT_RATE_ENVELOPE') ?></strong>
				</td>
				<td>
					<label>
						<input name="USPS_INTL6" type="radio" <?php if (USPS_INTL6 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_INTL6" type="radio" <?php if (USPS_INTL6 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_PRIORITY_MAIL_INTERNATIONAL_FLAT_RATE_BOX') ?></strong>
				</td>
				<td>
					<label>
						<input name="USPS_INTL7" type="radio" <?php if (USPS_INTL7 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_INTL7" type="radio" <?php if (USPS_INTL7 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			<tr class="row1">
				<td>
					<strong><?php echo JText::_('COM_REDSHOP_USPS_FIRST_CLASS_MAIL_INTERNATIONAL') ?></strong></td>
				<td>
					<label>
						<input name="USPS_INTL8" type="radio" <?php if (USPS_INTL8 == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						Yes
					</label>
					<label>
						<input name="USPS_INTL8" type="radio" <?php if (USPS_INTL8 == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						No
					</label>
				</td>
				<td>
				</td>
			</tr>
			</table>

			<?php

			return true;
		}
	}

	function onWriteconfig($d)
	{
		if ($d['element'] == $this->classname)
		{
			$maincfgfile = JPATH_ROOT . '/plugins/' . $d['plugin'] . '/' . $this->classname . '/' . $this->classname . '.cfg.php';
			$my_config_array = array(
				"USPS_USERNAME"                  => $d['USPS_USERNAME'],
				"USPS_PASSWORD"                  => $d['USPS_PASSWORD'],
				"USPS_SERVER"                    => $d['USPS_SERVER'],
				"USPS_PATH"                      => $d['USPS_PATH'],
				"USPS_SOURCE_ZIP"                => $d['USPS_SOURCE_ZIP'],
				"USPS_PACKAGESIZE"               => $d['USPS_PACKAGESIZE'],
				"USPS_TAX_CLASS"                 => $d['USPS_TAX_CLASS'],
				"USPS_HANDLINGFEE"               => $d['USPS_HANDLINGFEE'],
				"USPS_PADDING"                   => $d['USPS_PADDING'],
				"USPS_INTLLBRATE"                => $d['USPS_INTLLBRATE'],
				"USPS_INTLHANDLINGFEE"           => $d['USPS_INTLHANDLINGFEE'],
				"USPS_MACHINABLE"                => $d['USPS_MACHINABLE'],
				"USPS_SHOW_DELIVERY_QUOTE"       => $d['USPS_SHOW_DELIVERY_QUOTE'],
				"USPS_GLOBAL_EXPRESS_GUARANTEED" => $d['USPS_GLOBAL_EXPRESS_GUARANTEED'],
				"USPS_SHIP0"                     => $d['USPS_SHIP0'],
				"USPS_SHIP1"                     => $d['USPS_SHIP1'],
				"USPS_SHIP2"                     => $d['USPS_SHIP2'],
				"USPS_SHIP3"                     => $d['USPS_SHIP3'],
				"USPS_SHIP4"                     => $d['USPS_SHIP4'],
				"USPS_SHIP5"                     => $d['USPS_SHIP5'],
				"USPS_SHIP6"                     => $d['USPS_SHIP6'],
				"USPS_SHIP7"                     => $d['USPS_SHIP7'],
				"USPS_SHIP8"                     => $d['USPS_SHIP8'],
				"USPS_SHIP9"                     => $d['USPS_SHIP9'],
				"USPS_SHIP10"                    => $d['USPS_SHIP10'],
				"USPS_INTL0"                     => $d['USPS_INTL0'],
				"USPS_INTL1"                     => $d['USPS_INTL1'],
				"USPS_INTL2"                     => $d['USPS_INTL2'],
				"USPS_INTL3"                     => $d['USPS_INTL3'],
				"USPS_INTL4"                     => $d['USPS_INTL4'],
				"USPS_INTL5"                     => $d['USPS_INTL5'],
				"USPS_INTL6"                     => $d['USPS_INTL6'],
				"USPS_INTL7"                     => $d['USPS_INTL7'],
				"USPS_INTL8"                     => $d['USPS_INTL8']

				// END CUSTOM CODE
			);

			$config = "<?php ";

			foreach ($my_config_array as $key => $value)
			{
				$config .= "define ('$key', '$value');\n";
			}

			$config .= "?>";

			if ($fp = fopen($maincfgfile, "w"))
			{
				fputs($fp, $config, strlen($config));
				fclose($fp);

				return true;
			}
			else
			{
				return false;
			}
		}
	}

	function onListRates(&$d)
	{
		$shippinghelper = new shipping;
		$producthelper = new producthelper;

		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);
		$shippingcfg = JPATH_ROOT . '/plugins/' . $shipping->folder . '/' . $shipping->element . '/' . $shipping->element . '.cfg.php';
		include_once $shippingcfg;
		$shippingrate = array();
		$rate = 0;

		// conversation of weight ( ration )
		$unitRatio = $producthelper->getUnitConversation('pounds', DEFAULT_WEIGHT_UNIT);
		$totaldimention = $shippinghelper->getCartItemDimention();
		$order_weight = $totaldimention['totalweight'];

		if ($unitRatio != 0)
		{
			$order_weight = $order_weight * $unitRatio; // converting weight in pounds
		}

		if ($order_weight > 0)
		{
			$shippinginfo = $shippinghelper->getShippingAddress($d['users_info_id']);

			if (count($shippinginfo) < 1)
			{
				return $shippingrate;
			}

			$billing = $producthelper->getUserInformation($shippinginfo->user_id);

			if (count($billing) < 1)
			{
				return $shippingrate;
			}

			$itemparams = new JRegistry($shipping->params);

			//USPS Username
			$usps_username = USPS_USERNAME; //$itemparams->get("usps_username");
			//USPS Password
			$usps_password = USPS_PASSWORD; //$itemparams->get("usps_password");
			//USPS Server
			$usps_server = USPS_SERVER; //$itemparams->get("usps_server");
			//USPS Path
			$usps_path = USPS_PATH; //$itemparams->get("usps_path");
			//USPS Zip Code
			$usps_source_zip = USPS_SOURCE_ZIP; //$itemparams->get("usps_source_zip");
			//USPS package size
			$usps_packagesize = USPS_PACKAGESIZE; //$itemparams->get("usps_packagesize");
			//USPS Package ID
			$usps_packageid = 0;
			//USPS International Per Pound Rate
			$usps_intllbrate = USPS_INTLLBRATE; //$itemparams->get("usps_intllbrate");
			//USPS International handling fee
			$usps_intlhandlingfee = USPS_INTLHANDLINGFEE; // $itemparams->get("usps_handlingfee");
			//Pad the shipping weight to allow weight for shipping materials
			$usps_padding = USPS_PADDING * 0.01;
			$order_weight = ($order_weight * $usps_padding) + $order_weight;
			//USPS Machinable for Parcel Post
			$usps_machinable = USPS_MACHINABLE; //$itemparams->get("usps_machinable");

			if ($usps_machinable == '1') $usps_machinable = 'TRUE';
			else $usps_machinable = 'FALSE';
			$order_weight = 12;
			//USPS Shipping Options to display
			$usps_ship[0]['value'] = USPS_SHIP0; //$itemparams->get("usps_express_mail_po_to_po");
			$usps_ship[0]['name'] = 'Express Mail to PO Addressee';
			$usps_ship[1]['value'] = USPS_SHIP1; //$itemparams->get("usps_express_mail");
			$usps_ship[1]['name'] = 'Express Mail';
			$usps_ship[2]['value'] = USPS_SHIP2; //$itemparams->get("usps_express_mail_flat_rate_envelope");
			$usps_ship[2]['name'] = 'Express Mail Flat Rate Envelope';
			$usps_ship[3]['value'] = USPS_SHIP3; //$itemparams->get("usps_priority_mail");
			$usps_ship[3]['name'] = 'Priority Mail';
			$usps_ship[4]['value'] = USPS_SHIP4; //$itemparams->get("usps_priority_mail_flat_rate_envelope");
			$usps_ship[4]['name'] = 'Priority Mail Flat Rate Envelope';
			$usps_ship[5]['value'] = USPS_SHIP5; //$itemparams->get("usps_priority_mail_flat_rate_box");
			$usps_ship[5]['name'] = 'Priority Mail Flat Rate Box';
			$usps_ship[6]['value'] = USPS_SHIP6; //$itemparams->get("usps_first_class_mail");
			$usps_ship[6]['name'] = 'First Class Mail';
			$usps_ship[7]['value'] = USPS_SHIP7; //$itemparams->get("usps_parcel_post");
			$usps_ship[7]['name'] = 'Parcel Post';
			$usps_ship[8]['value'] = USPS_SHIP8; //$itemparams->get("usps_bound_printed_matter");
			$usps_ship[8]['name'] = 'Bound Printed Matter';
			$usps_ship[9]['value'] = USPS_SHIP9; //$itemparams->get("usps_media_mail");
			$usps_ship[9]['name'] = 'Media Mail';
			$usps_ship[10]['value'] = USPS_SHIP10; //$itemparams->get("usps_library_mail");
			$usps_ship[10]['name'] = 'Library Mail';

			$usps_intl[0] = USPS_GLOBAL_EXPRESS_GUARANTEED; //$itemparams->get("usps_global_express_guaranteed");
			$usps_intl[1] = USPS_INTL1; //$itemparams->get("usps_global_express_guaranteed_non_document_rectangular");
			$usps_intl[2] = USPS_INTL2; //$itemparams->get("usps_global_express_rectangular");
			$usps_intl[3] = USPS_INTL3; //$itemparams->get("usps_express_mail_international_(EMS)");
			$usps_intl[4] = USPS_INTL4; //$itemparams->get("usps_express_mail_international_(EMS)_flat_rate_envelope");
			$usps_intl[5] = USPS_INTL5; //$itemparams->get("usps_priority_mail_international");
			$usps_intl[6] = USPS_INTL6; //$itemparams->get("usps_priority_mail_international_flat_rate_envelope");
			$usps_intl[7] = USPS_INTL7; //$itemparams->get("usps_priority_mail_international_flat_rate_box");
			$usps_intl[8] = USPS_INTL8; //$itemparams->get("usps_first_class_mail_international");
			// $usps_intl[9] = USPS_INTL9;

			foreach ($usps_intl as $key => $value)
			{
				if ($value == '1') $usps_intl[$key] = 'TRUE';
				else $usps_intl[$key] = 'FALSE';
			}
			//Title for your request
			$request_title = "Shipping Estimate";

			//The zip that you are shipping from
			//$source_zip = substr($billing->zipcode,0,5);
			$source_zip = substr($usps_source_zip, 0, 5);
			$shpService = 'All'; //"Priority";

			//The zip that you are shipping to
			$dest_country = $shippinginfo->country_code;
			$dest_country_name = $shippinginfo->country_code;

			$dest_state = $shippinginfo->state_code;
			$dest_zip = substr($shippinginfo->zipcode, 0, 5);

			if ($order_weight < 1)
			{
				$shipping_pounds_intl = 0;
			}
			else
			{
				$shipping_pounds_intl = ceil($order_weight);
			}

			if ($order_weight < 0.88)
			{
				$shipping_pounds = 0;
				$shipping_ounces = round(16 * ($order_weight - floor($order_weight)));
			}
			else
			{
				$shipping_pounds = ceil($order_weight);
				$shipping_ounces = 0;
			}

			$os = array("Mac", "NT", "Irix", "Linux");
			$states = array("AL", "AK", "AR", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WI", "WV", "WY");
			//If weight is over 70 pounds, round down to 70 for now.
			//Will update in the future to be able to split the package or something?
			if ($order_weight > 70.00)
			{
				echo $shippingrate; //"We are unable to ship USPS as the package weight exceeds the 70 pound limit,<br>please select another shipping method.";
			}
			else
			{
				if ($dest_country == "USA" && in_array($dest_state, $states))
				{
					/******START OF DOMESTIC RATE******/
					//the xml that will be posted to usps
					$xmlPost = 'API=RateV2&XML=<RateV2Request USERID="' . $usps_username . '" PASSWORD="' . $usps_password . '">';
					$xmlPost .= '<Package ID="' . $usps_packageid . '">';
					$xmlPost .= "<Service>" . $shpService . "</Service>";
					$xmlPost .= "<ZipOrigination>" . $source_zip . "</ZipOrigination>";
					$xmlPost .= "<ZipDestination>" . $dest_zip . "</ZipDestination>";
					$xmlPost .= "<Pounds>" . $shipping_pounds . "</Pounds>";
					$xmlPost .= "<Ounces>" . $shipping_ounces . "</Ounces>";
					$xmlPost .= "<Size>" . $usps_packagesize . "</Size>";
					$xmlPost .= "<Machinable>" . $usps_machinable . "</Machinable>";
					$xmlPost .= "</Package></RateV2Request>";
					//$usps_server = "production.shippingapis.com";
					//$usps_path = "/ShippingAPI.dll";

					$html = "";
					// Using cURL is Up-To-Date and easier!!
					if (function_exists("curl_init"))
					{
						$CR = curl_init();
						curl_setopt($CR, CURLOPT_URL, "http://" . $usps_server . $usps_path); //"?API=RateV2&XML=".$xmlPost);
						curl_setopt($CR, CURLOPT_POST, 1);
						curl_setopt($CR, CURLOPT_FAILONERROR, true);
						curl_setopt($CR, CURLOPT_POSTFIELDS, $xmlPost);
						curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
						$xmlResult = curl_exec($CR);

						$error = curl_error($CR);

						if (!empty($error))
						{
							$html = "<br/><span class=\"message\">" . JText::_('COM_REDSHOP_PHPSHOP_INTERNAL_ERROR') . " USPS.com</span>";
							$error = true;
						}
						else
						{
							/* XML Parsing */
							$xmlDoc = JFactory::getXMLParser('Simple');
							$xmlDoc->loadString($xmlResult);
							/* Let's check wether the response from USPS is Success or Failure ! */
							if (strstr($xmlResult, "Error"))
							{ //echo $xmlResult;exit;
								$error = true;
								$html = "<span class=\"message\">" . JText::_('COM_REDSHOP_PHPSHOP_USPS_RESPONSE_ERROR') . "</span><br/>";
//								$html .= JText::_('COM_REDSHOP_PHPSHOP_ERROR_CODE').": ".$xmlResult."<br/>";
								$html .= JText::_('COM_REDSHOP_PHPSHOP_ERROR_DESC') . ": " . $xmlResult . "<br/>";
							}
						}
						curl_close($CR);
					}
					else
					{
						$fp = fsockopen("http://" . $usps_server, $errno, $errstr, $timeout = 60);

						if (!$fp)
						{
							$error = true;
							$html = JText::_('COM_REDSHOP_PHPSHOP_INTERNAL_ERROR') . ": $errstr ($errno)";
						}
						else
						{
							//send the server request
							fputs($fp, "POST $usps_path HTTP/1.1\r\n");
							fputs($fp, "Host: $usps_server\r\n");
							fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
							fputs($fp, "Content-length: " . strlen($xmlPost) . "\r\n");
							fputs($fp, "Connection: close\r\n\r\n");
							fputs($fp, $xmlPost . "\r\n\r\n");
							$xmlResult = '';
							while (!feof($fp))
							{
								$xmlResult .= fgets($fp, 4096);
							}

							if (stristr($xmlResult, "Success"))
							{
								/* XML Parsing */
								$xmlDoc = JFactory::getXMLParser('Simple');
								$xmlDoc->loadString($xmlResult);
								$error = false;
							}
							else
							{
								$html = "Error processing the Request to USPS.com";
								$error = true;
							}
						}
					}

					if ($itemparams->get("usps_debug"))
					{
						echo "XML Post: <br>";
						echo "<textarea cols='80' rows='10'>http://" . $usps_server . $usps_path . "?" . $xmlPost . "</textarea>";
						echo "<br>";
						echo "XML Result: <br>";
						echo "<textarea cols='80' rows='10'>" . $xmlResult . "</textarea>";
						echo "<br>";
						echo "Cart Contents: " . $order_weight . "<br><br>\n";
					}

					if ($error)
					{
						return $shippingrate; //"We are unable to ship USPS as the there was an error,<br> please select another shipping method.";
					}
					// Domestic shipping - add how long it might take
					$ship_commit[0] = "1 - 2 Days";
					$ship_commit[1] = "1 - 2 Days";
					$ship_commit[2] = "1 - 2 Days";
					$ship_commit[3] = "1 - 3 Days";
					$ship_commit[4] = "1 - 3 Days";
					$ship_commit[5] = "1 - 3 Days";
					$ship_commit[6] = "2 - 9 Days";
					$ship_commit[7] = "2 - 9 Days";
					$ship_commit[8] = "2 - 9 Days";
					$ship_commit[9] = "2 - 9 Days";
					$ship_commit[10] = "2 Days or More";

					// retrieve the service and postage items
					$i = 0;

					if ($order_weight > 15)
					{
						$count = 8;
						/*	$usps_ship[6] = $usps_ship[7];
							$usps_ship[7] = $usps_ship[9];
							$usps_ship[8] = $usps_ship[10];*/
					}
					else if ($order_weight >= 0.86)
					{
						$count = 9;
						/*$usps_ship[6] = $usps_ship[7];
						$usps_ship[7] = $usps_ship[8];
						$usps_ship[8] = $usps_ship[9];
						$usps_ship[9] = $usps_ship[10];*/
					}
					else
					{
						$count = 10;
					}

					$i = 0;
					$matchedchild = $xmlDoc->document->_children;
					$data = array();

					for ($us = 0; $us < count($usps_ship); $us++)
					{
						if ($usps_ship[$us]['value'] == 1)
						{
							$data[] = $usps_ship[$us];
						}
					}

					for ($t = 0; $t < count($matchedchild); $t++)
					{
						$totalmatchedchild = $matchedchild[$t]->_children;

						for ($i = 0; $i < count($totalmatchedchild); $i++)
						{
							$currNode = $totalmatchedchild[$i];
							$matched_childname = $currNode->name();

							if ($matched_childname == "postage")
							{
								$postage_child = $currNode->_children;
								$mailservice = $currNode->getElementByPath("mailservice");
								$ship_service[$count] = $mailservice->data(); //html_entity_decode($mailservice->data());
								$rateData = $currNode->getElementByPath("rate");
								$ship_postage[$count] = $rateData->data();

								if (preg_match('/%$/', USPS_HANDLINGFEE))
								{
									$ship_postage[$count] = $ship_postage[$count] * (1 + substr(USPS_HANDLINGFEE, 0, -1) / 100);
								}
								else
								{
									$ship_postage[$count] = $ship_postage[$count] + USPS_HANDLINGFEE;
								}

								$count++;
							}
						}
					}
					/******END OF DOMESTIC RATE******/
				}
				else
				{
					/******START INTERNATIONAL RATE******/
					//the xml that will be posted to usps

					$order_functions = new order_functions;
					$dest_country_name = $order_functions->getCountryName($dest_country);

					$xmlPost = 'API=IntlRate&XML=<IntlRateRequest USERID="' . $usps_username . '" PASSWORD="' . $usps_password . '">';
					$xmlPost .= '<Package ID="' . $usps_packageid . '">';
					$xmlPost .= "<Pounds>" . $shipping_pounds_intl . "</Pounds>";
					$xmlPost .= "<Ounces>" . $shipping_ounces . "</Ounces>";
					$xmlPost .= "<MailType>Package</MailType>";
					$xmlPost .= "<Country>" . $dest_country_name . "</Country>";
					$xmlPost .= "</Package></IntlRateRequest>";

					// Using cURL is Up-To-Date and easier!!
					if (function_exists("curl_init"))
					{
						$CR = curl_init();
						curl_setopt($CR, CURLOPT_URL, "http://" . $usps_server . $usps_path); //"?API=RateV2&XML=".$xmlPost);
						curl_setopt($CR, CURLOPT_POST, 1);
						curl_setopt($CR, CURLOPT_FAILONERROR, true);
						curl_setopt($CR, CURLOPT_POSTFIELDS, $xmlPost);
						curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
						$xmlResult = curl_exec($CR);

						$error = curl_error($CR);

						if (!empty($error))
						{
							$html = "<br/><span class=\"message\">" . JText::_('COM_REDSHOP_PHPSHOP_INTERNAL_ERROR') . " USPS.com</span>";
							$error = true;
						}
						else
						{
							/* XML Parsing */
							$xmlDoc = JFactory::getXMLParser('Simple');
							$xmlDoc->loadString($xmlResult);
							/* Let's check wether the response from USPS is Success or Failure ! */
							if (strstr($xmlResult, "Error"))
							{
								$error = true;
								$html = "<span class=\"message\">" . JText::_('COM_REDSHOP_PHPSHOP_USPS_RESPONSE_ERROR') . "</span><br/>";
								$html .= JText::_('COM_REDSHOP_PHPSHOP_ERROR_DESC') . ": " . $xmlResult . "<br/>";
							}
						}
						curl_close($CR);
					}
					else
					{
						$fp = fsockopen("http://" . $usps_server, $errno, $errstr, $timeout = 60);

						if (!$fp)
						{
							$error = true;
							$html = JText::_('COM_REDSHOP_PHPSHOP_INTERNAL_ERROR') . ": $errstr ($errno)";
						}
						else
						{
							//send the server request
							fputs($fp, "POST $usps_path HTTP/1.1\r\n");
							fputs($fp, "Host: $usps_server\r\n");
							fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
							fputs($fp, "Content-length: " . strlen($xmlPost) . "\r\n");
							fputs($fp, "Connection: close\r\n\r\n");
							fputs($fp, $xmlPost . "\r\n\r\n");

							$xmlResult = '';
							while (!feof($fp))
							{
								$xmlResult .= fgets($fp, 4096);
							}

							if (stristr($xmlResult, "Success"))
							{
								/* XML Parsing */
								$xmlDoc = JFactory::getXMLParser('Simple');
								$xmlDoc->loadString($xmlResult);
								$error = false;
							}
							else
							{
								$html = "Error processing the Request to USPS.com";
								$error = true;
							}
						}
					}

					if ($itemparams->get("usps_debug"))
					{
						echo "XML Post: <br>";
						echo "<textarea cols='80' rows='10'>http://" . $usps_server . $usps_path . "?" . $xmlPost . "</textarea>";
						echo "<br>";
						echo "XML Result: <br>";
						echo "<textarea cols='80' rows='10'>" . $xmlResult . "</textarea>";
						echo "<br>";
						echo "Cart Contents: " . $order_weight . "<br><br>\n";
					}

					if ($error)
					{
						echo "We are unable to ship USPS as there was an error,<br> please select another shipping method.";
					}
					//Edited 08 04 2010
					$ratev3response = $xmlDoc->document;
					$package = $ratev3response->getElementByPath("package");
					$totalmatchedchild = $package->_children;

					if ($totalmatchedchild != null)
					{
						$count = 0;

						for ($i = 0; $i < count($totalmatchedchild); $i++)
						{
							$currNode = $totalmatchedchild[$i];
							$matched_childname = $currNode->name();

							if ($matched_childname == "service")
							{
								$service_child = $currNode->_children;
								$SvcDescription = $currNode->getElementByPath("svcdescription");

								$strTitle = preg_replace('|<sup>(.*?)</sup>|', '', htmlspecialchars_decode($SvcDescription->data(), ENT_QUOTES));
								$strTitle = str_replace('*', '', $strTitle);

								$ship_service[$count] = $SvcDescription->data(); //html_entity_decode();
								$postage = $currNode->getElementByPath("postage");
								$ship_postage[$count] = $postage->data();
								$svccommitments = $currNode->getElementByPath("svccommitments");
								$ship_commit[$count] = $svccommitments->data();
								$maxweight = $currNode->getElementByPath("maxweight");
								$ship_weight[$count] = $maxweight->data();

								if (preg_match('/%$/', USPS_INTLHANDLINGFEE))
								{
									$ship_postage[$count] = $ship_postage[$count] * (1 + substr(USPS_INTLHANDLINGFEE, 0, -1) / 100);
								}
								else
								{
									$ship_postage[$count] = $ship_postage[$count] + USPS_INTLHANDLINGFEE;
								}

								$count++;
							}
						}
					}
				}
//				print_r($ship_service);

				if (($dest_country == "USA") && in_array($dest_state, $states))
				{
					$i = 0;
					while ($i < $count)
					{
						for ($j = 0; $j < count($data); $j++)
						{
							$ship_service[$i] = strip_tags(html_entity_decode($ship_service[$i]));
							$ship_service[$i] = str_replace('&reg;', '', $ship_service[$i]);

							if ($data[$j]['name'] == $ship_service[$i])
							{
								$delivery = "";

								if (USPS_SHOW_DELIVERY_QUOTE == 1 && !empty($ship_commit[$i]))
								{
									$delivery = $ship_commit[$i];
								}

								$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $ship_service[$i] . "|" . number_format($ship_postage[$i], 2, '.', '') . "|" . $ship_service[$i] . "|single|0");

								$shippingrate[$rate]->text = $ship_service[$i]; //.$delivery;
								$shippingrate[$rate]->value = $shipping_rate_id;
								$shippingrate[$rate]->rate = $ship_postage[$i];
								$shippingrate[$rate]->vat = 0;
								$rate++;
							}
						}

						$i++;
					}
				}
				else
				{
					$i = 0;
					while ($i < $count)
					{
						$delivery = "";

						if (USPS_SHOW_DELIVERY_QUOTE == 1 && !empty($ship_commit[$i]))
						{
							$delivery = $ship_commit[$i];
						}

						$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $shipping->name . "|" . number_format($ship_postage[$i], 2, '.', '') . "|" . $shipping->name . "|single|0");

						$shippingrate[$rate]->text = $ship_service[$i]; //.$delivery;
						$shippingrate[$rate]->value = $shipping_rate_id;
						$shippingrate[$rate]->rate = $ship_postage[$i];
						$shippingrate[$rate]->vat = 0;
						$rate++;
						$i++;
					}
				}
			}
		}

		return $shippingrate;
	}
}

?>