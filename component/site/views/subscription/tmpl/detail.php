<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('restricted access');
$objhelper           = new redhelper;
$producthelper       = new producthelper;
$Itemid              = JRequest::getVar('Itemid');
$option              = JRequest::getVar('option');
$model               = $this->getModel('subscription');
$user                = & JFactory::getUser();
$user_id             = $user->id;

if ($user_id > 0)
{
	// Check and Execute query update informations about subscription
	$new_user_subscriber = $model->checkNewUserIsSubscriber($user_id);

	// Check user is a subscriber
	$user_is_subscriber  = $model->checkUserIsSubscriber($user_id);

	if ( count($user_is_subscriber) > 0)
	{
		$loadSubscriptionDetailTemplate = $this->loadSubscriptionDetailTemplate;

		if (count($loadSubscriptionDetailTemplate) > 0 && $loadSubscriptionDetailTemplate[0]->template_desc != "")
		{
			$template_desc = $loadSubscriptionDetailTemplate[0]->template_desc;
		}
		else
		{
			$template_desc = "";
		}

		$template_desc 		 	= str_replace("{subscription_information_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_INFORMATION_LABEL'), $template_desc);

		if (strstr($template_desc, "{subscription_plan_loop_start}") && strstr($template_desc, "{subscription_plan_loop_end}"))
		{
			$subtemplate_desc    = explode('{subscription_plan_loop_start}', $template_desc);
			$subheader           = $subtemplate_desc [0];
			$subtemplate_desc    = explode('{subscription_plan_loop_end}', $subtemplate_desc [1]);
			$middletemplate_desc = $subtemplate_desc[0];
		}

		$sub_detail = "";
		$data_detail = $model->getDataDetail($user_id);

		for ($i = 0; $i < count($data_detail); $i++)
		{
			$row        = $data_detail[$i];
			$data_add   = $middletemplate_desc;
			$data_add   = str_replace("{subscription_name_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_NAME_LABEL'), $data_add);
			$data_add   = str_replace("{subscription_name}", $row->product_name, $data_add);
			$data_add   = str_replace("{subcription_lifeline_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_LIFELINE_LABEL'), $data_add);

			// Begin create bar lifeline
			$start_date = $row->create_date_subscription;
			$end_date   = $row->end_date_subscription;
			$today      = time();
			$time_use   = ceil(($today - $start_date) / 86400);
			$time_ex    = $end_date - $today;
			$time_life  = ceil($time_ex / 86400);
			$total_day  = ($end_date - $start_date);
			$percent    = ($time_ex / $total_day ) * 100;
			$result     = 100 - $percent;

			if ($result > 100 || $result < 0)
			{
				$result = 100;
			}

			$subscription_bar_lifeline = "<div class='subscription_progress'>";

			if ($result > 0 && $result <= 16.67)
			{
				$subscription_bar_lifeline .= "<div class='subscription_progress_start' style='width: " . $result . "%' ></div>";
			}
			elseif ($result > 16.67 && $result <= 91.67)
			{
				$subscription_bar_lifeline .= "<div class='subscription_progress_mid' style='width: " . $result . "%' ></div>";
			}
			elseif ($result > 91.67 && $result <= 100)
			{
				$subscription_bar_lifeline .= "<div class='subscription_progress_end' style='width: " . $result . "%' ></div>";
			}

			$subscription_bar_lifeline .= "</div>";

			// End create bar lifeline

			$data_add      = str_replace("{subscription_bar_lifeline}", $subscription_bar_lifeline, $data_add);

			if ($end_date > $today)
			{
				$subscription_notice     = "<div>" . JText::_('COM_REDSHOP_SUBSCRIPTION_ACOOUNT_SUBSCRIPTION_WILL') . $time_life . " " . JText::_('COM_REDSHOP_SUBSCRIPTION_ACOOUNT_SUBSCRIPTION_DAYS') . ", " . date("F d,Y - h:i", $end_date) . "</div>";
				$subscription_extend_lbl = JText::_('COM_REDSHOP_SUBSCRIPTION_EXTEND_LABEL');
			}
			else
			{
				$subscription_notice     = "<div>" . JText::_('COM_REDSHOP_SUBSCRIPTION_EXPIRED') . "</div>";
				$subscription_extend_lbl = JText::_('COM_REDSHOP_SUBSCRIPTION_RENEW_LABEL');
			}

			$data_add = str_replace("{subscription_notice}", $subscription_notice, $data_add);
			$cItemid  = $objhelper->getItemid($row->product_id);

			if ($cItemid != "")
			{
				$tmpItemid = $cItemid;
			}
			else
			{
				$tmpItemid = $Itemid;
			}

			$link_detail                 = JRoute::_('index.php?option=' . $option . '&view=subscription&layout=download&Itemid=' . $tmpItemid);
			$link_download               = "<a href='$link_detail'>" . JText::_('COM_REDSHOP_SUBSCRIPTION_LINK_DOWNLOAD') . "</a>";
			$data_add                    = str_replace("{subscription_link_download}", $link_download, $data_add);
			$list_downgrade_subscription = $model->getListDowngradeSubscription($row->subscription_id);
			$list_extend_subscription    = $model->getListExtendSubscription($row->subscription_id);
			$list_update_subscription    = $model->getListUpdateSubscription($row->subscription_id);

			$flag1 = 0;
			$flag2 = 0;
			$flag3 = 0;

			// List product downgrade
			if (count($list_downgrade_subscription) > 0)
			{
				$subscription_downgrade = "";

				for ($m = 0; $m < count($list_downgrade_subscription); $m++)
				{
					$row1                   = $list_downgrade_subscription[$m];
					$subscription_downgrade .= "<div>";
					$cItemid1  = $objhelper->getItemid($row1->product_id);

					if ($cItemid1 != "")
					{
						$tmpItemid1 = $cItemid1;
					}
					else
					{
						$tmpItemid1 = $Itemid;
					}

					$link_detail1            = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $row1->product_id . '&Itemid=' . $tmpItemid1);
					$link_product1           = "<a href='$link_detail1'>" . $row1->product_name . "</a>";
					$subscription_downgrade .= $link_product1;
				}

				$subscription_downgrade 	.= "</div>";
			}
			else
			{
				$flag1 = 1;
			}

			// List product extend
			if (count($list_extend_subscription) > 0)
			{
				$subscription_extend = "";

				for ($n = 0; $n < count($list_extend_subscription); $n++)
				{
					$row2                = $list_extend_subscription[$n];
					$subscription_extend .= "<div>";
					$cItemid2            = $objhelper->getItemid($row2->product_id);

					if ($cItemid2 != "")
					{
						$tmpItemid2 = $cItemid2;
					}
					else
					{
						$tmpItemid2 = $Itemid;
					}

					$link_detail2         = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $row2->product_id . '&Itemid=' . $tmpItemid2);
					$link_product2        = "<a href='$link_detail2'>" . $row2->product_name . "</a>";
					$subscription_extend .= $link_product2;
					$subscription_extend .= "</div>";
				}
			}
			else
			{
				$flag2 = 1;
			}

			// List product update
			if (count($list_update_subscription) > 0)
			{
				$subscription_update = "";

				for ($p = 0; $p < count($list_update_subscription); $p++)
				{
					$row3                = $list_update_subscription[$p];
					$subscription_update .= "<div>";
					$cItemid3            = $objhelper->getItemid($row3->product_id);

					if ($cItemid3 != "")
					{
						$tmpItemid3 = $cItemid3;
					}
					else
					{
						$tmpItemid3 = $Itemid;
					}

					$link_detail3         = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $row3->product_id . '&Itemid=' . $tmpItemid3);
					$link_product3        = "<a href='$link_detail3'>" . $row3->product_name . "</a>";
					$subscription_update .= $link_product3;
				}

				$subscription_update .= "</div>";
			}
			else
			{
				$flag3 = 1;
			}


			if ($end_date < $today)
			{
				// Check subscription has lowest level
				if ($flag1 > 0)
				{
					$data_add                  	 = str_replace("{subscription_downgrade_lbl}", "", $data_add);
					$data_add                    = str_replace("{subscription_downgrade}", "", $data_add);
				}
				else
				{
					$data_add                    = str_replace("{subscription_downgrade_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION_DOWNGRADE_LABEL'), $data_add);
					$data_add                    = str_replace("{subscription_downgrade}", $subscription_downgrade, $data_add);
				}

				$data_add                   	 = str_replace("{subscription_extend_lbl}", $subscription_extend_lbl, $data_add);
				$data_add                   	 = str_replace("{subscription_extend}", $subscription_extend, $data_add);

				// Check subscription has highest level

				if ($flag3 > 0)
				{
					$data_add                    = str_replace("{subscription_update_lbl}", "", $data_add);
					$data_add                    = str_replace("{subscription_update}", "", $data_add);
				}
				else
				{
					$data_add                    = str_replace("{subscription_update_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION_UPDATE_LABEL'), $data_add);
					$data_add                    = str_replace("{subscription_update}", $subscription_update, $data_add);
				}
			}
			else
			{
				$data_add                   	 = str_replace("{subscription_downgrade_lbl}", "", $data_add);
				$data_add                    	 = str_replace("{subscription_downgrade}", "", $data_add);

				if ($flag2 > 0)
				{
					$data_add                    = str_replace("{subscription_extend_lbl}", "", $data_add);
					$data_add                    = str_replace("{subscription_extend}", "", $data_add);
				}
				else
				{
					$data_add                    = str_replace("{subscription_extend_lbl}", $subscription_extend_lbl, $data_add);
					$data_add                    = str_replace("{subscription_extend}", $subscription_extend, $data_add);
				}

				if ($flag3 > 0)
				{
					$data_add                    = str_replace("{subscription_update_lbl}", "", $data_add);
					$data_add                    = str_replace("{subscription_update}", "", $data_add);
				}
				else
				{
					$data_add                    = str_replace("{subscription_update_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION_UPDATE_LABEL'), $data_add);
					$data_add                    = str_replace("{subscription_update}", $subscription_update, $data_add);
				}
			}

			$sub_detail   .= $data_add;
		}

		$template_desc = str_replace($middletemplate_desc, $sub_detail, $template_desc);
		$template_desc = str_replace("{subscription_plan_loop_start}", "", $template_desc);
		$template_desc = str_replace("{subscription_plan_loop_end}", "", $template_desc);
		echo eval("?>" . $template_desc . "<?php ");
	}
	else
	{
		echo "<div>" . JText::_('COM_REDSHOP_SUBSCRIPTION_NOTICE_NO_HAVE_SUBSCRIPTION') . "</div>";
	}
}
else
{
	echo "<div>" . JText::_('COM_REDSHOP_ALERTNOTAUTH_ACCOUNT') . "</div>";
	$link_login        = JRoute::_('index.php?option=' . $option . '&view=login&Itemid=' . $Itemid);
	$link_login_detail = "<a href='$link_login'>" . JText::_('COM_REDSHOP_LOGIN') . "</a>";
	echo "<div>" . $link_login_detail . "</div>";
}






