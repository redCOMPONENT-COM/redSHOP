<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The Catalogs view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View.Catalogs
 * @since       2.1.2
 */
class RedshopViewCatalogs extends RedshopViewList
{
	/**
	 * Method for render 'Media' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   2.1.2
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if ($config['dataCol'] == 'media')
		{
			$model = $this->getModel('catalogs');
			$mediaDetail = $model->mediaDetail($row->catalog_id);
			echo '<a class="joom-box"
				href="index.php?tmpl=component&option=com_redshop&view=media&section_id='
				. $row->catalog_id . '&showbuttons=1&media_section=catalog&section_name=' . $row->catalog_name . '"
				rel="{handler: \'iframe\', size: {x: 1050, y: 450}}" title="">
				<img src="' . REDSHOP_MEDIA_IMAGES_ABSPATH . 'media16.png" align="absmiddle" alt="media">(' . count($mediaDetail) . ')</a>';
		}

		return parent::onRenderColumn($config, $index, $row);
	}
}
