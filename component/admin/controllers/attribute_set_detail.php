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

class RedshopControllerAttribute_set_detail extends RedshopController
{
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

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('attribute_set_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ATTRIBUTE_SET_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=attribute_set', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_ATTRIBUTE_SET_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=attribute_set', $msg);
	}

	public function attribute_save($post, $row)
	{
		$model = $this->getModel('attribute_set_detail');

		$attribute_save = array();
		$property_save = array();
		$subproperty_save = array();

		if (!is_array($post['attribute']))
		{
			return;
		}

		$attribute = array_merge(array(), $post['attribute']);

		$files = $this->input->files->getArray();

		for ($a = 0, $countAttribute = count($attribute); $a < $countAttribute; $a++)
		{
			$attribute_save['attribute_id'] = $attribute[$a]['id'];
			$attribute_save['attribute_set_id'] = $row->attribute_set_id;
			$attribute_save['attribute_name'] = htmlspecialchars($attribute[$a]['name']);
			$attribute_save['attribute_description'] = $attribute[$a]['attribute_description'];
			$attribute_save['ordering'] = $attribute[$a]['ordering'];
			$attribute_save['attribute_required'] = ($attribute[$a]['required'] == 'on' || $attribute[$a]['required'] == '1') ? '1' : '0';
			$attribute_save['allow_multiple_selection'] = ($attribute[$a]['allow_multiple_selection'] == 'on'
				|| $attribute[$a]['allow_multiple_selection'] == '1') ? '1' : '0';
			$attribute_save['hide_attribute_price'] = ($attribute[$a]['hide_attribute_price'] == 'on'
				|| $attribute[$a]['hide_attribute_price'] == '1') ? '1' : '0';
			$attribute_save['attribute_published'] = ($attribute[$a]['published'] == 'on' || $attribute[$a]['published'] == '1') ? '1' : '0';
			$attribute_save['display_type'] = $attribute[$a]['display_type'];
			$attribute_array = $model->store_attr($attribute_save);
			$property = array_merge(array(), $attribute[$a]['property']);

			$propertyImage = array_keys($attribute[$a]['property']);
			$tmpproptyimagename = array_merge(array(), $propertyImage);

			for ($p = 0, $countProperty = count($property); $p < $countProperty; $p++)
			{
				$property_save['property_id'] = $property[$p]['property_id'];
				$property_save['attribute_id'] = $attribute_array->attribute_id;
				$property_save['property_name'] = htmlspecialchars($property[$p]['name']);
				$property_save['property_price'] = $property[$p]['price'];
				$property_save['oprand'] = $property[$p]['oprand'];
				$property_save['property_number'] = $property[$p]['number'];
				$property_save['property_image'] = $property[$p]['image'];
				$property_save['ordering'] = $property[$p]['order'];
				$property_save['setrequire_selected'] = ($property[$p]['req_sub_att'] == 'on' || $property[$p]['req_sub_att'] == '1') ? '1' : '0';
				$property_save['setmulti_selected'] = ($property[$p]['multi_sub_att'] == 'on' || $property[$p]['multi_sub_att'] == '1') ? '1' : '0';
				$property_save['setdefault_selected'] = ($property[$p]['default_sel'] == 'on' || $property[$p]['default_sel'] == '1') ? '1' : '0';
				$property_save['setdisplay_type'] = $property[$p]['setdisplay_type'];
				$property_save['property_published'] = ($property[$p]['published'] == 'on' || $property[$p]['published'] == '1') ? '1' : '0';
				$property_save['extra_field'] = $property[$p]['extra_field'];
				$property_array = $model->store_pro($property_save);
				$property_image = $files['attribute_' . $a . '_property_' . $tmpproptyimagename[$p] . '_image'];

				if (empty($property[$p]['mainImage']))
				{
					if (!empty($property_image['name']))
					{
						$property_save['property_image'] = $model->copy_image($property_image, 'product_attributes', $property_array->property_id);
						$property_save['property_id'] = $property_array->property_id;
						$property_array = $model->store_pro($property_save);
					}
				}

				if (!empty($property[$p]['mainImage']))
				{
					$property_save['property_image'] = $model->copy_image_from_path($property[$p]['mainImage'], 'product_attributes', $property_array->property_id);
					$property_save['property_id'] = $property_array->property_id;
					$property_array = $model->store_pro($property_save);
				}

				$subproperty = array_merge(array(), $property[$p]['subproperty']);
				$subproperty_title = $property[$p]['subproperty']['title'];
				$subpropertyImage = array_keys($property[$p]['subproperty']);
				unset($subpropertyImage[0]);
				$tmpimagename = array_merge(array(), $subpropertyImage);

				for ($sp = 0; $sp < count($subproperty) - 1; $sp++)
				{
					$subproperty_save['subattribute_color_id'] = $subproperty[$sp]['subproperty_id'];
					$subproperty_save['subattribute_color_name'] = $subproperty[$sp]['name'];
					$subproperty_save['subattribute_color_title'] = $subproperty_title;
					$subproperty_save['subattribute_color_price'] = $subproperty[$sp]['price'];
					$subproperty_save['oprand'] = $subproperty[$sp]['oprand'];
					$subproperty_save['subattribute_color_image'] = $subproperty[$sp]['image'];
					$subproperty_save['subattribute_id'] = $property_array->property_id;
					$subproperty_save['ordering'] = $subproperty[$sp]['order'];
					$subproperty_save['subattribute_color_number'] = $subproperty[$sp]['number'];
					$subproperty_save['setdefault_selected'] = ($subproperty[$sp]['chk_propdselected'] == 'on'
						|| $subproperty[$sp]['chk_propdselected'] == '1') ? '1' : '0';
					$subproperty_save['subattribute_published'] = ($subproperty[$sp]['published'] == 'on'
						|| $subproperty[$sp]['published'] == '1') ? '1' : '0';
					$subproperty_save['extra_field'] = $subproperty[$sp]['extra_field'];
					$subproperty_array = $model->store_sub($subproperty_save);
					$subproperty_image = $files['attribute_' . $a . '_property_' . $p . '_subproperty_' . $tmpimagename[$sp] . '_image'];

					if (empty($subproperty[$sp]['mainImage']))
					{
						if (!empty($subproperty_image['name']))
						{
							$subproperty_save['subattribute_color_image'] = $model->copy_image($subproperty_image, 'subcolor', $subproperty_array->subattribute_color_id);
							$subproperty_save['subattribute_color_id'] = $subproperty_array->subattribute_color_id;
							$subproperty_array = $model->store_sub($subproperty_save);
						}
					}

					if (!empty($subproperty[$sp]['mainImage']))
					{
						$subproperty_save['subattribute_color_image'] = $model->copy_image_from_path(
							$subproperty[$sp]['mainImage'],
							'subcolor', $subproperty_array->subattribute_color_id
						);
						$subproperty_save['subattribute_color_id'] = $subproperty_array->subattribute_color_id;
						$subproperty_array = $model->store_sub($subproperty_save);
					}
				}
			}
		}

		return;
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

		$width = round($width * $percentage);
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

		$main_img = $this->input->files->get('property_main_img', 'array', 'array');

		$sub_img = $this->input->files->get('property_sub_img', 'array', 'array');

		$model = $this->getModel('product_detail');

		$filetype = strtolower(JFile::getExt($main_img['name']));

		$filetype_sub = strtolower(JFile::getExt($sub_img['name'][0]));

		if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg' && $main_img['name'] != ''
			&& $filetype_sub != 'png' && $filetype_sub != 'gif' && $filetype_sub != 'jpeg'
			&& $filetype_sub != 'jpg' && $sub_img['name'][0] != ''
		)
		{
			$msg = JText::_("COM_REDSHOP_FILE_EXTENTION_WRONG_PROPERTY");
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

		$model = $this->getModel('product_detail');

		if ($model->deletesubimage($mediaid))
		{
			$msg = JText::_("COM_REDSHOP_PROPERTY_SUB_IMAGE_IS_DELETE");
			$link = $url . "administrator/index.php?tmpl=component&option=com_redshop&view=product_detail&section_id="
				. $section_id . "&cid=" . $cid . "&layout=property_images&showbuttons=1";
			$this->setRedirect($link, $msg);
		}
	}

	public function subattribute_color()
	{
		$post = $this->input->post->getArray();

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
		$model = $this->getModel('attribute_set_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPING_CATEGORY');
		}

		$this->setRedirect('index.php?option=com_redshop&view=attribute_set', $msg);
	}
}
