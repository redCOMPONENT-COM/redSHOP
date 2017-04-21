<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Media
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.4
 */

class RedshopModelMedium extends RedshopModelForm
{
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.media.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.media', $data);

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   12.2
	 */
	public function save($data)
	{
	    $app = JFactory::getApplication();
        $fileName = $app->getUserState('com_redshop.media.tmp.file.name', '');

        /* In case Upload File*/
        if (trim($fileName) !== '')
        {
            $data['name'] = $fileName;
        }

	    /* Case type Youtube */
	    if (isset($data['youtube_id']) && $type='youtube' && (trim($data['youtube_id']) !== ''))
        {
            $data['name'] = $data['youtube_id'];
        }

        if (!isset($data['youtube_id']))
        {
            $data['youtube_id'] = '';
        }

        $table = $this->getTable();

	    $table->id = $data['id'];
	    $table->name = $data['name'];
	    $table->title = $data['title'];
	    $table->youtube_id = $data['youtube_id'];
	    $table->alternate_text = $data['alternate_text'];
	    $table->section = $data['section'];
	    $table->type = $data['type'];
	    $table->published = $data['published'];

	    if (!$table->store($data))
        {
            return false;
        }

        if (isset($table->id) && ($table->id > 0) && isset($data['name']) && ($data['type'] != 'youtube'))
        {
            try
            {
                $src = JPATH_ROOT . '/media/com_redshop/files/tmp/' . $data['name'];
                var_dump($src);

                if (JFile::exists($src))
                {
                    echo 'adsfafd';
                    $des = JPATH_ROOT . '/media/com_redshop/files/' . $data['section'] . '/' . $table->id . '/';

                    if (!JFolder::exists($des))
                    {
                        JFolder::create($des);
                    }

                    JFile::move($src, $des . $data['name']);

                }
            }
            catch (Exception $e)
            {

            }
        }

        return $table->id;
	}
}
