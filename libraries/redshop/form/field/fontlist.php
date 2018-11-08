<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('filelist');

/**
 * Supports an HTML select list of image
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopFormFieldFontList extends JFormFieldFileList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $type = 'FontList';
	
	/**
	 * Method to get the list of images field options.
	 * Use the filter attribute to specify allowable file extensions.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getOptions()
	{
		$filter = "ttf";

		$path = JPATH_ROOT . '/media/com_redshop/fonts';

		$path = JPath::clean($path);

		$fontFile = JFolder::files($path, $filter);

		$options = array();

		foreach ($fontFile as $file)
		{
			// Check to see if the file is in the exclude mask.
			if ($this->exclude && preg_match(chr(1) . $this->exclude . chr(1), $file))
			{
				continue;
			}

			// If the extension is to be stripped, do it.
			if ($this->stripExt)
			{
				$file = JFile::stripExt($file);
			}

			$options[] = JHtml::_('select.option', 'ttf.' . $file, $file);
		}

		return array_merge(parent::getOptions(), $options);
	}
}
