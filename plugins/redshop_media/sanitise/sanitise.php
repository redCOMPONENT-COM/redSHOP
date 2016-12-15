<?php
/**
 * @package     RedSHOP.Plugins
 * @subpackage  DotPay
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Sanitise media's files
 *
 * @package     Redshop.Plugins
 * @subpackage  Media
 * @since       2.0.0.6
 */
class PlgRedshop_MediaSanitise extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   2.0.0.6
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_media_sanitise', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * [onMediaSanitise description]
	 *
	 * @return  [type]  [description]
	 */
	public function onMediaSanitise()
	{
        $app = JFactory::getApplication();
		return $app->redirect(
            JRoute::_('administrator/index.php?option=com_redshop&view=media&layout=sanitise')
        );
	}

	/**
	 * [onMediaSanitiseButton description]
	 *
	 * @return  [type]  [description]
	 */
	public function onMediaSanitiseButton()
	{
		return JToolBarHelper::custom('renameMedia', 'save.png', 'save_f2.png', 'Rename Medias', false);
	}
}
