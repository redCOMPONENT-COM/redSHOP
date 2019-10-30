<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;
use Redshop\Economic\RedshopEconomic;

jimport('joomla.filesystem.file');


/**
 * Product_Detail Controller.
 *
 * @package     RedSHOP.Backend
 * @subpackage  Administrator
 *
 * @since       1.0
 */
class RedshopControllerProduct_Detail extends RedshopController
{
	/**
	 * @var JApplicationCms
	 */
	public $app;

	/**
	 * @var  string
	 */
	public $option;

	/**
	 * Constructor to set the right model
	 *
	 * @param   array $default Optional  configuration parameters
	 *
	 * @throws  Exception
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);

		$this->registerTask('add', 'edit');

		$this->app    = JFactory::getApplication();
		$this->option = $this->input->getCmd('option', 'com_redshop');
	}

	/**
	 * Method for redirect to edit an existing record.
	 *
	 * @return void
	 *
	 * @since   1.5
	 */
	public function editRedirect()
	{
		$cid = $this->input->post->get('cid', array(), 'array');
		$this->setRedirect(
			JRoute::_(
				'index.php?option=com_redshop&view=product_detail'
				. $this->getRedirectToItemAppend($cid[0], 'cid[]'), false
			)
		);
	}

	/**
	 * Method for redirect to add task.
	 *
	 * @return void
	 *
	 * @since   1.5
	 */
	public function addRedirect()
	{
		$this->setRedirect(
			JRoute::_(
				'index.php?option=com_redshop&view=product_detail'
				. $this->getRedirectToItemAppend(), false
			)
		);
	}

	/**
	 * Edit task.
	 *
	 * @return void
	 */
	public function edit()
	{
		$this->input->set('view', 'product_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * Save + New task.
	 *
	 * @return void
	 */
	public function save2new()
	{
		$this->save(2);
	}

	/**
	 * Apply task.
	 *
	 * @return void
	 */
	public function apply()
	{
		$this->save(1);
	}

	/**
	 * Save task.
	 *
	 * @param   int $apply Task is apply or common save.
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function save($apply = 0)
	{
		//@TODO This is potentially unsafe because $_POST elements are not sanitized.
		$post                 = $this->input->post->getArray();
		$cid                  = $this->input->post->get('cid', array(), 'array');
		$post ['product_id']  = $cid[0];
		$post['product_name'] = $this->input->post->get('product_name', null, 'string');

		$selectedTabPosition = $this->input->get('selectedTabPosition');
		$this->app->setUserState('com_redshop.product_detail.selectedTabPosition', $selectedTabPosition);

		if (is_array($post['product_category'])
			&& (isset($post['cat_in_sefurl']) && !in_array($post['cat_in_sefurl'], $post['product_category'])))
		{
			$post['cat_in_sefurl'] = $post['product_category'][0];
		}

		if (!$post ['product_id'])
		{
			$post ['publish_date'] = date("Y-m-d H:i:s");
		}

		// Setting default value
		$post['product_on_sale'] = 0;

		// Setting product on sale when discount dates are set
		if ((bool) $post['discount_stratdate'] || (bool) $post['discount_enddate'])
		{
			$post['product_on_sale'] = 1;
		}

		$post["product_number"] = trim($this->input->getString('product_number', ''));

		$product_s_desc         = $this->input->post->get('product_s_desc', array(), 'array');
		$post["product_s_desc"] = stripslashes($product_s_desc[0]);

		$product_desc         = $this->input->post->get('product_desc', array(), 'array');
		$post["product_desc"] = stripslashes($product_desc[0]);

		if (!empty($post['product_availability_date']))
		{
			$post['product_availability_date'] = strtotime($post['product_availability_date']);
		}

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		if ($row = $model->store($post))
		{
			// Save Association
			$model->SaveAssociations($row->product_id, $post);

			// Add product to economic
			if (Redshop::getConfig()->getInt('ECONOMIC_INTEGRATION') === 1)
			{
				RedshopEconomic::createProductInEconomic($row);
			}

			// Field_section 1 :Product
			RedshopHelperExtrafields::extraFieldSave($post, RedshopHelperExtrafields::SECTION_PRODUCT, $row->product_id);

			// Field_section 12 :Product Userfield
			RedshopHelperExtrafields::extraFieldSave($post, RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $row->product_id);

			// Field_section 12 :Productfinder datepicker
			RedshopHelperExtrafields::extraFieldSave($post, RedshopHelperExtrafields::SECTION_PRODUCT_FINDER_DATE_PICKER, $row->product_id);

			$this->attribute_save($post, $row);

			JPluginHelper::importPlugin('redshop_product');
			JPluginHelper::importPlugin('redshop_product_type');

			RedshopHelperUtility::getDispatcher()->trigger('onAfterProductFullSave', array($row, $post['product_id']));

			// Extra Field Data Saved
			$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_SAVED');

			if ($apply == 2)
			{
				$this->setRedirect('index.php?option=com_redshop&view=product_detail&task=add', $msg);
			}

            elseif ($apply == 1)
			{
				$this->setRedirect('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id, $msg);
			}
			else
			{
				$model->checkin($cid);
				$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
			}
		}
		else
		{
			$this->app->enqueueMessage($model->getError(), 'error');
			$this->input->set('view', 'product_detail');
			$this->input->set('layout', 'default');
			$this->input->set('hidemainmenu', 1);

			parent::display();
		}
	}

	/**
	 * Remove task.
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function remove()
	{
		$cid = $this->input->post->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			$this->app->enqueueMessage(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'), 'notice');
		}

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_DELETED_SUCCESSFULLY');

		if (!$model->delete($cid))
		{
			$msg = "";

			if ($model->getError() != "")
			{
				$this->app->enqueueMessage($model->getError(), 'notice');
			}
		}

		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Publish task.
	 *
	 * @return void
	 */
	public function publish()
	{
		$cid = $this->input->post->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			$this->app->enqueueMessage(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'), 'error');
		}

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Unpublish task.
	 *
	 * @return void
	 */
	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			$this->app->enqueueMessage(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'), 'error');
		}

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Unpublish cancel.
	 *
	 * @return void
	 */
	public function cancel()
	{
		/** @var RedshopModelProduct_Detail $model */
		$model    = $this->getModel('product_detail');
		$recordId = $this->input->get('cid');
		$model->checkin($recordId);
		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Save to Copy
	 *
	 * @return void
	 */
	public function save2copy()
	{
		$cid = $this->input->post->get('cid', array(), 'array');

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		if ($row = $model->copy($cid, true))
		{
			$this->setRedirect(
				'index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id,
				JText::_('COM_REDSHOP_PRODUCT_COPIED')
			);
		}
		else
		{
			$this->setRedirect(
				'index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $cid[0],
				JText::_('COM_REDSHOP_ERROR_PRODUCT_COPIED')
			);
		}
	}

	/**
	 * Copy task.
	 *
	 * @return void
	 */
	public function copy()
	{
		$cid = $this->input->post->get('cid', array(), 'array');

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_PRODUCT_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_PRODUCT_COPIED');
		}

		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Function attribute_save.
	 *
	 * @param   array  $post Array of input data.
	 * @param   object $row  Array of row data.
	 *
	 * @return void
	 */
	public function attribute_save($post, $row)
	{
		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		$attributesSave    = array();
		$propertiesSave    = array();
		$subPropertiesSave = array();

		if (!is_array($post['attribute']))
		{
			return;
		}

		$attributes = array_merge(array(), $post['attribute']);

		foreach ($attributes as $a => $attribute)
		{
			$attributesSave['attribute_id']             = $attribute['id'];
			$tmpordering                                = !empty($attribute['tmpordering']) ? $attribute['tmpordering'] : $a;
			$attributesSave['product_id']               = $row->product_id;
			$attributesSave['attribute_name']           = htmlspecialchars($attribute['name']);
			$attributesSave['ordering']                 = $attribute['ordering'];
			$attributesSave['attribute_published']      = ($attribute['published'] == 'on' || $attribute['published'] == '1') ? '1' : '0';
			$attributesSave['attribute_description']    = $attribute['attribute_description'];
			$attributesSave['attribute_required']       = isset($attribute['required']) && ($attribute['required'] == 'on' || $attribute['required'] == '1') ? '1' : '0';
			$attributesSave['allow_multiple_selection'] = isset($attribute['allow_multiple_selection']) && ($attribute['allow_multiple_selection'] == 'on' || $attribute['allow_multiple_selection'] == '1') ? '1' : '0';
			$attributesSave['hide_attribute_price']     = isset($attribute['hide_attribute_price']) && ($attribute['hide_attribute_price'] == 'on' || $attribute['hide_attribute_price'] == '1') ? '1' : '0';
			$attributesSave['display_type']             = $attribute['display_type'];

			$attribute_array = $model->store_attr($attributesSave);
			$properties      = array_merge(array(), $attribute['property']);

			$propertyImage      = array_keys($attribute['property']);
			$tmpproptyimagename = array_merge(array(), $propertyImage);

			foreach ($properties as $pIndex => $property)
			{
				$propertiesSave['property_id']         = $property['property_id'];
				$propertiesSave['attribute_id']        = $attribute_array->attribute_id;
				$propertiesSave['property_name']       = htmlspecialchars($property['name']);
				$propertiesSave['property_price']      = $property['price'];
				$propertiesSave['oprand']              = $property['oprand'];
				$propertiesSave['property_number']     = isset($property['number']) ? $property['number'] : '';
				$propertiesSave['property_image']      = isset($property['property_image']) ? $property['property_image'] : '';
				$propertiesSave['ordering']            = $property['order'];
				$propertiesSave['setrequire_selected'] = isset($property['req_sub_att'])
				&& ($property['req_sub_att'] == 'on' || $property['req_sub_att'] == '1') ? '1' : '0';
				$propertiesSave['setmulti_selected']   = isset($property['multi_sub_att'])
				&& ($property['multi_sub_att'] == 'on' || $property['multi_sub_att'] == '1') ? '1' : '0';
				$propertiesSave['setdefault_selected'] = !empty($property['default_sel']) && ($property['default_sel'] == 'on' || $property['default_sel'] == '1') ? '1' : '0';
				$propertiesSave['setdisplay_type']     = $property['setdisplay_type'];
				$propertiesSave['property_published']  = ($property['published'] == 'on' || $property['published'] == '1') ? '1' : '0';
				$propertiesSave['extra_field']         = $property['extra_field'];
				$property_array                        = $model->store_pro($propertiesSave);
				$property_id                           = $property_array->property_id;
				$property_image                        = $this->input->files->get('attribute_' . $tmpordering . '_property_' . $tmpproptyimagename[$pIndex] . '_image', array(), 'array');

				if (empty($property['mainImage']))
				{
					if (!empty($property_image['name']))
					{
						$propertiesSave['property_image'] = $model->copy_image($property_image, 'product_attributes', $property_id);
						$propertiesSave['property_id']    = $property_id;
						$property_array                   = $model->store_pro($propertiesSave);
						$this->DeleteMergeImages();
					}
				}

				if (!empty($property['mainImage']))
				{
					$propertiesSave['property_image'] = $model->copy_image_from_path($property['mainImage'], 'product_attributes', $property_id);
					$propertiesSave['property_id']    = $property_id;
					$property_array                   = $model->store_pro($propertiesSave);
					$this->DeleteMergeImages();
				}

				if (empty($property['property_id']))
				{
					$listImages = $model->getImageInfor($property_id, 'property');

					for ($li = 0, $countImage = count($listImages); $li < $countImage; $li++)
					{
						$mImages                         = array();
						$mImages['media_name']           = $listImages[$li]->media_name;
						$mImages['media_alternate_text'] = $listImages[$li]->media_alternate_text;
						$mImages['media_section']        = 'property';
						$mImages['section_id']           = $property_id;
						$mImages['media_type']           = 'images';
						$mImages['media_mimetype']       = $listImages[$li]->media_mimetype;
						$mImages['published']            = $listImages[$li]->published;
						$model->copyadditionalImage($mImages);
					}
				}

				if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
				{
					RedshopEconomic::createPropertyInEconomic($row, $property_array);
				}

				// Set trigger to save Attribute Property Plugin Data
				if ((int) $property_id)
				{
					$dispatcher = RedshopHelperUtility::getDispatcher();
					JPluginHelper::importPlugin('redshop_product_type');

					// Trigger the data preparation event.
					$dispatcher->trigger('onAttributePropertySaveLoop', array($row, &$property, &$property_array));
				}

				$subproperty       = array_merge(array(), $property['subproperty']);
				$subproperty_title = $property['subproperty']['title'];
				$subpropertyImage  = array_keys($property['subproperty']);
				unset($subpropertyImage[0]);
				$tmpimagename = array_merge(array(), $subpropertyImage);

				for ($sp = 0; $sp < count($subproperty) - 1; $sp++)
				{
					$subPropertiesSave['subattribute_color_id']     = $subproperty[$sp]['subproperty_id'];
					$subPropertiesSave['subattribute_color_name']   = $subproperty[$sp]['name'];
					$subPropertiesSave['subattribute_color_title']  = $subproperty_title;
					$subPropertiesSave['subattribute_color_price']  = $subproperty[$sp]['price'];
					$subPropertiesSave['oprand']                    = $subproperty[$sp]['oprand'];
					$subPropertiesSave['subattribute_color_image']  = $subproperty[$sp]['image'];
					$subPropertiesSave['subattribute_id']           = $property_id;
					$subPropertiesSave['ordering']                  = $subproperty[$sp]['order'];
					$subPropertiesSave['subattribute_color_number'] = $subproperty[$sp]['number'];
					$subPropertiesSave['setdefault_selected']       = ($subproperty[$sp]['chk_propdselected'] == 'on'
						|| $subproperty[$sp]['chk_propdselected'] == '1') ? '1' : '0';
					$subPropertiesSave['subattribute_published']    = ($subproperty[$sp]['published'] == 'on'
						|| $subproperty[$sp]['published'] == '1') ? '1' : '0';
					$subPropertiesSave['extra_field']               = $subproperty[$sp]['extra_field'];
					$subproperty_array                              = $model->store_sub($subPropertiesSave);
					$subproperty_image                              = $this->input->files->get('attribute_' . $tmpordering . '_property_' . $pIndex . '_subproperty_' . $tmpimagename[$sp] . '_image',
						array(),
						'array'
					);
					$subproperty_id                                 = $subproperty_array->subattribute_color_id;

					if (empty($subproperty[$sp]['mainImage']))
					{
						if (!empty($subproperty_image['name']))
						{
							$subPropertiesSave['subattribute_color_image'] = $model->copy_image($subproperty_image, 'subcolor', $subproperty_id);
							$subPropertiesSave['subattribute_color_id']    = $subproperty_id;
							$subproperty_array                             = $model->store_sub($subPropertiesSave);
							$this->DeleteMergeImages();
						}
					}

					if (!empty($subproperty[$sp]['mainImage']))
					{
						$subPropertiesSave['subattribute_color_image'] = $model->copy_image_from_path($subproperty[$sp]['mainImage'], 'subcolor', $subproperty_id);
						$subPropertiesSave['subattribute_color_id']    = $subproperty_id;
						$subproperty_array                             = $model->store_sub($subPropertiesSave);
						$this->DeleteMergeImages();
					}

					if (empty($subproperty[$sp]['subproperty_id']))
					{
						$listsubpropImages     = $model->getImageInfor($subproperty_id, 'subproperty');
						$countSubpropertyImage = count($listsubpropImages);

						for ($lsi = 0; $lsi < $countSubpropertyImage; $lsi++)
						{
							$smImages                         = array();
							$smImages['media_name']           = $listsubpropImages[$lsi]->media_name;
							$smImages['media_alternate_text'] = $listsubpropImages[$lsi]->media_alternate_text;
							$smImages['media_section']        = 'subproperty';
							$smImages['section_id']           = $subproperty_id;
							$smImages['media_type']           = 'images';
							$smImages['media_mimetype']       = $listsubpropImages[$lsi]->media_mimetype;
							$smImages['published']            = $listsubpropImages[$lsi]->published;
							$model->copyadditionalImage($smImages);
						}
					}

					if (Redshop::getConfig()->getInt('ECONOMIC_INTEGRATION') === 1
						&& Redshop::getConfig()->getInt('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') !== 0)
					{
						RedshopEconomic::createSubpropertyInEconomic($row, $subproperty_array);
					}
				}
			}
		}
	}

	/**
	 * Does something with image?
	 *
	 * @param   int    $width  Width.
	 * @param   int    $height Height.
	 * @param   string $target Target.
	 *
	 * @return array
	 */
	public function _imageResize($width, $height, $target)
	{
		if ($width > $height)
		{
			$percentage = ($target / $width);
		}
		else
		{
			$percentage = ($target / $height);
		}

		$width  = round($width * $percentage);
		$height = round($height * $percentage);

		if ($width < 5)
		{
			$width = 50;
		}

		if ($height < 5)
		{
			$height = 50;
		}

		return array($width, $height);
	}

	/**
	 * Function property_more_img.
	 *
	 * @return void
	 */
	public function property_more_img()
	{
		$uri = JURI::getInstance();

		$url = $uri->root();

		// ToDo: This is potentially unsafe because $_POST elements are not sanitized.
		$post     = $this->input->post->getArray();
		$main_img = $this->input->files->get('property_main_img', null);
		$sub_img  = $this->input->files->get('property_sub_img', null);

		$model = $this->getModel('product_detail');

		$filetype = strtolower(JFile::getExt($main_img['name']));

		$filetype_sub = strtolower(JFile::getExt($sub_img['name'][0]));

		if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg'
			&& $main_img['name'] != '' && $filetype_sub != 'png' && $filetype_sub != 'gif'
			&& $filetype_sub != 'jpeg' && $filetype_sub != 'jpg' && $sub_img['name'][0] != ''
		)
		{
			$msg  = JText::_("COM_REDSHOP_FILE_EXTENTION_WRONG_PROPERTY");
			$link = $url . "administrator/index.php?tmpl=component&option=com_redshop&view=product_detail&section_id="
				. $post['section_id'] . "&cid=" . $post['cid'] . "&layout=property_images&showbuttons=1";
			$this->setRedirect($link, $msg);
		}
		else
		{
			$model->property_more_img($post, $main_img, $sub_img);
			?>
            <script language="javascript" type="text/javascript">
                window.parent.SqueezeBox.close();
            </script>
			<?php
		}
	}

	/**
	 * Delete image function.
	 *
	 * @return void
	 */
	public function deleteimage()
	{
		$uri = JURI::getInstance();

		$url = $uri->root();

		$mediaid    = $this->input->getInt('mediaid', null);
		$section_id = $this->input->getInt('section_id', null);
		$cid        = $this->input->getInt('cid', null);

		$model = $this->getModel('product_detail');

		if ($model->deletesubimage($mediaid))
		{
			$msg  = JText::_("COM_REDSHOP_PROPERTY_SUB_IMAGE_IS_DELETE");
			$link = $url . "administrator/index.php?tmpl=component&option=com_redshop&view=product_detail&section_id="
				. $section_id . "&cid=" . $cid . "&layout=property_images&showbuttons=1";
			$this->setRedirect($link, $msg);
		}
	}

	/**
	 * Function subattribute_color.
	 *
	 * @return void
	 */
	public function subattribute_color()
	{
		$post = $this->input->post->getArray();

		$model = $this->getModel('product_detail');

		$subattr_id = implode("','", $post['subattribute_color_id']);

		$subattr_diff = $model->subattr_diff($subattr_id, $post['section_id']);

		// Delete subAttribute Diffrence
		$model->delsubattr_diff($subattr_diff);

		$sub_img = $this->input->files->get('property_sub_img', null);

		$model->subattribute_color($post, $sub_img);

		?>
        <script language="javascript" type="text/javascript">
            window.parent.SqueezeBox.close();
        </script>
		<?php
	}

	/**
	 * Function removepropertyImage.
	 *
	 * @return void
	 */
	public function removepropertyImage()
	{
		$pid = $this->input->get->getInt('pid', null);

		$model = $this->getModel('product_detail');

		if ($model->removepropertyImage($pid))
		{
			echo "sucess";
		}
	}

	/**
	 * Function removesubpropertyImage.
	 *
	 * @return void
	 */
	public function removesubpropertyImage()
	{
		$pid = $this->input->get->getInt('pid', null);

		$model = $this->getModel('product_detail');

		if ($model->removesubpropertyImage($pid))
		{
			echo "sucess";
		}
	}

	/**
	 * Function saveAttributeStock.
	 *
	 * @return void
	 */
	public function saveAttributeStock()
	{
		// ToDo: This is potentially unsafe because $_POST elements are not sanitized.
		$post = $this->input->post->getArray();

		$model = $this->getModel('product_detail');

		if ($model->SaveAttributeStockroom($post))
		{
			$msg = JText::_('COM_REDSHOP_STOCKROOM_ATTRIBUTE_XREF_SAVE');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKROOM_ATTRIBUTE_XREF');
		}

		$link = "index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=" . $post['section_id'] . "&cid="
			. $post['cid'] . "&layout=productstockroom&property=" . $post['section'];
		$this->setRedirect($link, $msg);
	}

	/**
	 * Function orderup.
	 *
	 * @return void
	 */
	public function orderup()
	{
		$model = $this->getModel('product_detail');

		/**
		 * @var RedshopModelProduct_Detail $model
		 */
		$model->orderup();

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Function orderdown.
	 *
	 * @return void
	 */
	public function orderdown()
	{
		$model = $this->getModel('product_detail');

		/**
		 * @var RedshopModelProduct_Detail $model
		 */
		$model->orderdown();
		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Function saveorder.
	 *
	 * @return void
	 */
	public function saveorder()
	{
		$cid   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		$cid   = ArrayHelper::toInteger($cid);
		$order = ArrayHelper::toInteger($order);

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Function deleteProdcutSerialNumbers.
	 *
	 * @return void
	 */
	public function deleteProdcutSerialNumbers()
	{
		$serial_id  = $this->input->getInt('serial_id', null);
		$product_id = $this->input->getInt('product_id', null);

		$model = $this->getModel('product_detail');
		$model->deleteProdcutSerialNumbers($serial_id);

		$msg = JText::_('COM_REDSHOP_PRODUCT_SERIALNUMBER_DELETED');
		$this->setRedirect('index.php?option=com_redshop&view=product_detail&cid=' . $product_id, $msg);
	}

	/**
	 * Function delete_subprop.
	 *
	 * @return void
	 */
	public function delete_subprop()
	{
		$sp_id           = $this->input->get->getInt('sp_id', null);
		$subattribute_id = $this->input->get->getInt('subattribute_id', null);

		$model = $this->getModel('product_detail');
		$model->delete_subprop($sp_id, $subattribute_id);
	}

	/**
	 * Function delete_prop.
	 *
	 * @return void
	 */
	public function delete_prop()
	{
		$attribute_id = $this->input->get->getInt('attribute_id', null);
		$property_id  = $this->input->get->getInt('property_id', null);

		$model = $this->getModel('product_detail');
		$model->delete_prop($attribute_id, $property_id);
	}

	/**
	 * Function delete_attibute.
	 *
	 * @return void
	 */
	public function delete_attibute()
	{
		$product_id       = $this->input->get->getInt('product_id', null);
		$attribute_id     = $this->input->get->getInt('attribute_id', null);
		$attribute_set_id = $this->input->get->getInt('attribute_set_id', null);

		$model = $this->getModel('product_detail');
		$model->delete_attibute($product_id, $attribute_id, $attribute_set_id);
	}

	/**
	 * Function checkVirtualNumber.
	 *
	 * @return void
	 */
	public function checkVirtualNumber()
	{
		$isExists   = true;
		$product_id = $this->input->getInt('product_id', null);
		$str        = $this->input->getString('str', '');
		$strArr     = explode(",", $str);
		$result     = array_unique($strArr);

		if (count($result) > 0 && count($result) == count($strArr))
		{
			$model    = $this->getModel('product_detail');

			/**
			 * @var RedshopModelProduct_Detail $model
			 */
			$isExists = $model->checkVirtualNumber($product_id, $result);
		}

		echo (int) $isExists;

		\JFactory::getApplication()->close();
	}

	/**
	 * Function to get all child product array for ajax call.
	 *
	 * @return void
	 */
	public function getChildProducts()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest('GET');

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');
		$prod  = $model->getChildProducts();

		echo implode(",", $prod->id) . ":" . implode(",", $prod->name);

		JFactory::getApplication()->close();
	}

	/**
	 * Function removeaccesory.
	 *
	 * @return void
	 */
	public function removeaccesory()
	{
		$accessory_id     = $this->input->getInt('accessory_id', null);
		$category_id      = $this->input->getInt('category_id', null);
		$child_product_id = $this->input->getInt('child_product_id', null);
		$model            = $this->getModel('product_detail');
		$model->removeaccesory($accessory_id, $category_id, $child_product_id);
		JFactory::getApplication()->close();
	}

	/**
	 * Function ResetPreorderStock.
	 *
	 * @return void
	 */
	public function ResetPreorderStock()
	{
		$model          = $this->getModel('product_detail');
		$stockroom_type = $this->input->getString('stockroom_type', 'product');
		$pid            = $this->input->getInt('product_id', null);
		$sid            = $this->input->getInt('stockroom_id', null);

		$model->ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid);

		$this->setRedirect('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $pid);
	}

	/**
	 * Function ResetPreorderStockBank.
	 *
	 * @return void
	 */
	public function ResetPreorderStockBank()
	{
		$model          = $this->getModel('product_detail');
		$stockroom_type = $this->input->getString('stockroom_type', 'product');
		$section_id     = $this->input->getInt('section_id', null);
		$cid            = $this->input->getInt('cid', null);
		$sid            = $this->input->getInt('stockroom_id', null);

		$model->ResetPreOrderStockroomQuantity($stockroom_type, $sid, $section_id);

		$link = "index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=" . $section_id . "&cid="
			. $cid . "&layout=productstockroom&property=" . $stockroom_type;
		$this->setRedirect($link);
	}

	/**
	 * Function getDynamicFields.
	 *
	 * @return void
	 */
	public function getDynamicFields()
	{
		$this->input->set('view', 'product_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * Function DeleteMergeImages.
	 *
	 * @return boolean
	 */
	public function DeleteMergeImages()
	{
		$dirname = REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages";

		if (!is_dir($dirname))
		{
			return true;
		}

		$dirHandle = opendir($dirname);

		if ($dirHandle === false)
		{
			return true;
		}

		while ($file = readdir($dirHandle))
		{
			if ($file == '..' || $file == '.' || $file == '' || $file == 'index.html')
			{
				continue;
			}

			if (!JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $file))
			{
				continue;
			}

			if (!is_writeable(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $file))
			{
				chmod(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $file, 0777);
			}

			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $file);
		}

		closedir($dirHandle);

		return true;
	}

	/**
	 * Method for get all available product number
	 *
	 * @return  void
	 *
	 * @since   2.0.4
	 */
	public function ajaxGetAllProductNumber()
	{
		JSession::checkToken() or die('JINVALID_TOKEN');

		$app = JFactory::getApplication();

		echo json_encode(RedshopHelperProduct::getAllAvailableProductNumber($app->input->getInt('product_id', 0)));

		$app->close();
	}

	/**
	 * Method display product attribute
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function ajaxDisplayAttributeSet()
	{
		$post = $this->input->post->getArray();

		if ($post['attribute_set'])
		{
			$attributes = \RedshopHelperProduct_Attribute::getProductAttribute(0, $post['attribute_set'], 0, 1, 0);
			foreach ($attributes as $attribute)
			{
				$attribute->propeties = \RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute->attribute_id, 0);

				foreach ($attribute->propeties as $propety)
				{
					$propety->subProperties = \RedshopHelperProduct_Attribute::getAttributeSubProperties(0,$propety->property_id);
				}
			}

			$result = json_encode($attributes);

			echo $result;
		}

		JFactory::getApplication()->close();
	}
}
