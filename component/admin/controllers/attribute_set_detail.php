<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Attribute Set Detail controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.6
 */
class RedshopControllerAttribute_Set_Detail extends RedshopController
{
	/**
	 * RedshopControllerAttribute_Set_Detail constructor.
	 *
	 * @param   array $default
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'attribute_set_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = $this->input->post->getArray();

		/** @var RedshopModelAttribute_set_detail $model */
		$model = $this->getModel('attribute_set_detail');
		$msg   = '';

		if ($row = $model->store($post))
		{
			$this->attribute_save($post, $row);

			$msg = JText::_('COM_REDSHOP_ATTRIBUTE_SET_DETAIL_SAVED');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=attribute_set_detail&task=edit&cid[]=' . $row->attribute_set_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=attribute_set', $msg);
		}
	}

	/**
	 * Method for save attribute
	 *
	 * @param $post
	 * @param $row
	 *
	 * @return  void
	 */
	public function attribute_save($post, $row)
	{
		/** @var RedshopModelAttribute_set_detail $model */
		$model = $this->getModel('attribute_set_detail');

		$attributesSave    = array();
		$propertiesSave    = array();
		$subPropertiesSave = array();

		if (!is_array($post['attribute']))
		{
			return;
		}

		$attribute = array_merge(array(), $post['attribute']);

		$files = $this->input->files->getArray();

		for ($a = 0, $countAttribute = count($attribute); $a < $countAttribute; $a++)
		{
			$attributesSave['attribute_id']             = $attribute[$a]['id'];
			$attributesSave['attribute_set_id']         = $row->attribute_set_id;
			$attributesSave['attribute_name']           = htmlspecialchars($attribute[$a]['name']);
			$attributesSave['attribute_description']    = $attribute[$a]['attribute_description'];
			$attributesSave['ordering']                 = $attribute[$a]['ordering'];
			$attributesSave['attribute_required']       = ($attribute[$a]['required'] == 'on' || $attribute[$a]['required'] == '1') ? '1' : '0';
			$attributesSave['allow_multiple_selection'] = ($attribute[$a]['allow_multiple_selection'] == 'on'
				|| $attribute[$a]['allow_multiple_selection'] == '1') ? '1' : '0';
			$attributesSave['hide_attribute_price']     = ($attribute[$a]['hide_attribute_price'] == 'on'
				|| $attribute[$a]['hide_attribute_price'] == '1') ? '1' : '0';
			$attributesSave['attribute_published']      = ($attribute[$a]['published'] == 'on' || $attribute[$a]['published'] == '1') ? '1' : '0';
			$attributesSave['display_type']             = $attribute[$a]['display_type'];
			$attribute_array                            = $model->store_attr($attributesSave);
			$property                                   = array_merge(array(), $attribute[$a]['property']);

			$propertyImages   = array_keys($attribute[$a]['property']);
			$tmpPropertyImage = array_merge(array(), $propertyImages);

			for ($p = 0, $countProperty = count($property); $p < $countProperty; $p++)
			{
				$propertiesSave['property_id']         = $property[$p]['property_id'];
				$propertiesSave['attribute_id']        = $attribute_array->attribute_id;
				$propertiesSave['property_name']       = htmlspecialchars($property[$p]['name']);
				$propertiesSave['property_price']      = $property[$p]['price'];
				$propertiesSave['oprand']              = $property[$p]['oprand'];
				$propertiesSave['property_number']     = $property[$p]['number'];
				$propertiesSave['property_image']      = $property[$p]['image'];
				$propertiesSave['ordering']            = $property[$p]['order'];
				$propertiesSave['setrequire_selected'] = ($property[$p]['req_sub_att'] == 'on' || $property[$p]['req_sub_att'] == '1') ? '1' : '0';
				$propertiesSave['setmulti_selected']   = ($property[$p]['multi_sub_att'] == 'on' || $property[$p]['multi_sub_att'] == '1') ? '1' : '0';
				$propertiesSave['setdefault_selected'] = ($property[$p]['default_sel'] == 'on' || $property[$p]['default_sel'] == '1') ? '1' : '0';
				$propertiesSave['setdisplay_type']     = $property[$p]['setdisplay_type'];
				$propertiesSave['property_published']  = ($property[$p]['published'] == 'on' || $property[$p]['published'] == '1') ? '1' : '0';
				$propertiesSave['extra_field']         = $property[$p]['extra_field'];
				$properties                            = $model->store_pro($propertiesSave);
				$propertyImage                         = $files['attribute_' . $a . '_property_' . $tmpPropertyImage[$p] . '_image'];

				if (empty($property[$p]['mainImage']))
				{
					if (!empty($propertyImage['name']))
					{
						$propertiesSave['property_image'] = $model->copy_image($propertyImage, 'product_attributes', $properties->property_id);
						$propertiesSave['property_id']    = $properties->property_id;
						$properties                       = $model->store_pro($propertiesSave);
					}
				}

				if (!empty($property[$p]['mainImage']))
				{
					$propertiesSave['property_image'] = $model->copy_image_from_path($property[$p]['mainImage'], 'product_attributes', $properties->property_id);
					$propertiesSave['property_id']    = $properties->property_id;
					$properties                       = $model->store_pro($propertiesSave);
				}

				$subProperties    = array_merge(array(), $property[$p]['subproperty']);
				$subPropertyTitle = $property[$p]['subproperty']['title'];
				$subpropertyImage = array_keys($property[$p]['subproperty']);
				unset($subpropertyImage[0]);
				$tmpimagename = array_merge(array(), $subpropertyImage);

				for ($sp = 0; $sp < count($subProperties) - 1; $sp++)
				{
					$subPropertiesSave['subattribute_color_id']     = $subProperties[$sp]['subproperty_id'];
					$subPropertiesSave['subattribute_color_name']   = $subProperties[$sp]['name'];
					$subPropertiesSave['subattribute_color_title']  = $subPropertyTitle;
					$subPropertiesSave['subattribute_color_price']  = $subProperties[$sp]['price'];
					$subPropertiesSave['oprand']                    = $subProperties[$sp]['oprand'];
					$subPropertiesSave['subattribute_color_image']  = $subProperties[$sp]['image'];
					$subPropertiesSave['subattribute_id']           = $properties->property_id;
					$subPropertiesSave['ordering']                  = $subProperties[$sp]['order'];
					$subPropertiesSave['subattribute_color_number'] = $subProperties[$sp]['number'];
					$subPropertiesSave['setdefault_selected']       = ($subProperties[$sp]['chk_propdselected'] == 'on'
						|| $subProperties[$sp]['chk_propdselected'] == '1') ? '1' : '0';
					$subPropertiesSave['subattribute_published']    = ($subProperties[$sp]['published'] == 'on'
						|| $subProperties[$sp]['published'] == '1') ? '1' : '0';
					$subPropertiesSave['extra_field']               = $subProperties[$sp]['extra_field'];
					$subproperty_array                              = $model->store_sub($subPropertiesSave);
					$subproperty_image                              = $files['attribute_' . $a . '_property_' . $p . '_subproperty_' . $tmpimagename[$sp] . '_image'];

					if (empty($subProperties[$sp]['mainImage']))
					{
						if (!empty($subproperty_image['name']))
						{
							$subPropertiesSave['subattribute_color_image'] = $model->copy_image($subproperty_image, 'subcolor', $subproperty_array->subattribute_color_id);
							$subPropertiesSave['subattribute_color_id']    = $subproperty_array->subattribute_color_id;
							$subproperty_array                             = $model->store_sub($subPropertiesSave);
						}
					}

					if (!empty($subProperties[$sp]['mainImage']))
					{
						$subPropertiesSave['subattribute_color_image'] = $model->copy_image_from_path(
							$subProperties[$sp]['mainImage'],
							'subcolor', $subproperty_array->subattribute_color_id
						);

						$subPropertiesSave['subattribute_color_id'] = $subproperty_array->subattribute_color_id;

						$model->store_sub($subPropertiesSave);
					}
				}
			}
		}

		return;
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		/** @var RedshopModelAttribute_set_detail $model */
		$model = $this->getModel('attribute_set_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . /** @scrutinizer ignore-deprecated */
				$model->getError() . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ATTRIBUTE_SET_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=attribute_set', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_ATTRIBUTE_SET_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=attribute_set', $msg);
	}

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

	public function property_more_img()
	{
		$uri = JURI::getInstance();

		$url = $uri->root();

		$post = $this->input->post->getArray();

		$main_img = (array) $this->input->files->get('property_main_img', array(), 'array');
		$sub_img  = (array) $this->input->files->get('property_sub_img', array(), 'array');

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		$filetype = strtolower(JFile::getExt($main_img['name']));

		$filetype_sub = strtolower(JFile::getExt($sub_img['name'][0]));

		if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg' && $main_img['name'] != ''
			&& $filetype_sub != 'png' && $filetype_sub != 'gif' && $filetype_sub != 'jpeg'
			&& $filetype_sub != 'jpg' && $sub_img['name'][0] != ''
		)
		{
			$msg  = JText::_("COM_REDSHOP_FILE_EXTENTION_WRONG_PROPERTY");
			$link = $url . "administrator/index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=" . $post['section_id']
				. "&cid=" . $post['cid'] . "&layout=property_images&showbuttons=1";
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

	public function deleteimage()
	{
		$uri = JURI::getInstance();

		$url = $uri->root();

		$mediaid    = $this->input->get('mediaid');
		$section_id = $this->input->get('section_id');
		$cid        = $this->input->get('cid');

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		if ($model->deletesubimage($mediaid))
		{
			$msg  = JText::_("COM_REDSHOP_PROPERTY_SUB_IMAGE_IS_DELETE");
			$link = $url . "administrator/index.php?tmpl=component&option=com_redshop&view=product_detail&section_id="
				. $section_id . "&cid=" . $cid . "&layout=property_images&showbuttons=1";
			$this->setRedirect($link, $msg);
		}
	}

	public function subattribute_color()
	{
		$post = $this->input->post->getArray();

		/** @var RedshopModelProduct_Detail $model */
		$model = $this->getModel('product_detail');

		$subattr_id = implode("','", $post['subattribute_color_id']);

		$subattr_diff = $model->subattr_diff($subattr_id, $post['section_id']);

		$model->delsubattr_diff($subattr_diff);

		$sub_img = $this->input->files->get('property_sub_img', 'array', 'array');

		$model->subattribute_color($post, $sub_img);

		?>
        <script language="javascript" type="text/javascript">
            window.parent.SqueezeBox.close();
        </script>
		<?php
	}

	public function removepropertyImage()
	{
		$get = $this->input->get->getArray();

		$pid = $get['pid'];

		/** @var RedshopModelAttribute_set_detail $model */
		$model = $this->getModel('attribute_set_detail');

		if ($model->removepropertyImage($pid))
		{
			echo "sucess";
		}

		JFactory::getApplication()->close();
	}

	public function removesubpropertyImage()
	{
		$get = $this->input->get->getArray();

		$pid = $get['pid'];

		/** @var RedshopModelAttribute_set_detail $model */
		$model = $this->getModel('attribute_set_detail');

		if ($model->removesubpropertyImage($pid))
		{
			echo "sucess";
		}

		JFactory::getApplication()->close();
	}

	public function saveAttributeStock()
	{
		$post = $this->input->post->getArray();

		/** @var RedshopModelAttribute_set_detail $model */
		$model = $this->getModel('attribute_set_detail');

		if ($model->SaveAttributeStockroom($post))
		{
			$msg = JText::_('COM_REDSHOP_STOCKROOM_ATTRIBUTE_XREF_SAVE');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKROOM_ATTRIBUTE_XREF');
		}

		$link = "index.php?tmpl=component&option=com_redshop&view=product_detail&section_id="
			. $post['section_id'] . "&cid=" . $post['cid'] . "&layout=productstockroom&property=" . $post['section'];

		$this->setRedirect($link, $msg);
	}

	public function copy()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		/** @var RedshopModelAttribute_set_detail $model */
		$model = $this->getModel('attribute_set_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_ATTRIBUTE_SET_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPY_ATTRIBUTE_SET');
		}

		$this->setRedirect('index.php?option=com_redshop&view=attribute_set', $msg);
	}
}
