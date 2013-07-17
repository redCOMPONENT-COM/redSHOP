<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('restricted access');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/images.php';

$expand_all = EXPAND_ALL;
$uri = JURI::getInstance();
$url = $uri->root();
$filteroption = JRequest::getVar('filteroption');
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		if (pressbutton == 'configuration') {
			var link = 'index.php?option=com_redshop&view=configuration';
			window.location.href = link;
		}

		if (pressbutton == 'remote_update') {
			var link = 'index.php?option=com_redshop&view=zip_import&layout=confirmupdate';
			window.location.href = link;
		}

		if (pressbutton == 'wizard') {
			var link = 'index.php?option=com_redshop&wizard=1';
			window.location.href = link;
		}

		if (pressbutton == 'statistic') {
			var link = 'index.php?option=com_redshop&view=statistic';
			window.location.href = link;
		}
	}

	window.addEvent('domready', function () {

		var myPopularIcon = new Fx.Slide('popularicons_content');
		var myquickicons = new Fx.Slide('quickicons_content');

		if (document.getElementById('newcustomericons')) var mynewcustomericons = new Fx.Slide('newcustomericons_content');
		if (document.getElementById('newestordericons')) var mynewestordericons = new Fx.Slide('newestordericons_content');
		if (document.getElementById('charticons')) var mycharticons = new Fx.Slide('charticons_content');


		<?php if($expand_all==1) {?>

		myPopularIcon.show();
		myquickicons.show();
		if (document.getElementById('newcustomericons')) mynewcustomericons.show();
		if (document.getElementById('newestordericons')) mynewestordericons.show();
		if (document.getElementById('charticons')) mycharticons.show();


		<?php } else {?>

		myPopularIcon.hide();
		myquickicons.hide();
		if (document.getElementById('newcustomericons')) mynewcustomericons.hide();
		if (document.getElementById('newestordericons')) mynewestordericons.hide();
		if (document.getElementById('charticons')) mycharticons.hide();

		<?php } ?>


		$('popularicons').addEvent('click', function (event) {

			myPopularIcon.toggle();

		});


		$('quickicons').addEvent('click', function (event) {

			myquickicons.toggle();

		});

		if (document.getElementById('newcustomericons')) {
			$('newcustomericons').addEvent('click', function (event) {

				mynewcustomericons.toggle();

			});
		}

		if (document.getElementById('newestordericons')) {
			$('newestordericons').addEvent('click', function (event) {

				mynewestordericons.toggle();

			});
		}

		if (document.getElementById('charticons')) {
			$('charticons').addEvent('click', function (event) {

				mycharticons.toggle();

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
jimport('joomla.html.pane');
$user = JFactory::getUser();
$usertype = array_keys($user->groups);
$user->usertype = $usertype[0];
$user->gid = $user->groups[$user->usertype];
$quicklink_icon = explode(",", QUICKLINK_ICON);
$new_arr = RedShopHelperImages::geticonarray();
$option = JRequest::getCmd('option');



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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['products'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['prodimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]));
						$cnt_prod = 1;
					}
				}
				else
				{

					$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['products'][$i];
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

					case "container":
						if (USE_CONTAINER != 0)
						{

							if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
							{
								if (in_array($new_arr['orders'][$i], $this->access_rslt))
								{
									$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
									redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
									$cnt_ord = 1;
								}
							}
							else
							{
								$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
								$cnt_ord = 1;
							}
						}
						break;
					case "stockroom":
						if (USE_STOCKROOM != 0)
						{

							if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
							{
								if (in_array($new_arr['orders'][$i], $this->access_rslt))
								{
									$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
									redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
									$cnt_ord = 1;
								}
							}
							else
							{
								$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
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
								$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
								$cnt_ord = 1;
							}
						}
						else
						{
							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['discounts'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['discountimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i]));
						$cnt_dis = 1;
					}
				}
				else
				{
					$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['discounts'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['communications'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['commimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i]));
						$cnt_com = 1;
					}
				}
				else
				{
					$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['communications'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['shippings'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['shippings'][$i];
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
									$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['users'][$i];
									redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
									$cnt_user = 1;
								}
							}
							else
							{
								$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['users'][$i];
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
								$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['users'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
								$cnt_user = 1;
							}
						}
						else
						{
							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['users'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['vats'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['vatimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i]));
						$cnt_vat = 1;

					}
				}
				else
				{
					$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['vats'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['importexport'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['importimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i]));
						$cnt_imp = 1;
					}
				}
				else
				{
					$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['importexport'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['altration'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['altrationimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i]));
						$cnt_alt = 1;

					}
				}
				else
				{
					$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['altration'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['customerinput'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['customerinputimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i]));
						$cnt_cust = 1;
					}
				}
				else
				{
					$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['customerinput'][$i];
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

							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['accountings'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['accimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i]));

						}
					}
					else
					{

						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['accountings'][$i];
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
<td>&nbsp;</td>
<td valign="top">

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
//  $pane = @JPane::getInstance('sliders',array('startOffset'=>$selected));
//	echo $pane->startPane( 'stat-pane' );
$title = JText::_('COM_REDSHOP_POPULAR');
//echo $pane->startPanel( $title, 'POPULAR' );
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
					$link = 'index.php?option=' . $option . '&amp;wizard=1';
					redshopViewredshop::quickiconButton($link, 'wizard_48.png', JText::_('COM_REDSHOP_WIZARD'));



					?>
				</div>
			</td>
			<td width="25%" align="center">
				<div id="cpanel" align="center">
					<?php
					$link = 'index.php?option=' . $option . '&view=configuration&dashboard=1';
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
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['products'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['prodimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]));
				$cnt_prod = 1;
			}
		}
		else
		{
			if (in_array($new_arr['products'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['products'][$i];
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

			case "container":
				if (USE_CONTAINER != 0)
				{

					if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
					{
						if (in_array($new_arr['orders'][$i], $this->access_rslt) && in_array($new_arr['orders'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
							$cnt_ord = 1;
						}
					}
					else
					{
						if (in_array($new_arr['orders'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
							$cnt_ord = 1;
						}
					}
				}
				break;
			case "stockroom":
				if (USE_STOCKROOM != 0)
				{

					if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
					{
						if (in_array($new_arr['orders'][$i], $this->access_rslt) && in_array($new_arr['orders'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
							$cnt_ord = 1;
						}
					}
					else
					{
						if (in_array($new_arr['orders'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['orderimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]));
						$cnt_ord = 1;
					}
				}
				else
				{
					if (in_array($new_arr['orders'][$i], $quicklink_icon))
					{
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['orders'][$i];
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
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['discounts'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['discountimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i]));
				$cnt_dis = 1;
			}
		}
		else
		{
			if (in_array($new_arr['discounts'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['discounts'][$i];
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
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['communications'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['commimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i]));
				$cnt_com = 1;
			}
		}
		else
		{
			if (in_array($new_arr['communications'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['communications'][$i];
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
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['shippings'][$i];
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
					$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['shippings'][$i];
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
							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['users'][$i];
							redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
							$cnt_user = 1;
						}
					}
					else
					{
						if (in_array($new_arr['users'][$i], $quicklink_icon))
						{
							$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['users'][$i];
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
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['users'][$i];
						redshopViewredshop::quickiconButton($link, $new_arr['userimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]));
						$cnt_user = 1;
					}
				}
				else
				{
					if (in_array($new_arr['users'][$i], $quicklink_icon))
					{
						$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['users'][$i];
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
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['vats'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['vatimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i]));
				$cnt_vat = 1;

			}
		}
		else
		{
			if (in_array($new_arr['vats'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['vats'][$i];
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
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['importexport'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['importimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i]));
				$cnt_imp = 1;
			}
		}
		else
		{
			if (in_array($new_arr['importexport'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['importexport'][$i];
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
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['altration'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['altrationimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i]));
				$cnt_alt = 1;

			}
		}
		else
		{

			if (in_array($new_arr['altration'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['altration'][$i];
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
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['customerinput'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['customerinputimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i]));
				$cnt_cust = 1;
			}
		}
		else
		{
			if (in_array($new_arr['customerinput'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['customerinput'][$i];
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

				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['accountings'][$i];
				redshopViewredshop::quickiconButton($link, $new_arr['accimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i]));

			}
		}
		else
		{
			if (in_array($new_arr['accountings'][$i], $quicklink_icon))
			{
				$link = 'index.php?option=' . $option . '&amp;view=' . $new_arr['accountings'][$i];
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
</td>
</tr>
</table>
