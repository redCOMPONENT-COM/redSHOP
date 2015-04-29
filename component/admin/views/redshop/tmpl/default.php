<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JLoader::load('RedshopHelperAdminImages');
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		if (pressbutton == 'configuration') {
			var link = 'index.php?option=com_redshop&view=configuration';
			window.location.href = 'index.php?option=com_redshop&view=configuration';
		}

		if (pressbutton == 'remote_update') {
			window.location.href = 'index.php?option=com_redshop&view=zip_import&layout=confirmupdate';
		}

		if (pressbutton == 'wizard') {
			window.location.href = 'index.php?option=com_redshop&wizard=1';
		}

		if (pressbutton == 'statistic') {
			window.location.href = 'index.php?option=com_redshop&view=statistic';
		}

		if (pressbutton == 'update') {
			window.location.href = 'index.php?option=com_redshop&view=update';
		}
	}
</script>
<?php
$user           = JFactory::getUser();
$usertype       = array_keys($user->groups);
$user->usertype = $usertype[0];
$user->gid      = $user->groups[$user->usertype];
$quicklink_icon = explode(",", QUICKLINK_ICON);
$new_arr        = RedShopHelperImages::geticonarray();

?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td valign="top" width="50%">
<table class="adminlist">

<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php

			$cnt_prod = 0;
			for ($i = 0; $i < count($new_arr['products']); $i++)
			{
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['products'][$i], $this->access_rslt))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['products'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['prodimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]));
						$cnt_prod = 1;
					}
				}
				else
				{

					$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['products'][$i];
					redshopViewredshop::quickiconButton($link, $new_arr['prodimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]));
					$cnt_prod = 1;
				}
			}

			if ($cnt_prod == 0)
			{
				echo "You do not have access to this section";

			}


			?>
		</div>
	</td>
</tr>

<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_ORDER')?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php

			$cnt_ord = 0;



			for ($i = 0; $i < count($new_arr['orders']); $i++)
			{
				switch ($new_arr['orders'][$i])
				{
					case "stockroom":
						if (USE_STOCKROOM != 0)
						{
							if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
							{
								if (in_array($new_arr['orders'][$i], $this->access_rslt))
								{
									$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i];
									redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
									$cnt_ord = 1;
								}
							}
							else
							{
								$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
								$cnt_ord = 1;
							}
						}
						break;
					default:
						if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
						{
							if (in_array($new_arr['orders'][$i], $this->access_rslt))
							{
								$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
								$cnt_ord = 1;
							}
						}
						else
						{
							$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
							$cnt_ord = 1;
						}
				}

			}

			$link = 'index.php?option=com_plugins&filter_folder=redshop_payment';
			redshopViewredshop::quickiconButton($link, 'payment48.png', JText::_('COM_REDSHOP_PAYMENT'));
			$cnt_ord = 1;
			if ($cnt_ord == 0)
			{
				echo "You do not have access to this section";

			}
			?>
		</div>
	</td>
</tr>


<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_DISCOUNT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			$cnt_dis = 0;
			for ($i = 0; $i < count($new_arr['discounts']); $i++)
			{
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['discounts'][$i], $this->access_rslt))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['discounts'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['discountimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i]));
						$cnt_dis = 1;
					}
				}
				else
				{
					$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['discounts'][$i];
					redshopViewredshop::quickiconButton($link, $new_arr['discountimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i]));
					$cnt_dis = 1;
				}
			}
			if ($cnt_dis == 0)
			{
				echo "You do not have access to this section";

			}
			?>
		</div>
	</td>
</tr>
<tr>
	<td class="distitle"> <?php echo JText::_('COM_REDSHOP_COMMUNICATION');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			$cnt_com = 0;
			for ($i = 0; $i < count($new_arr['communications']); $i++)
			{
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['communications'][$i], $this->access_rslt))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['communications'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['commimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i]));
						$cnt_com = 1;
					}
				}
				else
				{
					$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['communications'][$i];
					redshopViewredshop::quickiconButton($link, $new_arr['commimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i]));
					$cnt_com = 1;
				}
			}
			if ($cnt_com == 0)
			{
				echo "You do not have access to this section";

			}

			?>
		</div>
	</td>
</tr>
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_SHIPPING');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			$cnt_ship = 0;
			for ($i = 0; $i < count($new_arr['shippings']); $i++)
			{
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['shippings'][$i], $this->access_rslt))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['shippings'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['shippingimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['shippingtxt'][$i]));
						$cnt_ship = 1;
					}
				}
				else
				{
					if ($new_arr['shippings'][$i] == 'shipping_detail')
					{
						$link = 'index.php?option=com_installer';
						redshopViewredshop::quickiconButton($link, $new_arr['shippingimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['shippingtxt'][$i]));
						$cnt_ship = 1;
					}
					else
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['shippings'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['shippingimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['shippingtxt'][$i]));
						$cnt_ship = 1;
					}
				}
			}
			if ($cnt_ship == 0)
			{
				echo "You do not have access to this section";
			}

			?>
		</div>
	</td>
</tr>
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_USER');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			$cnt_user = 0;
			for ($i = 0; $i < count($new_arr['users']); $i++)
			{
				switch ($new_arr['users'][$i])
				{

					case "accessmanager":
						if (ENABLE_BACKENDACCESS != 0)
						{

							if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
							{
								if (in_array($new_arr['users'][$i], $this->access_rslt))
								{
									$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i];
									redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
									$cnt_user = 1;
								}
							}
							else
							{
								$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
								$cnt_user = 1;
							}
						}
						break;
					default:
						if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
						{
							if (in_array($new_arr['users'][$i], $this->access_rslt))
							{
								$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
								$cnt_user = 1;
							}
						}
						else
						{
							$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
							$cnt_user = 1;
						}
				}
			}

			if ($cnt_user == 0)
			{
				echo "You do not have access to this section";

			}

			?>
		</div>
	</td>
</tr>
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_VAT_AND_CURRENCY');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			$cnt_vat = 0;
			for ($i = 0; $i < count($new_arr['vats']); $i++)
			{
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['vats'][$i], $this->access_rslt))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['vats'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['vatimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i]));
						$cnt_vat = 1;

					}
				}
				else
				{
					$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['vats'][$i];
					redshopViewredshop::quickiconButton($link, $new_arr['vatimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i]));
					$cnt_vat = 1;
				}
			}
			if ($cnt_vat == 0)
			{
				echo "You do not have access to this section";

			}

			?>
		</div>
	</td>
</tr>

<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_IMPORT_EXPORT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			$cnt_imp = 0;
			for ($i = 0; $i < count($new_arr['importexport']); $i++)
			{
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['importexport'][$i], $this->access_rslt))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['importexport'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['importimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i]));
						$cnt_imp = 1;
					}
				}
				else
				{
					$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['importexport'][$i];
					redshopViewredshop::quickiconButton($link, $new_arr['importimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i]));
					$cnt_imp = 1;

				}

			}
			if ($cnt_imp == 0)
			{
				echo "You do not have access to this section";

			}

			?>
		</div>
	</td>
</tr>

<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_CUSTOMIZATION');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			$cnt_alt = 0;
			for ($i = 0; $i < count($new_arr['altration']); $i++)
			{
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['altration'][$i], $this->access_rslt))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['altration'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['altrationimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i]));
						$cnt_alt = 1;

					}
				}
				else
				{
					$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['altration'][$i];
					redshopViewredshop::quickiconButton($link, $new_arr['altrationimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i]));
					$cnt_alt = 1;

				}
			}
			if ($cnt_alt == 0)
			{
				echo "You do not have access to this section";

			}
			?>
		</div>
	</td>
</tr>


<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_CUSTOMER_INPUT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			$cnt_cust = 0;
			for ($i = 0; $i < count($new_arr['customerinput']); $i++)
			{
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['customerinput'][$i], $this->access_rslt))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['customerinput'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['customerinputimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i]));
						$cnt_cust = 1;
					}
				}
				else
				{
					$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['customerinput'][$i];
					redshopViewredshop::quickiconButton($link, $new_arr['customerinputimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i]));
					$cnt_cust = 1;
				}
			}
			if ($cnt_cust == 0)
			{
				echo "You do not have access to this section";

			}

			?>
		</div>
	</td>
</tr>
<?php if (ECONOMIC_INTEGRATION != 0)
{
	?>

	<tr>
		<td class="distitle"><?php echo JText::_('COM_REDSHOP_ACCOUNTING');?></td>
	</tr>
	<tr>
		<td>
			<div id="cpanel">
				<?php

				for ($i = 0; $i < count($new_arr['accountings']); $i++)
				{
					if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
					{
						if (in_array($new_arr['accountings'][$i], $this->access_rslt))
						{

							$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['accountings'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['accimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i]));

						}
					}
					else
					{

						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['accountings'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['accimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i]));

					}
				}

				?>
			</div>
		</td>
	</tr>
<?php } ?>

</table>
</td>
<td valign="top" width="50%">
	<?php if (version_compare(JVERSION, '3.0', '>=')) :?>
		<?php  echo $this->loadTemplate('quickpanel');  ?>
	<?php else: ?>
		<?php  echo $this->loadTemplate('quickpanel_j25');  ?>
	<?php endif; ?>
</td>
</tr>
</table>
