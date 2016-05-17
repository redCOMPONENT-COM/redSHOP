<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopControllerConfiguration extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->_configpath1 = JPATH_SITE . "/administrator/components/com_redshop/helpers/newtxt.php";
	}

	public function apply()
	{
		$this->save(1);
	}

	/**
	 * Collect Items from array using specific prefix
	 *
	 * @param   array   $array   Array from which needs to collects items based ok keys.
	 * @param   string  $prefix  Key prefix which needs to be filtered.
	 *
	 * @return  array   Array of values which is collected using prefix.
	 */
	protected function collectItemsUsingPrefix($array, $prefix)
	{
		$keys = array_keys($array);

		$values = array_filter($keys, function($value) use ($prefix) {
			return preg_match("/$prefix\d/", $value);
		});

		array_walk(
			$values,
			function(&$value) use ($array) {
				$value = $array[$value];
			},
			$array
		);

		return $values;
	}

	/**
	 * Collect quick icons list values
	 *
	 * @return  string  Comma seperated quick icon names
	 */
	protected function collectQuickIcons()
	{
		$post = JRequest::get('post');

		$iconList = array_merge(
			$this->collectItemsUsingPrefix($post, 'prodmng'),
			$this->collectItemsUsingPrefix($post, 'ordermng'),
			$this->collectItemsUsingPrefix($post, 'distmng'),
			$this->collectItemsUsingPrefix($post, 'commmng'),
			$this->collectItemsUsingPrefix($post, 'impmng'),
			$this->collectItemsUsingPrefix($post, 'vatmng'),
			$this->collectItemsUsingPrefix($post, 'custmng'),
			$this->collectItemsUsingPrefix($post, 'altmng'),
			$this->collectItemsUsingPrefix($post, 'usermng'),
			$this->collectItemsUsingPrefix($post, 'shippingmng'),
			$this->collectItemsUsingPrefix($post, 'accmng')
		);

		return implode(',', $iconList);
	}

	public function save($apply = 0)
	{
		$post = JRequest::get('post');

		$app = JFactory::getApplication();
		$selectedTabPosition = $app->input->get('selectedTabPosition');
		$app->setUserState('com_redshop.configuration.selectedTabPosition', $selectedTabPosition);

		$post['quicklink_icon'] = $this->collectQuickIcons();

		$post['custom_previous_link'] = JRequest::getVar('custom_previous_link', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$post['custom_next_link'] = JRequest::getVar('custom_next_link', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$post['default_next_suffix'] = JRequest::getVar('default_next_suffix', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$post['default_previous_prefix'] = JRequest::getVar('default_previous_prefix', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$post['return_to_category_prefix'] = JRequest::getVar('return_to_category_prefix', '', 'post', 'string', JREQUEST_ALLOWRAW);

		// Administrator email notifications ids
		if (is_array($post['administrator_email']))
		{
			$post['administrator_email'] = implode(",", $post['administrator_email']);
		}

		$msg                   = null;
		$model                 = $this->getModel('configuration');
		$newsletter_test_email = JRequest::getVar('newsletter_test_email');

		$post['country_list'] = implode(',', $app->input->post->get('country_list', array(), 'ARRAY'));

		if (!isset($post['default_vat_state']))
		{
			$post['default_vat_state'] = '';
		}

		if (!isset($post['write_review_is_lightbox']))
		{
			$post['write_review_is_lightbox'] = '';
		}

		if (!isset($post['splitable_payment']))
		{
			$post['splitable_payment'] = 0;
		}

		if (!isset($post['splitable_payment']))
		{
			$post['splitable_payment'] = 0;
		}

		if (!isset($post['seo_page_short_description']))
		{
			$post['seo_page_short_description'] = 0;
		}

		if (!isset($post['seo_page_short_description_category']))
		{
			$post['seo_page_short_description_category'] = 0;
		}

		if (!isset($post['allow_multiple_discount']))
		{
			$post['allow_multiple_discount'] = 0;
		}

		if (isset($post['product_download_root']))
		{
			if (!is_dir($post['product_download_root']))
			{
				$msg = "";
				JError::raiseWarning(21, JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DIRECTORY_DOES_NO_EXIST'));
			}

			elseif (!$model->configurationWriteable())
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_CONFIGURATION_FILE_IS_NOT_WRITABLE'));
			}
			elseif (!$model->configurationReadable())
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_CONFIGURATION_FILE_IS_NOT_READABLE'));
			}
			else
			{
				if ($model->store($post))
				{
					$msg = JText::_('COM_REDSHOP_CONFIG_SAVED');

					if ($newsletter_test_email)
					{
						$model->newsletterEntry($post);
						$msg = JText::sprintf('COM_REDSHOP_NEWSLETTER_SEND_TO_TEST_EMAIL', $newsletter_test_email);
					}

					// Thumb folder deleted and created
					if ($post['image_quality_output'] != IMAGE_QUALITY_OUTPUT || $post['use_image_size_swapping'] != USE_IMAGE_SIZE_SWAPPING)
					{
						$this->removeThumbImages();
					}
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_ERROR_IN_CONFIG_SAVE');
				}
			}
		}

		if ($apply)
		{
			$this->setRedirect('index.php?option=com_redshop&view=configuration', $msg);
		}

		else
		{
			$this->setRedirect('index.php?option=com_redshop', $msg);
		}
	}

	/**
	 * Remove all thumbanil images generated by redSHOP
	 *
	 * @return  boolean
	 */
	public function removeThumbImages()
	{
		$thumb_folder = array('product', 'category', 'manufacturer', 'product_attributes', 'property', 'subcolor', 'wrapper', 'shopperlogo');

		for ($i = 0, $in = count($thumb_folder); $i < $in; $i++)
		{
			$unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . $thumb_folder[$i] . '/thumb';

			if (JFolder::exists($unlink_path))
			{
				if (JFolder::delete($unlink_path) !== true)
				{
					return false;
				}
				else
				{
					if (JFolder::create($unlink_path) !== true)
					{
						return false;
					}
					else
					{
						$src = REDSHOP_FRONT_IMAGES_RELPATH . 'index.html';
						JFile::COPY($src, $unlink_path . '/index.html');
					}
				}
			}
		}

		return true;
	}

	public function removeimg()
	{
		ob_clean();
		$imname = JRequest::getString('imname', '');
		$spath = JRequest::getString('spath', '');
		$data_id = JRequest::getInt('data_id', 0);
		$extra_field = extra_field::getInstance();

		if ($data_id)
		{
			$extra_field->deleteExtraFieldData($data_id);
		}

		if (JPATH_ROOT . '/' . $spath . '/' . $imname)
		{
			unlink(JPATH_ROOT . '/' . $spath . '/' . $imname);
		}

		exit;
	}

	public function cancel()
	{

		$this->setRedirect('index.php?option=com_redshop');
	}

	public function resetTemplate()
	{
		$model = $this->getModel('configuration');


		$model->resetTemplate();

		$msg = JText::_('COM_REDSHOP_TEMPLATE_HAS_BEEN_RESET');
		$this->setRedirect('index.php?option=com_redshop', $msg);
	}

	public function resetTermsCondition()
	{
		$userhelper = rsUserHelper::getInstance();
		$userhelper->updateUserTermsCondition();
		die();
	}

	public function resetOrderId()
	{
		$order_functions = order_functions::getInstance();
		$order_functions->resetOrderId();
		die();
	}
}
