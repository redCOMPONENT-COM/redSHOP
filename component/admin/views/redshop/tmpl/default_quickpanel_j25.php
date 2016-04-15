<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$expand_all = EXPAND_ALL;
$uri = JURI::getInstance();
$url = $uri->root();
$filteroption = JRequest::getVar('filteroption');

$user = JFactory::getUser();
$usertype = array_keys($user->groups);
$user->usertype = $usertype[0];
$user->gid = $user->groups[$user->usertype];
$quicklink_icon = explode(",", QUICKLINK_ICON);
$new_arr = RedShopHelperImages::geticonarray();
?>
<script type="text/javascript">
	window.addEvent('domready', function () {

		var callList = {};
		var expand_all = <?php echo $expand_all; ?>;

		if (document.getElementById('newcustomericons')) callList.newcustomericons = new Fx.Slide('newcustomericons_content');
		if (document.getElementById('newestordericons')) callList.newestordericons = new Fx.Slide('newestordericons_content');
		if (document.getElementById('charticons')) callList.charticons =  new Fx.Slide('charticons_content');

		callList.quickicons = new Fx.Slide('quickicons_content');

		for (name in callList) {

			if(expand_all) {

				callList[name].show();

			} else {

				callList[name].hide();

			}

		}

		callList.popularicons = new Fx.Slide('popularicons_content');

		for (name in callList) {
			$(name).addEvent('click', function (event) {

				callList[(this.id)].toggle();

			});
		}

	});

	function divonoff(divname) {
		if (document.getElementById(divname).style.display == "block") {
			document.getElementById(divname).style.display = "none";

		} else {
			document.getElementById(divname).style.display = "block";
		}
	}
</script>
<?php
$selected = JRequest::getVar('filteroption');
if (isset($selected))
{
	$selected = 4;
}
else
{
	$selected = 0;
}

$title = JText::_('COM_REDSHOP_POPULAR');
?>
<table class="adminlist" id="popularicons" style="cursor: pointer;">
	<thead>
	<tr class="title">
		<td width="3%"><img src="<?php echo $url ?>media/system/images/arrow.png"></td>
		<td><?php echo $title;?></td>
	</tr>
	</thead>
</table>
<div id="popularicons_content">
	<table class="adminlist">
		<tr>
			<td valign="middle" width="20%"><strong><?php echo JText::_('COM_REDSHOP_VERSION');?></strong></td>
			<td valign="middle" width="80%"><?php echo $this->redshopversion;?></td>
		</tr>
		<tr>
			<td width="20%" align="center">
				<div id="cpanel" align="center">
					<?php
					$link = 'index.php?option=com_redshop&amp;wizard=1';
					redshopViewredshop::quickiconButton($link, 'wizard_48.png', JText::_('COM_REDSHOP_WIZARD'));



					?>
				</div>
			</td>
			<td width="25%" align="center">
				<div id="cpanel" align="center">
					<?php
					$link = 'index.php?option=com_redshop&view=configuration&dashboard=1';
					redshopViewredshop::quickiconButton($link, 'dashboard_48.png', JText::_('COM_REDSHOP_DASHBORAD_CONFIGURATION'));
					?>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php    $title = JText::_('COM_REDSHOP_QUICK_LINKS'); ?>
<table id="quickicons" style="cursor: pointer;" class="adminlist">

	<thead>
	<tr>
		<td bgcolor="white" colspan="2"></td>
	</tr>
	<tr class="title">
		<td width="3%"><img src="<?php echo $url ?>media/system/images/arrow.png"></td>
		<td><?php echo $title;?></td>
	</tr>
	</thead>
</table>
<div id="quickicons_content">
<table class="adminlist">
<tr>
<td>
<div id="cpanel">
	<?php

	$cnt_prod = 0;
	for ($i = 0; $i < count($new_arr['products']); $i++)
	{
		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{
			if (in_array($new_arr['products'][$i], $this->access_rslt) && in_array($new_arr['products'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['products'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['prodimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]));
				$cnt_prod = 1;
			}
		}
		else
		{
			if (in_array($new_arr['products'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['products'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['prodimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]));
				$cnt_prod = 1;

			}
		}
	}




	?>
</div>

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
						if (in_array($new_arr['orders'][$i], $this->access_rslt) && in_array($new_arr['orders'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
							$cnt_ord = 1;
						}
					}
					else
					{
						if (in_array($new_arr['orders'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
							$cnt_ord = 1;
						}
					}
				}
				break;
			default:
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['orders'][$i], $this->access_rslt) && in_array($new_arr['orders'][$i], $quicklink_icon))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
						$cnt_ord = 1;
					}
				}
				else
				{
					if (in_array($new_arr['orders'][$i], $quicklink_icon))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
						$cnt_ord = 1;

					}
				}
		}

	}

	?>
</div>

<div id="cpanel">
	<?php
	$cnt_dis = 0;
	for ($i = 0; $i < count($new_arr['discounts']); $i++)
	{
		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{
			if (in_array($new_arr['discounts'][$i], $this->access_rslt) && in_array($new_arr['discounts'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['discounts'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['discountimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i]));
				$cnt_dis = 1;
			}
		}
		else
		{
			if (in_array($new_arr['discounts'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['discounts'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['discountimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i]));
				$cnt_dis = 1;
			}
		}
	}

	?>
</div>

<div id="cpanel">
	<?php
	$cnt_com = 0;
	for ($i = 0; $i < count($new_arr['communications']); $i++)
	{
		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{
			if (in_array($new_arr['communications'][$i], $this->access_rslt) && in_array($new_arr['communications'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['communications'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['commimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i]));
				$cnt_com = 1;
			}
		}
		else
		{
			if (in_array($new_arr['communications'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['communications'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['commimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i]));
				$cnt_com = 1;

			}
		}
	}


	?>
</div>

<div id="cpanel">
	<?php
	$cnt_ship = 0;
	for ($i = 0; $i < count($new_arr['shippings']); $i++)
	{

		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{

			if (in_array($new_arr['shippings'][$i], $this->access_rslt) && in_array($new_arr['shippings'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['shippings'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['shippingimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['shippingtxt'][$i]));
				$cnt_ship = 1;
			}
		}
		else
		{

			if (in_array($new_arr['shippings'][$i], $quicklink_icon))
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
	}


	?>
</div>

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
						if (in_array($new_arr['users'][$i], $this->access_rslt) && in_array($new_arr['users'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
							$cnt_user = 1;
						}
					}
					else
					{
						if (in_array($new_arr['users'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
							$cnt_user = 1;
						}
					}
				}
				break;
			default:
				if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
				{
					if (in_array($new_arr['users'][$i], $this->access_rslt) && in_array($new_arr['users'][$i], $quicklink_icon))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
						$cnt_user = 1;
					}
				}
				else
				{
					if (in_array($new_arr['users'][$i], $quicklink_icon))
					{
						$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
						$cnt_user = 1;
					}
				}
		}
	}



	?>
</div>

<div id="cpanel">
	<?php
	$cnt_vat = 0;
	for ($i = 0; $i < count($new_arr['vats']); $i++)
	{
		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{
			if (in_array($new_arr['vats'][$i], $this->access_rslt) && in_array($new_arr['vats'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['vats'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['vatimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i]));
				$cnt_vat = 1;

			}
		}
		else
		{
			if (in_array($new_arr['vats'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['vats'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['vatimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i]));
				$cnt_vat = 1;
			}
		}
	}


	?>
</div>

<div id="cpanel">
	<?php
	$cnt_imp = 0;
	for ($i = 0; $i < count($new_arr['importexport']); $i++)
	{
		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{
			if (in_array($new_arr['importexport'][$i], $this->access_rslt) && in_array($new_arr['importexport'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['importexport'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['importimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i]));
				$cnt_imp = 1;
			}
		}
		else
		{
			if (in_array($new_arr['importexport'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['importexport'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['importimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i]));
				$cnt_imp = 1;
			}

		}

	}


	?>
</div>

<div id="cpanel">
	<?php
	$cnt_alt = 0;
	for ($i = 0; $i < count($new_arr['altration']); $i++)
	{
		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{
			if (in_array($new_arr['altration'][$i], $this->access_rslt) && in_array($new_arr['altration'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['altration'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['altrationimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i]));
				$cnt_alt = 1;

			}
		}
		else
		{

			if (in_array($new_arr['altration'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['altration'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['altrationimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i]));
				$cnt_alt = 1;
			}

		}
	}

	?>
</div>

<div id="cpanel">
	<?php
	$cnt_cust = 0;
	for ($i = 0; $i < count($new_arr['customerinput']); $i++)
	{
		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{
			if (in_array($new_arr['customerinput'][$i], $this->access_rslt) && in_array($new_arr['customerinput'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['customerinput'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['customerinputimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i]));
				$cnt_cust = 1;
			}
		}
		else
		{
			if (in_array($new_arr['customerinput'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['customerinput'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['customerinputimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i]));
				$cnt_cust = 1;
			}
		}
	}

	?>
</div>

<div id="cpanel">
	<?php

	for ($i = 0; $i < count($new_arr['accountings']); $i++)
	{
		if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
		{
			if (in_array($new_arr['accountings'][$i], $this->access_rslt) && in_array($new_arr['accountings'][$i], $quicklink_icon))
			{

				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['accountings'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['accimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i]));

			}
		}
		else
		{
			if (in_array($new_arr['accountings'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['accountings'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['accimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i]));
			}

		}
	}

	?>
</div>
</td>


</tr>
</table>
</div>

<?php
if (DISPLAY_NEW_CUSTOMERS)
{
	$title = JText::_('COM_REDSHOP_NEWEST_CUSTOMERS');
	?>
	<table id="newcustomericons" style="cursor: pointer;" class="adminlist">
		<thead>
		<tr>
			<td bgcolor="white" colspan="2"></td>
		</tr>
		<tr class="title">
			<td width="3%"><img src="<?php echo $url ?>media/system/images/arrow.png"></td>
			<td><?php echo $title;?></td>
		</tr>
		</thead>
	</table>
	<div id="newcustomericons_content">
		<table class="adminlist">

			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_NEWEST_CUSTOMERS');?></td>
			</tr>
			<tr>
				<td>
					<div id="cpanel">
						<?php  echo $this->loadTemplate('newest_customers');  ?>
					</div>
				</td>
			</tr>
		</table>
	</div>

<?php
}
if (DISPLAY_NEW_ORDERS)
{
	$title = JText::_('COM_REDSHOP_NEWEST_ORDERS'); ?>
	<table id="newestordericons" style="cursor: pointer;" class="adminlist">
		<thead>
		<tr>
			<td bgcolor="white" colspan="2"></td>
		</tr>
		<tr class="title">
			<td width="3%"><img src="<?php echo $url ?>media/system/images/arrow.png"></td>
			<td><?php echo $title;?></td>
		</tr>
		</thead>
	</table>
	<div id="newestordericons_content">
		<table class="adminlist">
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_NEWEST_ORDERS');?></td>
			</tr>
			<tr>
				<td>
					<div id="cpanel">
						<?php  echo $this->loadTemplate('newest_orders');  ?>
					</div>
				</td>
			</tr>
		</table>
	</div>

<?php
}
if (DISPLAY_STATISTIC)
{
	$title = JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES');
	?>
	<table id="charticons" style="cursor: pointer;" class="adminlist">
		<thead>
		<tr>
			<td bgcolor="white" colspan="2"></td>
		</tr>
		<tr class="title">
			<td width="3%"><img src="<?php echo $url ?>media/system/images/arrow.png"></td>
			<td><?php echo $title;?></td>
		</tr>
		</thead>
	</table>
	<div id="charticons_content">
		<table class="adminlist">
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES');?></td>
			</tr>
			<tr>
				<td>
					<div id="cpanel">
						<?php  echo $this->loadTemplate('sales_piechart');  ?>
					</div>
				</td>
			</tr>
		</table>
	</div>

<?php
}
?>
