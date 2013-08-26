<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('restricted access');
$objhelper     = new redhelper;
$producthelper = new producthelper;
$extraField    = new extraField;
$Itemid        = JRequest::getVar('Itemid');
$option        = JRequest::getVar('option');
$model         = $this->getModel('subscription');
$user          = & JFactory::getUser();
$user_id       = $user->id;
$today         = time();
$loadSubscriptionOverviewTemplate = $this->loadSubscriptionOverviewTemplate;

if (count($loadSubscriptionOverviewTemplate) > 0 && $loadSubscriptionOverviewTemplate[0]->template_desc != "")
{
	$template_desc = $loadSubscriptionOverviewTemplate[0]->template_desc;
}
else
{
	$template_desc = "";
}

$template_desc 		 = str_replace("{subscription_plan_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_PLAN_LABEL'), $template_desc);
$template_desc 		 = str_replace("{subscription_price_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_PRICE_LABEL'), $template_desc);
$template_desc 		 = str_replace("{subscription_duration_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_DURATION_LABEL'), $template_desc);
$template_desc 		 = str_replace("{subscription_download_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_DOWNLOAD_LABEL'), $template_desc);
$template_desc 		 = str_replace("{subscription_buy_now_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_BUY_NOW_LABEL'), $template_desc);

if (strstr($template_desc, "{subscription_loop_start}") && strstr($template_desc, "{subscription_loop_end}"))
{
	$subtemplate_desc    = explode('{subscription_loop_start}', $template_desc);
	$subheader           = $subtemplate_desc [0];
	$subtemplate_desc    = explode('{subscription_loop_end}', $subtemplate_desc [1]);
	$middletemplate_desc = $subtemplate_desc[0];
}

for ($i = 0; $i < count($this->detail); $i++)
{
	$row               = & $this->detail[$i];
	$data_add          = $middletemplate_desc;
	$data_add          = str_replace("{subscription_detail_name}", $row->product_name, $data_add);
	$product_price     = $model->checkDiscountProduct($row);

	if ($user_id > 0)
	{
		$product_price_temp = $model->getPriceProuctViaSubscription($user_id, $row->subscription_id);

		if ($product_price_temp > 0 )
		{
			$product_price = $product_price_temp;
		}
	}

	$product_price     = $producthelper->getProductFormattedPrice($product_price);
	$data_add          = str_replace("{subscription_detail_price}", $product_price, $data_add);
	$data_add          = str_replace("{subscription_detail_duration}", $row->subscription_period . " " . $row->subscription_period_unit, $data_add);
	$number_of_product = $model->getNumberOfProduct($row->subscription_applicable_products);
	$data_add          = str_replace("{subscription_detail_number_download}", $number_of_product, $data_add);

	//  Extra field display
	$extraFieldName    = $extraField->getSectionFieldNameArray(1, 1, 1);

	$data_add          = $producthelper->getExtraSectionTag($extraFieldName, $row->product_id, "1", $data_add);

	$cItemid           = $objhelper->getItemid($row->product_id);

	if ($cItemid != "")
	{
		$tmpItemid = $cItemid;
	}
	else
	{
		$tmpItemid = $Itemid;
	}

	$link_buy_now = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $row->product_id . '&Itemid=' . $tmpItemid);

	$user    = & JFactory::getUser();
	$user_id = $user->id;

	if ($user_id > 0)
	{
		$subscription_of_user = $model->checkUserIsSubscriberNotExpired($user_id);



		if (count($subscription_of_user) > 0)
		{
			$subs = "";

			for ($j = 0; $j < count($subscription_of_user); $j++)
			{
				$subs[] = $subscription_of_user[$j]->subscription_id;
			}

			if (count($subs) > 0)
			{
				$check_parent = $model->checkParentSubscription($row->subscription_id, $subs);
				$check_extend = $model->checkExtendSubscription($row->subscription_id, $subs);

				if ($check_extend)
				{
					$link_detail  = "<a href='$link_buy_now'>" . JText::_('COM_REDSHOP_SUBSCRIPTION_EXTEND_LABEL') . "</a>";
				}
				elseif ($check_parent)
				{
					$link_detail  = "<a href='$link_buy_now'>" . JText::_('COM_REDSHOP_SUBSCRIPTION_UPDATE_LABEL') . "</a>";
				}
				else
				{
					$link_detail  = "<a href='$link_buy_now'>" . JText::_('COM_REDSHOP_SUBSCRIPTION_BUY_NOW_LABEL') . "</a>";
				}
			}
			else
			{
				$link_detail  = "<a href='$link_buy_now'>" . JText::_('COM_REDSHOP_SUBSCRIPTION_BUY_NOW_LABEL') . "</a>";
			}
		}
		else
		{
			$link_detail  = "<a href='$link_buy_now'>" . JText::_('COM_REDSHOP_SUBSCRIPTION_BUY_NOW_LABEL') . "</a>";
		}
	}
	else
	{
		$link_detail  = "<a href='$link_buy_now'>" . JText::_('COM_REDSHOP_SUBSCRIPTION_BUY_NOW_LABEL') . "</a>";
	}

	$data_add     = str_replace("{subscription_detail_buy_now}", $link_detail, $data_add);
	$sub_detail  .= $data_add;
}

$template_desc = str_replace($middletemplate_desc, $sub_detail, $template_desc);
$template_desc = str_replace("{subscription_loop_start}", "", $template_desc);
$template_desc = str_replace("{subscription_loop_end}", "", $template_desc);

echo eval("?>" . $template_desc . "<?php ");
