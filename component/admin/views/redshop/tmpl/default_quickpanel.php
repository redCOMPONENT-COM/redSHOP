<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<div class="row">
		<div class="alert alert-info">
			<small><?php echo JText::_('COM_REDSHOP_VERSION');?></small>
			<span class="label label-info"><?php echo $this->redshopversion;?></span>
		</div>

		<div class="well">
			<h2 class="module-title nav-header">
				<?php echo JText::_('COM_REDSHOP_POPULAR');?>
			</h2>
			<div class="row-fluid">
				<div id="cpanel" align="center">
				<?php
				$link = 'index.php?option=com_redshop&amp;wizard=1';
				redshopViewredshop::quickiconButton($link, 'wizard_48.png', JText::_('COM_REDSHOP_WIZARD'));
				?>
			</div>
			<div id="cpanel" align="center">
					<?php
					$link = 'index.php?option=com_redshop&view=configuration&dashboard=1';
					redshopViewredshop::quickiconButton($link, 'dashboard_48.png', JText::_('COM_REDSHOP_DASHBORAD_CONFIGURATION'));
					?>
				</div>
			</div>
		</div>
	<!-- </div>
	<div class="row-fluid"> -->
		<div class="well">
			<h2 class="module-title nav-header">
				<?php echo JText::_('COM_REDSHOP_QUICK_LINKS');?>
			</h2>
			<div class="row-fluid">
				<div id="cpanel">
					<?php

					$user           = JFactory::getUser();
					$usertype       = array_keys($user->groups);
					$user->usertype = $usertype[0];
					$user->gid      = $user->groups[$user->usertype];
					$quicklink_icon = explode(",", QUICKLINK_ICON);
					$new_arr        = RedShopHelperImages::geticonarray();

					for ($i = 0; $i < count($new_arr['products']); $i++)
					{
						if ($user->gid != 8 && ENABLE_BACKENDACCESS != 0)
						{
							if (in_array($new_arr['products'][$i], $this->access_rslt) && in_array($new_arr['products'][$i], $quicklink_icon))
							{
								$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['products'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['prodimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]));
							}
						}
						else
						{
							if (in_array($new_arr['products'][$i], $quicklink_icon))
							{
								$link = 'index.php?option=com_redshop&amp;view=' . $new_arr['products'][$i];
								redshopViewredshop::quickiconButton($link, $new_arr['prodimages'][$i], JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]));
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
			</div>
		</div>

	<?php if (DISPLAY_NEW_CUSTOMERS): ?>
		<div class="well">
			<h2 class="module-title nav-header">
				<?php echo JText::_('COM_REDSHOP_NEWEST_CUSTOMERS');?>
			</h2>
			<div class="row-fluid">
				<?php  echo $this->loadTemplate('newest_customers');  ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if (DISPLAY_NEW_ORDERS): ?>
		<div class="well">
			<h2 class="module-title nav-header">
				<?php echo JText::_('COM_REDSHOP_NEWEST_ORDERS');?>
			</h2>
			<div class="row-fluid">
				<?php  echo $this->loadTemplate('newest_orders');  ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if (DISPLAY_STATISTIC): ?>
		<div class="well">
			<h2 class="module-title nav-header">
				<?php echo JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES');?>
			</h2>
			<div class="row-fluid">
				<?php  echo $this->loadTemplate('sales_piechart');  ?>
			</div>
		</div>
	<?php endif; ?>

	</div>
