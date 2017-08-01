<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopControllerManufacturer_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'manufacturer_detail');
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
		$manufacturer_desc = $this->input->post->get('manufacturer_desc', '', 'raw');
		$post["manufacturer_desc"] = $manufacturer_desc;

		$cid = $this->input->post->get('cid', array(0), 'array');

		$post['manufacturer_id'] = $cid[0];

		$model = $this->getModel('manufacturer_detail');

		if ($row = $model->store($post))
		{
			RedshopHelperExtrafields::extraFieldSave($post, "10", $row->manufacturer_id);

			$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_SAVED');

			// Working on media files of this manufacturer
			$mediaTable = JTable::getInstance('Media_detail', 'Table');

			// If force to delete media image of manufacturer. Delete this media.
			if (isset($post['manufacturer_image_delete'])
				&& $mediaTable->load(array('section_id' => $row->manufacturer_id, 'media_section' => 'manufacturer')))
			{
				$mediaTable->delete();
			}
			// If there are new image.
			elseif (!empty($post['manufacturer_image']))
			{
				// Try to load media associate with this manufacturer
				if ($mediaTable->load(array('section_id' => $row->manufacturer_id, 'media_section' => 'manufacturer')))
				{
					// Delete old image.
					$oldMediaFile = REDSHOP_FRONT_IMAGES_RELPATH . 'manufacturer/' . $mediaTable->media_name;
					JFile::delete($oldMediaFile);
				}
				else
				{
					$mediaTable->set('section_id', $row->manufacturer_id);
					$mediaTable->set('media_section', 'manufacturer');
				}

				$mediaTable->set('media_alternate_text', $this->input->getString('media_alternate_text', ''));
				$mediaTable->set('media_type', 'images');
				$mediaTable->set('published', 1);

				// Copy new image for this media
				$fileName = basename($post['manufacturer_image']);
				copy(JPATH_ROOT . '/' . $post['manufacturer_image'], REDSHOP_FRONT_IMAGES_RELPATH . 'manufacturer/' . $fileName);
				$mediaTable->set('media_name', $fileName);
				$mediaTable->store();
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MANUFACTURER_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=manufacturer_detail&task=edit&cid[]=' . $row->manufacturer_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('manufacturer_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
	}

	public function copy()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		$model = $this->getModel('manufacturer_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPING_MANUFACTURER_DETAIL');
		}

		$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
	}
}
