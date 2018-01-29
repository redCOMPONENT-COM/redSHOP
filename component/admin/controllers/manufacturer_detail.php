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

	/**
	 * Method for save manufacturer
	 *
	 * @param   integer $apply Apply or not.
	 *
	 * @throws  Exception
	 *
	 * @return void
	 */
	public function save($apply = 0)
	{
		$post                      = $this->input->post->getArray();
		$post["manufacturer_desc"] = $this->input->post->get('manufacturer_desc', '', 'raw');
		$cid                       = $this->input->post->get('cid', array(0), 'array');
		$post['manufacturer_id']   = $cid[0];

		/** @var RedshopModelManufacturer_detail $model */
		$model = $this->getModel('manufacturer_detail');
		$row   = $model->store($post);

		if ($row)
		{
			RedshopHelperExtrafields::extraFieldSave($post, "10", $row->manufacturer_id);
			$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_SAVED');

			// Working on media files of this manufacturer
			$dropzone = isset($post['dropzone']) && !empty($post['dropzone']['manufacturer_image'])
				? $post['dropzone']['manufacturer_image'] : null;

			if (!empty($dropzone))
			{
				foreach ($dropzone as $key => $value)
				{
					/** @var RedshopTableMedia $mediaTable */
					$mediaTable = JTable::getInstance('Media', 'RedshopTable');

					if (strpos($key, 'media-') !== false)
					{
						$mediaTable->load(str_replace('media-', '', $key));

						// Delete old image.
						$oldMediaFile = JPath::clean(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/'
							. $row->manufacturer_id . '/' . $mediaTable->media_name
						);

						if (JFile::exists($oldMediaFile))
						{
							JFile::delete($oldMediaFile);
						}

						if (empty($value))
						{
							$mediaTable->delete();

							continue;
						}
					}
					else
					{
						$mediaTable->set('section_id', $row->manufacturer_id);
						$mediaTable->set('media_section', 'manufacturer');
					}

					$alternateText = $this->input->getString('media_alternate_text', '');
					$alternateText = empty($alternateText) ? $row->manufacturer_name : $alternateText;

					$mediaTable->set('media_alternate_text', $alternateText);
					$mediaTable->set('media_type', 'images');
					$mediaTable->set('published', 1);

					// Copy new image for this media
					$fileName = md5($row->manufacturer_name) . '.' . JFile::getExt($value);
					JFile::move(
						JPATH_ROOT . '/' . $value,
						REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $row->manufacturer_id . '/' . $fileName
					);
					$mediaTable->set('media_name', $fileName);
					$mediaTable->store();

					// Optimize image
					$factory   = new \ImageOptimizer\OptimizerFactory;
					$optimizer = $factory->get();
					$optimizer->optimize(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $row->manufacturer_id . '/' . $fileName);
				}

				// Clear thumbnail folder
				JFolder::delete(JPath::clean(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $row->manufacturer_id . '/thumb'));
				JFolder::create(JPath::clean(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $row->manufacturer_id . '/thumb'));
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

		/** @var RedshopModelManufacturer_detail $model */
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

		/** @var RedshopModelManufacturer_detail $model */
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
