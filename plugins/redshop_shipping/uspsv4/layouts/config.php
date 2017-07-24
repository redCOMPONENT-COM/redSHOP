<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<table class="table table-striped">
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_USERNAME'); ?>
		</td>
		<td>
			<input
				type="text"
				name="USPS_USERNAME"
				class="form-control"
				value="<?php echo USPS_USERNAME ?>"
			/>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_USERNAME_TOOLTIP'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PASSWORD'); ?>
		</td>
		<td>
			<input
				type="text"
				name="USPS_PASSWORD"
				class="form-control"
				value="<?php echo USPS_PASSWORD ?>"
			/>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PASSWORD_TOOLTIP'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SERVER'); ?>
		</td>
		<td>
			<input
				type="text"
				name="USPS_SERVER"
				class="form-control"
				value="<?php echo USPS_SERVER ?>"
			/>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SERVER_TOOLTIP'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PATH'); ?>

		</td>
		<td>
			<input
				type="text"
				name="USPS_PATH"
				class="form-control"
				value="<?php echo USPS_PATH ?>"
			/>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PATH_TOOLTIP'); ?>
		</td>
	</tr>
	</tr>
	<tr class="row1">
		<td>
			<?php echo JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE');?>
		</td>
		<td>
			<input
				class="form-control"
				type="text"
				name="OVERRIDE_SOURCE_ZIP"
				value="<?php echo OVERRIDE_SOURCE_ZIP ?>"
			/>
		</td>
		<td>
			<?php echo JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE_TOOLTIP'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PROXYSERVER'); ?>
		</td>
		<td>
			<input
				type="text"
				name="USPS_PROXYSERVER"
				class="form-control"
				value="<?php echo USPS_PROXYSERVER ?>"
			/>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PROXYSERVER_TOOLTIP'); ?>
		</td>
	</tr>
	<tr><td colspan="3"><hr/></td></tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PADDING'); ?>
		</td>
		<td>
			<input
				class="form-control"
				type="text"
				name="USPS_PADDING"
				value="<?php echo USPS_PADDING ?>"
			/>
		</td>
		<td><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PADDING_TOOLTIP'); ?></td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_HANDLING_FEE'); ?>
		</td>
		<td>
			<input
				class="form-control"
				type="text"
				name="USPS_HANDLINGFEE"
				value="<?php echo USPS_HANDLINGFEE ?>"
			/>
		</td>
		<td><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_HANDLING_FEE_TOOLTIP'); ?></td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTLHANDLINGFEE'); ?>

		</td>
		<td>
			<input
				type="text"
				name="USPS_INTLHANDLINGFEE"
				class="form-control"
				value="<?php echo USPS_INTLHANDLINGFEE ?>"
			/>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTLHANDLINGFEE_TOOLTIP'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_MACHINABLE'); ?>
		</td>
		<td>
			<?php
			echo JHTML::_('redshopselect.booleanlist', 'USPS_MACHINABLE', array(), USPS_MACHINABLE);
			?>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_MACHINABLE_TOOLTIP'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_QUOTE'); ?>
		</td>
		<td>
			<?php
			echo JHTML::_('redshopselect.booleanlist', 'USPS_SHOW_DELIVERY_QUOTE', array(), USPS_SHOW_DELIVERY_QUOTE);
			?>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_QUOTE_TOOLTIP'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_REPORTERRORS'); ?>
		</td>
		<td>
			<?php
			echo JHTML::_('redshopselect.booleanlist', 'USPS_REPORTERRORS', array(), USPS_REPORTERRORS);
			?>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_REPORTERRORS_TOOLTIP'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_STANDARDSHIPPING'); ?>
		</td>
		<td>
			<?php
			echo JHTML::_('redshopselect.booleanlist', 'USPS_STANDARDSHIPPING', array(), USPS_STANDARDSHIPPING);
			?>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_STANDARDSHIPPING_TOOLTIP'); ?>
		</td>
	</tr>

	<tr>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PREFIX'); ?>

		</td>
		<td>
			<input type="text" name="USPS_PREFIX" class="form-control" value="<?php echo USPS_PREFIX ?>"/>
		</td>
		<td>
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PREFIX_TOOLTIP'); ?>
		</td>
	</tr>
</table>
<table cellpadding="30">
	<tr valign="top">
		<td>
			<table>
				<tr>
					<td colspan="3">
						<hr>
						<strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SHIP'); ?></strong>
						<hr>
					</td>
				</tr>
				<?php
				$i = 0;
				?>
				<?php while (defined("USPS_SHIP" . $i)) : ?>

					<?php
					$shipName = 'USPS_SHIP' . $i;
					?>
					<tr class="row<?php echo($i & 1); ?>">
						<td>
							<?php echo constant($shipName . '_TEXT'); ?>
						</td>
						<td>
							<?php
							echo JHtml::_(
								'redshopselect.booleanlist',
								$shipName,
								array(),
								constant($shipName)
							);
							?>
						</td>
					</tr>
					<?php $i++; ?>
				<?php endwhile; ?>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td colspan="3">
						<hr>
						<strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTL'); ?></strong>
						<hr>
					</td>
				</tr>
				<?php $i = 0; ?>

				<?php while (defined("USPS_INTL" . $i)) : ?>
					<?php
					$shipName = 'USPS_INTL' . $i;
					?>
					<tr class="row<?php echo($i & 1); ?>">
						<td>
							<?php echo constant($shipName . '_TEXT'); ?>
						</td>
						<td>
							<?php
							echo JHtml::_(
								'redshopselect.booleanlist',
								$shipName,
								array(),
								constant($shipName)
							);
							?>
						</td>
					</tr>
					<?php $i++; ?>
				<?php endwhile; ?>
			</table>
		</td>
	</tr>
</table>
