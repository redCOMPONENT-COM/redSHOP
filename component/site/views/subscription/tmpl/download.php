<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('restricted access');
$url           = JURI::base();
$objhelper     = new redhelper;
$producthelper = new producthelper;
$Itemid        = JRequest::getVar('Itemid');
$option        = JRequest::getVar('option');
$model         = $this->getModel('subscription');
$user          = & JFactory::getUser();
$user_id       = $user->id;

if ($user_id > 0)
{
	// Check and Execute query update informations about subscription
	$new_user_subscriber            = $model->checkNewUserIsSubscriber($user_id);

	// Check user is a subscriber not expired
	$user_is_subscriber_not_expired = $model->checkUserIsSubscriberNotExpired($user_id);

	if (count($user_is_subscriber_not_expired) > 0 )
	{
		$loadSubscriptionDownloadTemplate = $this->loadSubscriptionDownloadTemplate;

		if (count($loadSubscriptionDownloadTemplate) > 0 && $loadSubscriptionDownloadTemplate[0]->template_desc != "")
		{
			$template_desc = $loadSubscriptionDownloadTemplate[0]->template_desc;
		}
		else
		{
			$template_desc = "";
		}

		$template_desc          = str_replace("{subscription_download_info}", JText::_('COM_REDSHOP_SUBSCRIPTION_DOWNLOAD_INFO'), $template_desc);

		$data_data_subscription = $model->getDataSubscriptionDownload($user_id);

		if (strstr($template_desc, "{subscription_loop_start}") && strstr($template_desc, "{subscription_loop_end}"))
		{
			$subtemplate_desc    = explode('{subscription_loop_start}', $template_desc);
			$subheader           = $subtemplate_desc [0];
			$subtemplate_desc    = explode('{subscription_loop_end}', $subtemplate_desc [1]);
			$middletemplate_desc = $subtemplate_desc[0];
		}

		$sub_detail = "";

		for ( $i = 0; $i < count($data_data_subscription); $i++)
		{
			$row           = $data_data_subscription[$i];
			$data_add      = $middletemplate_desc;
			$data_add      = str_replace("{subscription_name}", $row->subscription_name, $data_add);
			$data_add      = str_replace("{product_name_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_DOWNLOAD_PRODUCT_NAME'), $data_add);
			$data_add      = str_replace("{product_price_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_DOWNLOAD_PRODUCT_PRICE'), $data_add);
			$data_add      = str_replace("{product_select_file_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_DOWNLOAD_SELECT_FILE'), $data_add);
			$data_add      = str_replace("{product_download_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_DOWNLOAD_PRODUCT_DOWNLOAD'), $data_add);

			if (strstr($middletemplate_desc, "{product_loop_start}") && strstr($middletemplate_desc, "{product_loop_end}"))
			{
				$protemplate_desc    	= explode('{product_loop_start}', $middletemplate_desc);
				$proheader           	= $protemplate_desc [0];
				$protemplate_desc    	= explode('{product_loop_end}', $protemplate_desc [1]);
				$middleprotemplate_desc = $protemplate_desc[0];
			}

			$pro_detail           = "";
			$data_detail_download = $model->getDataDetailDownload($data_data_subscription[$i]->subscription_id);

			for ($j = 0; $j < count($data_detail_download); $j++)
			{
				$data_add_pro      = $middleprotemplate_desc;
				$row_ex            = $data_detail_download[$j];
				$product_price     = $producthelper->getProductFormattedPrice($row_ex->product_price);
				$data_add_pro      = str_replace("{product_price}", $product_price, $data_add_pro);
				$data_add_pro      = str_replace("{product_name}", $row_ex->product_name, $data_add_pro);
				$product_filenames = $producthelper->getAdditionMediaImage($row_ex->product_id, 'product', 'download');
				krsort($product_filenames);
				$select_file_download = "<select id='selectedfile_" . $row_ex->product_id . "' name='selected_file[" . $row_ex->product_id . "]' >";
				$media_id = 0;

				foreach ($product_filenames as $key)
				{
					if ($media_id == 0)
					{
						$media_id = $key->media_id;
					}

					$product_filename = end(explode('/', $key->media_name));
					$product_filename = explode('_', $product_filename);
					unset($product_filename[0]);
					$product_filename = implode('_', $product_filename);
					$select_file_download .= "<option value='$key->media_id'>" . $product_filename . "</option>";
				}

				$select_file_download .= "</select>";
				$data_add_pro = str_replace("{product_select_file}", $select_file_download, $data_add_pro);
				$link_download = JRoute::_('index.php?option=' . $option . '&view=subscription&task=download&id=' . $row_ex->product_id . '&media_id=' . $media_id . '&Itemid=' . $Itemid);
				$product_download = '<a href="' . $link_download . '"><img src="' . $url . 'components/com_redshop/assets/images/download.png" alt="checkmark"></a>';
				$data_add_pro = str_replace("{product_download}", $product_download, $data_add_pro);
				$pro_detail  .= $data_add_pro;
			}

			$data_add  		= str_replace($middleprotemplate_desc, $pro_detail, $data_add);
			$data_add 		= str_replace("{product_loop_start}", "", $data_add);
			$data_add 		= str_replace("{product_loop_end}", "", $data_add);
			$sub_detail   .= $data_add;
		}

		$template_desc = str_replace($middletemplate_desc, $sub_detail, $template_desc);
		$template_desc = str_replace("{subscription_loop_start}", "", $template_desc);
		$template_desc = str_replace("{subscription_loop_end}", "", $template_desc);
		echo eval("?>" . $template_desc . "<?php ");
	}
	else
	{
		echo "<div>" . JText::_('COM_REDSHOP_SUBSCRIPTION_NOTICE_NO_HAVE_SUBSCRIPTION_OR_SUBSCRIPTION_EXPIRED') . "</div>";
	}
}
else
{
	echo "<div>" . JText::_('COM_REDSHOP_ALERTNOTAUTH_ACCOUNT') . "</div>";
	$link_login        = JRoute::_('index.php?option=' . $option . '&view=login&Itemid=' . $Itemid);
	$link_login_detail = "<a href='$link_login'>" . JText::_('COM_REDSHOP_LOGIN') . "</a>";
	echo "<div>" . $link_login_detail . "</div>";
}


?>
<script language="javascript" type="text/javascript">
	 jQuery("select[id*=selectedfile_]").change(function(){

			  	var id = jQuery(this).val();
				var a = jQuery(this).parent().next().find('a');
				var b = a.attr('href').replace(/media_id=([0-9]+)/, "media_id=" + id);
				a.attr('href', b); 

		  })
</script>	

