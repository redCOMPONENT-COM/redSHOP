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

		$this->initUserState($data);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.media', $data);

		return $data;
	}

    /**
     * init User State
     *
     * @param   array  &data  Data Array
     *
     * @return void
     */
    public function initUserState(&$data)
    {
        $app = JFactory::getApplication();
        $section = $app->input->get('section', null);
        $sectionId = $app->input->get('section_id', null);

        if (isset($section))
        {
            $data['section'] = $section;
        }

        if (isset($sectionId))
        {
            $data['section_id'] = (int) $sectionId;
        }
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
        $fileName = trim(basename($data['name']));

        /* In case Upload File*/
        if ($fileName !== '')
        {
            $data['name'] = RedshopHelperMedia::cleanFileName($fileName);
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

        if (isset($data['scope']))
        {
            $table->scope = trim($data['scope']);
        }

	    if (!$table->store($data))
        {
            return false;
        }

        if (isset($table->id) && ($table->id > 0) && isset($data['name']) && ($data['type'] != 'youtube'))
        {
            try
            {
                $src = JPATH_ROOT . '/media/com_redshop/files/tmp/' . $fileName;

                if (JFile::exists($src))
                {
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

        return $table;
	}
}
