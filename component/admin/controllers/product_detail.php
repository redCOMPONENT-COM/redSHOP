<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

require_once JPATH_COMPONENT . '/helpers/thumbnail.php';
require_once JPATH_COMPONENT . '/helpers/product.php';

class product_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'product_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function save2new()
	{
		$this->save(2);
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['product_id'] = $cid [0];
		$stockroom_id = '';

		if (is_array($post['product_category']) && !in_array($post['cat_in_sefurl'], $post['product_category']))
		{
			$post['cat_in_sefurl'] = $post['product_category'][0];
		}

		if (!$post ['product_id'])
		{
			$post ['publish_date'] = date("Y-m-d H:i:s");
		}

		$post ['discount_stratdate'] = strtotime($post ['discount_stratdate']);

		if ($post ['discount_enddate'])
		{
			$post ['discount_enddate'] = strtotime($post ['discount_enddate']) + (23 * 59 * 59);
		}

		$post ['product_availability_date'] = strtotime($post ['product_availability_date']);
		$post["product_s_desc"] = JRequest::getVar('product_s_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["product_desc"] = JRequest::getVar('product_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["product_parent_id"] = trim($post["parent"]) == "" ? 0 : $post["product_parent_id"];

		$container_id = JRequest::getVar('container_id', '', 'request', 'string');

		if (USE_CONTAINER == 1)
		{
			$stockroom_id = JRequest::getVar('stockroom_id', '', 'request', 'string');
		}

		require_once JPATH_COMPONENT . '/helpers/extra_field.php';

		$model = $this->getModel('product_detail');

		if ($row = $model->store($post))
		{
			// Save Association
			$model->SaveAssociations($row->product_id, $post);

			// Add product to economic
			if (ECONOMIC_INTEGRATION == 1)
			{
				$economic = new economic;
				$economic->createProductInEconomic($row);
			}

			$field = new extra_field;

			// Field_section 1 :Product
			$field->extra_field_save($post, 1, $row->product_id);

			// Field_section 12 :Product Userfield
			$field->extra_field_save($post, 12, $row->product_id);

			// Field_section 12 :Productfinder datepicker
			$field->extra_field_save($post, 17, $row->product_id);
			$file = JRequest::getVar('image', 'array', 'files', 'array');

			$this->attribute_save($post, $row, $file);

			// Extra Field Data Saved
			$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_SAVED');

			if ($container_id != '' || $stockroom_id != '')
			{
				?>
            <script language="javascript" type="text/javascript">
					<?php
					if ($container_id)
					{
						$link = 'index.php?option=' . $option . '&view=container_detail&task=edit&cid[]=' . $container_id;
					}

					if ($stockroom_id && USE_CONTAINER == 1)
					{
						$link = 'index.php?option=' . $option . '&view=stockroom_detail&task=edit&cid[]=' . $stockroom_id;
					}
					?>
                window.parent.document.location = '<?php echo $link; ?>';
            </script>
			<?php
				exit;
			}

			if ($apply == 2)
			{
				$this->setRedirect('index.php?option=' . $option . '&view=product_detail&task=add', $msg);
			}

			elseif ($apply == 1)
			{
				$this->setRedirect('index.php?option=' . $option . '&view=product_detail&task=edit&cid[]=' . $row->product_id, $msg);
			}
			else
			{
				$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
			}
		}
		else
		{
			$row->product_id = $post ['product_id'];
			$msg = $model->getError();

			JError::raiseWarning(404, $msg);

			JRequest::setVar('view', 'product_detail');
			JRequest::setVar('layout', 'default');
			JRequest::setVar('hidemainmenu', 1);

			parent::display();
		}
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('product_detail');

		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_DELETED_SUCCESSFULLY');

		if (!$model->delete($cid))
		{
			$msg = "";

			if ($model->getError() != "")
			{
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('product_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('product_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$model = $this->getModel('product_detail');
		$model->checkin();
		$msg = JText::_('COM_REDSHOP_PRODUCT_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}

	public function copy()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('product_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_PRODUCT_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_PRODUCT_COPIED');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}

	public function attribute_save($post, $row, $file)
	{
		if (ECONOMIC_INTEGRATION == 1 && ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC != 0)
		{
			$economic = new economic;
		}

		$model = $this->getModel('product_detail');

		$attribute_save = array();
		$property_save = array();
		$subproperty_save = array();

		if (!is_array($post['attribute']))
		{
			return;
		}

		$attribute = array_merge(array(), $post['attribute']);

		$files = JRequest::get('files');

		for ($a = 0; $a < count($attribute); $a++)
		{
			$attribute_save['attribute_id'] = $attribute[$a]['id'];
			$tmpordering = ($attribute[$a]['tmpordering']) ? $attribute[$a]['tmpordering'] : $a;
			$attribute_save['product_id'] = $row->product_id;
			$attribute_save['attribute_name'] = urldecode($attribute[$a]['name']);
			$attribute_save['ordering'] = $attribute[$a]['ordering'];
			$attribute_save['attribute_published'] = ($attribute[$a]['published'] == 'on' || $attribute[$a]['published'] == '1') ? '1' : '0';
			$attribute_save['attribute_required'] = ($attribute[$a]['required'] == 'on' || $attribute[$a]['required'] == '1') ? '1' : '0';
			$attribute_save['allow_multiple_selection'] = ($attribute[$a]['allow_multiple_selection'] == 'on'
				|| $attribute[$a]['allow_multiple_selection'] == '1') ? '1' : '0';
			$attribute_save['hide_attribute_price'] = ($attribute[$a]['hide_attribute_price'] == 'on'
				|| $attribute[$a]['hide_attribute_price'] == '1') ? '1' : '0';
			$attribute_save['display_type'] = $attribute[$a]['display_type'];
			$attribute_array = $model->store_attr($attribute_save);
			$property = array_merge(array(), $attribute[$a]['property']);

			$propertyImage = array_keys($attribute[$a]['property']);
			$tmpproptyimagename = array_merge(array(), $propertyImage);

			for ($p = 0; $p < count($property); $p++)
			{
				$property_save['property_id'] = $property[$p]['property_id'];
				$property_save['attribute_id'] = $attribute_array->attribute_id;
				$property_save['property_name'] = urldecode($property[$p]['name']);
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
				$property_id = $property_array->property_id;
				$property_image = $files['attribute_' . $tmpordering . '_property_' . $tmpproptyimagename[$p] . '_image'];

				if (empty($property[$p]['mainImage']))
				{
					if (!empty($property_image['name']))
					{
						$property_save['property_image'] = $model->copy_image($property_image, 'product_attributes', $property_id);
						$property_save['property_id'] = $property_id;
						$property_array = $model->store_pro($property_save);
						$this->DeleteMergeImages();
					}
				}

				if (!empty($property[$p]['mainImage']))
				{
					$property_save['property_image'] = $model->copy_image_from_path($property[$p]['mainImage'], 'product_attributes', $property_id);
					$property_save['property_id'] = $property_id;
					$property_array = $model->store_pro($property_save);
					$this->DeleteMergeImages();
				}

				if (empty($property[$p]['property_id']))
				{
					$listImages = $model->GetimageInfo($property_id, 'property');

					for ($li = 0; $li < count($listImages); $li++)
					{
						$mImages = array();
						$mImages['media_name'] = $listImages[$li]->media_name;
						$mImages['media_alternate_text'] = $listImages[$li]->media_alternate_text;
						$mImages['media_section'] = 'property';
						$mImages['section_id'] = $property_id;
						$mImages['media_type'] = 'images';
						$mImages['media_mimetype'] = $listImages[$li]->media_mimetype;
						$mImages['published'] = $listImages[$li]->published;
						$model->copyadditionalImage($mImages);
					}
				}

				if (ECONOMIC_INTEGRATION == 1 && ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC != 0)
				{
					$economic->createPropertyInEconomic($row, $property_array);
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
					$subproperty_save['subattribute_id'] = $property_id;
					$subproperty_save['ordering'] = $subproperty[$sp]['order'];
					$subproperty_save['subattribute_color_number'] = $subproperty[$sp]['number'];
					$subproperty_save['setdefault_selected'] = ($subproperty[$sp]['chk_propdselected'] == 'on'
						|| $subproperty[$sp]['chk_propdselected'] == '1') ? '1' : '0';
					$subproperty_save['subattribute_published'] = ($subproperty[$sp]['published'] == 'on'
						|| $subproperty[$sp]['published'] == '1') ? '1' : '0';
					$subproperty_save['extra_field'] = $subproperty[$sp]['extra_field'];
					$subproperty_array = $model->store_sub($subproperty_save);
					$subproperty_image = $files['attribute_' . $tmpordering . '_property_' . $p . '_subproperty_' . $tmpimagename[$sp] . '_image'];
					$subproperty_id = $subproperty_array->subattribute_color_id;

					if (empty($subproperty[$sp]['mainImage']))
					{
						if (!empty($subproperty_image['name']))
						{
							$subproperty_save['subattribute_color_image'] = $model->copy_image($subproperty_image, 'subcolor', $subproperty_id);
							$subproperty_save['subattribute_color_id'] = $subproperty_id;
							$subproperty_array = $model->store_sub($subproperty_save);
							$this->DeleteMergeImages();
						}
					}

					if (!empty($subproperty[$sp]['mainImage']))
					{
						$subproperty_save['subattribute_color_image'] = $model->copy_image_from_path($subproperty[$sp]['mainImage'], 'subcolor', $subproperty_id);
						$subproperty_save['subattribute_color_id'] = $subproperty_id;
						$subproperty_array = $model->store_sub($subproperty_save);
						$this->DeleteMergeImages();
					}

					if (empty($subproperty[$sp]['subproperty_id']))
					{
						$listsubpropImages = $model->GetimageInfo($subproperty_id, 'subproperty');

						for ($lsi = 0; $lsi < count($listsubpropImages); $lsi++)
						{
							$smImages = array();
							$smImages['media_name'] = $listsubpropImages[$lsi]->media_name;
							$smImages['media_alternate_text'] = $listsubpropImages[$lsi]->media_alternate_text;
							$smImages['media_section'] = 'subproperty';
							$smImages['section_id'] = $subproperty_id;
							$smImages['media_type'] = 'images';
							$smImages['media_mimetype'] = $listsubpropImages[$lsi]->media_mimetype;
							$smImages['published'] = $listsubpropImages[$lsi]->published;
							$model->copyadditionalImage($smImages);
						}
					}

					if (ECONOMIC_INTEGRATION == 1 && ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC != 0)
					{
						$economic->createSubpropertyInEconomic($row, $subproperty_array);
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

	public function media_bank()
	{
		$uri = JURI::getInstance();
		$url = $uri->root();

		$folder_path = JRequest::getVar('path', '');
		$dirpath = JRequest::getVar('dirpath', '');

		if (!$folder_path)
		{
			$path = REDSHOP_FRONT_IMAGES_RELPATH;
			$dir_path = "components/com_redshop/assets/images";
		}
		else
		{
			$path = $folder_path;
			$dir_path = $dirpath;
		}

		$files = JFolder::listFolderTree($path, '.', 1);
		$tbl = '';
		$tbl .= "<table cellspacing='7' cellpdding='2' width='100%' border='0'>";
		$tbl .= "<tr>";

		if ($folder_path)
		{
			$t = preg_split('/', $folder_path);
			$na = count($t) - 1;
			$n = count($t) - 2;

			if ($t[$n] != 'assets')
			{
				if ($t[$n] == 'images')
				{
					$path_bk = REDSHOP_FRONT_IMAGES_RELPATH;
					$dir_path = "components/com_redshop/assets/images/" .$t[$na];
				}
				else
				{
					$path_bk = REDSHOP_FRONT_IMAGES_RELPATH . $t[$n];
					$dir_path = "components/com_redshop/assets/images/" .$t[$n] . "/" . $t[$na];
				}

				$folder_img_bk = "components/com_redshop/assets/images/folderup_32.png";

				$info = @getimagesize($folder_img_bk);

				$width = @$info[0];
				$height = @$info[1];

				if (($info[0] > 50) || ($info[1] > 50))
				{
					$dimensions = $this->_imageResize($info[0], $info[1], 50);

					$width_60 = $dimensions[0];
					$height_60 = $dimensions[1];
				}
				else
				{
					$width_60 = $width;
					$height_60 = $height;
				}

				$link_bk = "index.php?tmpl=component&option=com_redshop&view=product_detail&task=media_bank&path=" . $path_bk
					. "&dirpath=" . $dir_path;
				$tbl .= "<td width='25%'><table width='120' height='70' style='background-color:#C0C0C0;' cellspacing='1'
				cellpdding='1'><tr><td align='center' style='background-color:#FFFFFF;'><a href='" . $link_bk . "'>
				<img src='" . $folder_img_bk . "' width='" . $width_60 . "' height='" . $height_60 . "'></a></td></tr><
				tr height='15'><td style='background-color:#F7F7F7;' align='center'><label>Up</label></td></tr></table></td></tr><tr>";
			}
			else
			{
				$dir_path = "components/com_redshop/assets/images";
			}
		}

		if ($handle = opendir($path))
		{
			$folder_img = "components/com_redshop/assets/images/folder.png";

			$info = @getimagesize($folder_img);

			$width = @$info[0];
			$height = @$info[1];

			if (($info[0] > 50) || ($info[1] > 50))
			{
				$dimensions = $this->_imageResize($info[0], $info[1], 50);

				$width_60 = $dimensions[0];
				$height_60 = $dimensions[1];
			}
			else
			{
				$width_60 = $width;
				$height_60 = $height;
			}

			$j = 1;

			for ($f = 0; $f < count($files); $f++)
			{
				$link = "index.php?tmpl=component&option=com_redshop&view=product_detail&task=media_bank&folder=1&path="
					. $files[$f]['fullname'] . "&dirpath=" . $files[$f]['relname'];
				$tbl .= "<td width='25%'><table width='120' height='70' style='background-color:#C0C0C0;' cellspacing='1' cellpdding='1'><tr>
				<td align='center' style='background-color:#FFFFFF;'><a href='" . $link . "'><img src='" . $folder_img . "' width='"
					. $width_60 . "' height='" . $height_60 . "'></a></tr><tr height='15'><td style='background-color:#F7F7F7;' align='center'>
					<label>" . $files[$f]['name'] . "</label></td></tr></table></td>";

				if ($j % 4 == 0)
				{
					$tbl .= "</tr><tr>";
				}

				$j++;
			}

			$i = $j;

			while (false !== ($filename = readdir($handle)))
			{
				if (preg_match("/.jpg/", $filename) || preg_match("/.gif/", $filename) || preg_match("/.png/", $filename))
				{
					$live_path = $url . $dir_path . DS . $filename;

					$info = @getimagesize($live_path);

					$width = @$info[0];
					$height = @$info[1];

					if (($info[0] > 50) || ($info[1] > 50))
					{
						$dimensions = $this->_imageResize($info[0], $info[1], 50);

						$width_60 = $dimensions[0];
						$height_60 = $dimensions[1];
					}
					else
					{
						$width_60 = $width;
						$height_60 = $height;
					}

					$tbl .= "<td width='25%'><table width='120' height='70' style='background-color:#C0C0C0;' cellspacing='1' cellpdding='1'>
					<tr><td align='center' style='background-color:#FFFFFF;'>
					<a href=\"javascript:window.parent.jimage_insert('" . $dir_path . DS . $filename . "');window.parent.SqueezeBox.close();\">
					<img width='" . $width_60 . "' height='" . $height_60 . "' alt='" . $filename . "' src='" . $live_path . "'></a></td>
					</tr><tr height='15'><td style='background-color:#F7F7F7;' align='center'><label>" . substr($filename, 0, 10) . "</label>
					</td></tr></table></td>";

					if ($i % 4 == 0)
					{
						$tbl .= "</tr><tr>";
					}

					$i++;
				}
			}

			$tbl .= '</tr></table>';
			echo $tbl;
			closedir($handle);
		}
	}

	public function property_more_img()
	{
		$uri = JURI::getInstance();

		$url = $uri->root();

		$post = JRequest::get('post');

		$main_img = JRequest::getVar('property_main_img', 'array', 'files', 'array');

		$sub_img = JRequest::getVar('property_sub_img', 'array', 'files', 'array');

		$model = $this->getModel('product_detail');

		$filetype = strtolower(JFile::getExt($main_img['name']));

		$filetype_sub = strtolower(JFile::getExt($sub_img['name'][0]));

		if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg'
			&& $main_img['name'] != '' && $filetype_sub != 'png' && $filetype_sub != 'gif'
			&& $filetype_sub != 'jpeg' && $filetype_sub != 'jpg' && $sub_img['name'][0] != '')
		{
			$msg = JText::_("COM_REDSHOP_FILE_EXTENTION_WRONG_PROPERTY");
			$link = $url . "administrator/index.php?tmpl=component&option=com_redshop&view=product_detail&section_id="
				. $post['section_id'] . "&cid=" . $post['cid'] . "&layout=property_images&showbuttons=1";
			$this->setRedirect($link, $msg);
		}
		else
		{
			$more_images = $model->property_more_img($post, $main_img, $sub_img);
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

		$mediaid = JRequest::getVar('mediaid');
		$section_id = JRequest::getVar('section_id');
		$cid = JRequest::getVar('cid');

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
		$post = JRequest::get('post');

		$model = $this->getModel('product_detail');

		$subattr_id = implode("','", $post['subattribute_color_id']);

		$subattr_diff = $model->subattr_diff($subattr_id, $post['section_id']);

		// Delete subAttribute Diffrence
		$model->delsubattr_diff($subattr_diff);

		$sub_img = JRequest::getVar('property_sub_img', 'array', 'files', 'array');

		$more_images = $model->subattribute_color($post, $sub_img);

		?>
    <script language="javascript" type="text/javascript">
        window.parent.SqueezeBox.close();
    </script>
	<?php
	}

	public function removepropertyImage()
	{
		$get = JRequest::get('get');

		$pid = $get['pid'];

		$model = $this->getModel();

		if ($model->removepropertyImage($pid))
		{
			echo "sucess";
		}

		exit;
	}

	public function removesubpropertyImage()
	{
		$get = JRequest::get('get');

		$pid = $get['pid'];

		$model = $this->getModel();

		if ($model->removesubpropertyImage($pid))
		{
			echo "sucess";
		}

		exit;
	}

	public function saveAttributeStock()
	{
		$post = JRequest::get('post');

		$model = $this->getModel();

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

	public function orderup()
	{
		$option = JRequest::getVar('option');

		$model = $this->getModel('product_detail');

		$model->orderup();

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}

	public function orderdown()
	{
		$option = JRequest::getVar('option');

		$model = $this->getModel('product_detail');

		$model->orderdown();
		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}

	public function saveorder()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('product_detail');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}

	public function deleteProdcutSerialNumbers()
	{
		$serial_id = JRequest::getInt('serial_id');
		$product_id = JRequest::getInt('product_id');
		$option = JRequest::getVar('option');

		$model = $this->getModel('product_detail');
		$model->deleteProdcutSerialNumbers($serial_id);

		$msg = JText::_('COM_REDSHOP_PRODUCT_SERIALNUMBER_DELETED');
		$this->setRedirect('index.php?option=' . $option . '&view=product_detail&cid=' . $product_id, $msg);
	}

	public function delete_subprop()
	{
		$get = JRequest::get('get');
		$model = $this->getModel('product_detail');
		$model->delete_subprop($get['sp_id'], $get['subattribute_id']);
	}

	public function delete_prop()
	{
		$get = JRequest::get('get');
		$model = $this->getModel('product_detail');
		$model->delete_prop($get['attribute_id'], $get['property_id']);
	}

	public function delete_attibute()
	{
		$get = JRequest::get('get');
		$model = $this->getModel('product_detail');

		$model->delete_attibute($get['product_id'], $get['attribute_id'], $get['attribute_set_id']);
	}

	public function checkVirtualNumber()
	{
		$isExists = true;
		$str = JRequest::getVar('str');
		$strArr = explode(",", $str);
		$product_id = JRequest::getVar('product_id');
		$result = array_unique($strArr);

		if (count($result) > 0 && count($result) == count($strArr))
		{
			$model = $this->getModel('product_detail');
			$isExists = $model->checkVirtualNumber($product_id, $result);
		}

		echo (int) $isExists;
		die();
	}

	/**
	 * function to get all child product array
	 * for ajax call
	 */
	public function getChildProducts()
	{
		ob_clean();
		$model = $this->getModel('product_detail');
		$prod = $model->getChildProducts();

		echo implode(",", $prod->id) . ":" . implode(",", $prod->name);
		exit;
	}

	public function removeaccesory()
	{
		$accessory_id = JRequest::getInt('accessory_id', '');
		$category_id = JRequest::getInt('category_id', '');
		$child_product_id = JRequest::getInt('child_product_id', '');
		$model = $this->getModel('product_detail');
		$model->removeaccesory($accessory_id, $category_id, $child_product_id);
		exit;
	}

	public function removenavigator()
	{
		$navigator_id = JRequest::getInt('navigator_id', '');
		$model = $this->getModel('product_detail');
		$model->removenavigator($navigator_id);

		exit;
	}

	public function ResetPreorderStock()
	{
		$model = $this->getModel('product_detail');
		$stockroom_type = JRequest::getVar('stockroom_type', 'product');
		$pid = JRequest::getVar('product_id');
		$sid = JRequest::getVar('stockroom_id');

		$model->ResetPreOrderStockroomQuantity($stockroom_type, $sid[$i], $pid);

		$this->setRedirect('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $pid);
	}

	public function ResetPreorderStockBank()
	{
		$model = $this->getModel('product_detail');
		$stockroom_type = JRequest::getVar('stockroom_type', 'product');
		$section_id = JRequest::getVar('section_id');

		$cid = JRequest::getVar('cid');
		$sid = JRequest::getVar('stockroom_id');

		$model->ResetPreOrderStockroomQuantity($stockroom_type, $sid, $section_id);

		$link = "index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=" . $section_id . "&cid="
			. $cid . "&layout=productstockroom&property=" . $stockroom_type;
		$this->setRedirect($link);
	}

	public function getDynamicFields()
	{
		JRequest::setVar('view', 'product_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

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
}
