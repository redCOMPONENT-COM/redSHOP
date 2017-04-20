<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');


/**
 * Product_Detail Controller.
 *
 * @package     RedSHOP.Backend
 * @subpackage  Administrator
 *
 * @since       1.0
 */
class RedshopControllerProduct extends RedshopControllerForm
{

	public function edit($key = null, $urlVar = 'id')
	{
		return parent::edit($key, $urlVar);
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function save($key = 'product_id', $urlVar = 'id')
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$model = $this->getModel();
		$table = $model->getTable();
		$data  = $this->input->post->getArray();
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();

		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		$recordId = $this->input->getInt($urlVar);

		// Populate the row id from the session.
		$data[$key] = $recordId;

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy')
		{
			// Check-in the original row.
			if ($checkin && $model->checkin($data[$key]) === false)
			{
				// Check-in failed. Go back to the item and display a notice.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
				$this->setMessage($this->getError(), 'error');

				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);

				return false;
			}

			// Reset the ID, the multilingual associations and then treat the request as for Apply.
			$data[$key] = 0;
			$data['associations'] = array();
			$task = 'apply';
		}

		// Access check.
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		// Test whether the data is valid.
        // @TODO Temporary bypass vlaidate because we are not convert to jform yet
		//$validData = $model->validate($form, $data);
		$validData = $data;

		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		if (!isset($validData['tags']))
		{
			$validData['tags'] = null;
		}

		// Attempt to save the data.
		if (!$model->store($validData))
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		// Save succeeded, so check-in the record.
		if ($checkin && $model->checkin($validData[$key]) === false)
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Check-in failed, so go back to the record and display a notice.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		$langKey = $this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS';
		$prefix  = JFactory::getLanguage()->hasKey($langKey) ? $this->text_prefix : 'JLIB_APPLICATION';

		$this->setMessage(JText::_($prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$model->checkout($recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend(null, $urlVar), false
					)
				);
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list
						. $this->getRedirectToListAppend(), false
					)
				);
				break;
		}

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}

	/**
	 * Remove task.
	 *
	 * @return void
	 */
	public function remove()
	{
		$cid = $this->input->post->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			$this->app->enqueueMessage(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'), 'notice');
		}

		$model = $this->getModel('Product');

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

		$model = $this->getModel('Product');

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

		$model = $this->getModel('Product');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Save to Copy
	 *
	 * @return void
	 */
	public function save2copy()
	{
		$cid   = $this->input->post->get('cid', array(), 'array');
		$model = $this->getModel('Product');

		if ($row = $model->copy($cid, true))
		{
			$this->setRedirect('index.php?option=com_redshop&view=product&task=edit&cid[]=' . $row->product_id, JText::_('COM_REDSHOP_PRODUCT_COPIED'));
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=product&task=edit&cid[]=' . $cid[0], JText::_('COM_REDSHOP_ERROR_PRODUCT_COPIED'));
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

		$model = $this->getModel('Product');

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
		$economic = null;

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
		{
			$economic = economic::getInstance();
		}

		$model = $this->getModel('Product');

		$attribute_save   = array();
		$property_save    = array();
		$subproperty_save = array();

		if (!is_array($post['attribute']))
		{
			return;
		}

		$attribute = array_merge(array(), $post['attribute']);

		for ($a = 0; $a < count($attribute); $a++)
		{
			$attribute_save['attribute_id']        = $attribute[$a]['id'];
			$tmpordering                           = ($attribute[$a]['tmpordering']) ? $attribute[$a]['tmpordering'] : $a;
			$attribute_save['product_id']          = $row->product_id;
			$attribute_save['attribute_name']      = htmlspecialchars($attribute[$a]['name']);
			$attribute_save['ordering']            = $attribute[$a]['ordering'];
			$attribute_save['attribute_published'] = ($attribute[$a]['published'] == 'on' || $attribute[$a]['published'] == '1') ? '1' : '0';

			$attribute_save['attribute_required']       = isset($attribute[$a]['required'])
			&& ($attribute[$a]['required'] == 'on' || $attribute[$a]['required'] == '1') ? '1' : '0';
			$attribute_save['allow_multiple_selection'] = isset($attribute[$a]['allow_multiple_selection'])
			&& ($attribute[$a]['allow_multiple_selection'] == 'on'
				|| $attribute[$a]['allow_multiple_selection'] == '1') ? '1' : '0';
			$attribute_save['hide_attribute_price']     = isset($attribute[$a]['hide_attribute_price'])
			&& ($attribute[$a]['hide_attribute_price'] == 'on'
				|| $attribute[$a]['hide_attribute_price'] == '1') ? '1' : '0';
			$attribute_save['display_type']             = $attribute[$a]['display_type'];

			$attribute_array = $model->store_attr($attribute_save);
			$property        = array_merge(array(), $attribute[$a]['property']);

			$propertyImage      = array_keys($attribute[$a]['property']);
			$tmpproptyimagename = array_merge(array(), $propertyImage);

			for ($p = 0; $p < count($property); $p++)
			{
				$property_save['property_id']         = $property[$p]['property_id'];
				$property_save['attribute_id']        = $attribute_array->attribute_id;
				$property_save['property_name']       = htmlspecialchars($property[$p]['name']);
				$property_save['property_price']      = $property[$p]['price'];
				$property_save['oprand']              = $property[$p]['oprand'];
				$property_save['property_number']     = isset($property[$p]['number']) ? $property[$p]['number'] : '';
				$property_save['property_image']      = isset($property[$p]['property_image']) ? $property[$p]['property_image'] : '';
				$property_save['ordering']            = $property[$p]['order'];
				$property_save['setrequire_selected'] = isset($property[$p]['req_sub_att'])
				&& ($property[$p]['req_sub_att'] == 'on' || $property[$p]['req_sub_att'] == '1') ? '1' : '0';
				$property_save['setmulti_selected']   = isset($property[$p]['multi_sub_att'])
				&& ($property[$p]['multi_sub_att'] == 'on' || $property[$p]['multi_sub_att'] == '1') ? '1' : '0';
				$property_save['setdefault_selected'] = ($property[$p]['default_sel'] == 'on' || $property[$p]['default_sel'] == '1') ? '1' : '0';
				$property_save['setdisplay_type']     = $property[$p]['setdisplay_type'];
				$property_save['property_published']  = ($property[$p]['published'] == 'on' || $property[$p]['published'] == '1') ? '1' : '0';
				$property_save['extra_field']         = $property[$p]['extra_field'];
				$property_array                       = $model->store_pro($property_save);
				$property_id                          = $property_array->property_id;
				$property_image                       = $this->input->files->get('attribute_' . $tmpordering . '_property_' . $tmpproptyimagename[$p] . '_image', array(), 'array');

				if (empty($property[$p]['mainImage']))
				{
					if (!empty($property_image['name']))
					{
						$property_save['property_image'] = $model->copy_image($property_image, 'product_attributes', $property_id);
						$property_save['property_id']    = $property_id;
						$property_array                  = $model->store_pro($property_save);
						$this->DeleteMergeImages();
					}
				}

				if (!empty($property[$p]['mainImage']))
				{
					$property_save['property_image'] = $model->copy_image_from_path($property[$p]['mainImage'], 'product_attributes', $property_id);
					$property_save['property_id']    = $property_id;
					$property_array                  = $model->store_pro($property_save);
					$this->DeleteMergeImages();
				}

				if (empty($property[$p]['property_id']))
				{
					$listImages = $model->GetimageInfo($property_id, 'property');

					for ($li = 0; $li < count($listImages); $li++)
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
					$economic->createPropertyInEconomic($row, $property_array);
				}

				// Set trigger to save Attribute Property Plugin Data
				if ((int) $property_id)
				{
					$dispatcher = JDispatcher::getInstance();
					JPluginHelper::importPlugin('redshop_product_type');

					// Trigger the data preparation event.
					$dispatcher->trigger('onAttributePropertySaveLoop', array($row, &$property[$p], &$property_array));
				}

				$subproperty       = array_merge(array(), $property[$p]['subproperty']);
				$subproperty_title = $property[$p]['subproperty']['title'];
				$subpropertyImage  = array_keys($property[$p]['subproperty']);
				unset($subpropertyImage[0]);
				$tmpimagename = array_merge(array(), $subpropertyImage);

				for ($sp = 0; $sp < count($subproperty) - 1; $sp++)
				{
					$subproperty_save['subattribute_color_id']     = $subproperty[$sp]['subproperty_id'];
					$subproperty_save['subattribute_color_name']   = $subproperty[$sp]['name'];
					$subproperty_save['subattribute_color_title']  = $subproperty_title;
					$subproperty_save['subattribute_color_price']  = $subproperty[$sp]['price'];
					$subproperty_save['oprand']                    = $subproperty[$sp]['oprand'];
					$subproperty_save['subattribute_color_image']  = $subproperty[$sp]['image'];
					$subproperty_save['subattribute_id']           = $property_id;
					$subproperty_save['ordering']                  = $subproperty[$sp]['order'];
					$subproperty_save['subattribute_color_number'] = $subproperty[$sp]['number'];
					$subproperty_save['setdefault_selected']       = ($subproperty[$sp]['chk_propdselected'] == 'on'
						|| $subproperty[$sp]['chk_propdselected'] == '1') ? '1' : '0';
					$subproperty_save['subattribute_published']    = ($subproperty[$sp]['published'] == 'on'
						|| $subproperty[$sp]['published'] == '1') ? '1' : '0';
					$subproperty_save['extra_field']               = $subproperty[$sp]['extra_field'];
					$subproperty_array                             = $model->store_sub($subproperty_save);
					$subproperty_image                             = $this->input->files->get('attribute_' . $tmpordering . '_property_' . $p . '_subproperty_' . $tmpimagename[$sp] . '_image',
						array(),
						'array'
					);
					$subproperty_id                                = $subproperty_array->subattribute_color_id;

					if (empty($subproperty[$sp]['mainImage']))
					{
						if (!empty($subproperty_image['name']))
						{
							$subproperty_save['subattribute_color_image'] = $model->copy_image($subproperty_image, 'subcolor', $subproperty_id);
							$subproperty_save['subattribute_color_id']    = $subproperty_id;
							$subproperty_array                            = $model->store_sub($subproperty_save);
							$this->DeleteMergeImages();
						}
					}

					if (!empty($subproperty[$sp]['mainImage']))
					{
						$subproperty_save['subattribute_color_image'] = $model->copy_image_from_path($subproperty[$sp]['mainImage'], 'subcolor', $subproperty_id);
						$subproperty_save['subattribute_color_id']    = $subproperty_id;
						$subproperty_array                            = $model->store_sub($subproperty_save);
						$this->DeleteMergeImages();
					}

					if (empty($subproperty[$sp]['subproperty_id']))
					{
						$listsubpropImages = $model->GetimageInfo($subproperty_id, 'subproperty');

						for ($lsi = 0; $lsi < count($listsubpropImages); $lsi++)
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

					if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
					{
						$economic->createSubpropertyInEconomic($row, $subproperty_array);
					}
				}
			}
		}

		return;
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

		$model = $this->getModel('Product');

		$filetype = strtolower(JFile::getExt($main_img['name']));

		$filetype_sub = strtolower(JFile::getExt($sub_img['name'][0]));

		if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg'
			&& $main_img['name'] != '' && $filetype_sub != 'png' && $filetype_sub != 'gif'
			&& $filetype_sub != 'jpeg' && $filetype_sub != 'jpg' && $sub_img['name'][0] != ''
		)
		{
			$msg  = JText::_("COM_REDSHOP_FILE_EXTENTION_WRONG_PROPERTY");
			$link = $url . "administrator/index.php?tmpl=component&option=com_redshop&view=product&section_id="
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

		$model = $this->getModel('Product');

		if ($model->deletesubimage($mediaid))
		{
			$msg  = JText::_("COM_REDSHOP_PROPERTY_SUB_IMAGE_IS_DELETE");
			$link = $url . "administrator/index.php?tmpl=component&option=com_redshop&view=product&section_id="
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

		$model = $this->getModel('Product');

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

		$model = $this->getModel('Product');

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

		$model = $this->getModel('Product');

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

		$model = $this->getModel('Product');

		if ($model->SaveAttributeStockroom($post))
		{
			$msg = JText::_('COM_REDSHOP_STOCKROOM_ATTRIBUTE_XREF_SAVE');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKROOM_ATTRIBUTE_XREF');
		}

		$link = "index.php?tmpl=component&option=com_redshop&view=product&section_id=" . $post['section_id'] . "&cid="
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
		$model = $this->getModel('Product');

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
		$model = $this->getModel('Product');

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
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('Product');
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

		$model = $this->getModel('Product');
		$model->deleteProdcutSerialNumbers($serial_id);

		$msg = JText::_('COM_REDSHOP_PRODUCT_SERIALNUMBER_DELETED');
		$this->setRedirect('index.php?option=com_redshop&view=product&cid=' . $product_id, $msg);
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

		$model = $this->getModel('Product');
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

		$model = $this->getModel('Product');
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

		$model = $this->getModel('Product');
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
			$model    = $this->getModel('Product');
			$isExists = $model->checkVirtualNumber($product_id, $result);
		}

		echo (int) $isExists;
		die();
	}

	/**
	 * Function to get all child product array for ajax call.
	 *
	 * @return void
	 */
	public function getChildProducts()
	{
		RedshopHelperAjax::validateAjaxRequest('GET');

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('Product');
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
		$model            = $this->getModel('Product');
		$model->removeaccesory($accessory_id, $category_id, $child_product_id);
		exit;
	}

	/**
	 * Function ResetPreorderStock.
	 *
	 * @return void
	 */
	public function ResetPreorderStock()
	{
		$model          = $this->getModel('Product');
		$stockroom_type = $this->input->getString('stockroom_type', 'product');
		$pid            = $this->input->getInt('product_id', null);
		$sid            = $this->input->getInt('stockroom_id', null);

		$model->ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid);

		$this->setRedirect('index.php?option=com_redshop&view=product&task=edit&cid[]=' . $pid);
	}

	/**
	 * Function ResetPreorderStockBank.
	 *
	 * @return void
	 */
	public function ResetPreorderStockBank()
	{
		$model          = $this->getModel('Product');
		$stockroom_type = $this->input->getString('stockroom_type', 'product');
		$section_id     = $this->input->getInt('section_id', null);
		$cid            = $this->input->getInt('cid', null);
		$sid            = $this->input->getInt('stockroom_id', null);

		$model->ResetPreOrderStockroomQuantity($stockroom_type, $sid, $section_id);

		$link = "index.php?tmpl=component&option=com_redshop&view=product&section_id=" . $section_id . "&cid="
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
		$this->input->set('view', 'product');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * Function DeleteMergeImages.
	 *
	 * @return bool
	 */
	public function DeleteMergeImages()
	{
		$dirname = REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages";

		if (is_dir($dirname))
		{
			$dir_handle = opendir($dirname);

			if ($dir_handle)
			{
				while ($file = readdir($dir_handle))
				{
					if ($file != '..' && $file != '.' && $file != '')
					{
						if ($file != 'index.html')
						{
							if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $file))
							{
								if (!is_writeable(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $file))
								{
									chmod(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $file, 0777);
								}

								unlink(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $file);
							}
						}
					}
				}
			}

			closedir($dir_handle);
		}

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

		echo implode(',', RedshopHelperProduct::getAllAvailableProductNumber($app->input->getInt('product_id', 0)));

		$app->close();
	}
}
