<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * RedSHOP quick icon plugin
 *
 * @since  1.5
 */
class PlgQuickiconRedshop extends JPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var  boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method is called when the Quick Icons module is constructing its set
	 * of icons. You can return an array which defines a single icon and it will
	 * be rendered right after the stock Quick Icons.
	 *
	 * @param   string $context The calling context
	 *
	 * @return  array  A list of icon definition associative arrays, consisting of the
	 *                 keys link, image, text and access.
	 */
	public function onGetIcons($context)
	{
		if ($context != $this->params->get('context', 'mod_quickicon'))
		{
			return array();
		}

		if (version_compare(JVERSION, '3.0', '>='))
		{
			$image    = 'quickIconRedshop';
			$document = JFactory::getDocument();
			$document->addStyleDeclaration('
				.icon-' . $image . ' {
					background-image: url(' . JUri::base() . 'components/com_redshop/assets/images/redshopcart16.png);
					background-size: 14px;
					background-repeat: no-repeat;
				}'
			);
		}
		else
		{
			$image = JUri::base() . 'components/com_redshop/assets/images/redshopcart48.png';
		}

		return array(
			array(
				'link'  => 'index.php?option=com_redshop',
				'image' => $image,
				'text'  => JText::_('PLG_QUICKICON_REDSHOP_TITLE'),
				'id'    => 'plg_quickicon_redshop',
				'group' => 'MOD_QUICKICON_EXTENSIONS'
			)
		);
	}
}
