<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('filelist');

/**
 * Supports an HTML select list of image
 *
 * @since  11.1
 */
class RedshopFormFieldFontList extends JFormFieldFileList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'FontList';
	
	/**
	 * Method to get the list of images field options.
	 * Use the filter attribute to specify allowable file extensions.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$filter = "ttf";
		
		$path = JPATH_ROOT . '/media/com_redshop/fonts';
		
		$path = JPath::clean($path);
		
		$fontFile = JFolder::files($path, $filter);
		
		foreach ($fontFile as $file)
		{
			// Check to see if the file is in the exclude mask.
			if ($this->exclude)
			{
				if (preg_match(chr(1) . $this->exclude . chr(1), $file))
				{
					continue;
				}
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
