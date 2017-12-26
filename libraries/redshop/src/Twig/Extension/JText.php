<?php
/**
 * @package     RedSHOP
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Twig\Extension;

defined('_JEXEC') or die;

/**
 * JText integration for Twig.
 *
 * @package     Redshop
 * @subpackage  Base
 * @since       __DEPLOY_VERSION__
 **/
class JText extends \Twig_Extension
{
	/**
	 * Inject functions.
	 *
	 * @return  array
	 */
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('jtext', array($this, 'translate')),
			new \Twig_SimpleFunction('jtext_sprintf', array($this, 'translateSprintf')),
		);
	}

	/**
	 * Translate a string through JText::_().
	 *
	 * @return  void
	 */
	public function translate()
	{
		echo forward_static_call_array(array('\JText', '_'), func_get_args());
	}

	/**
	 * Translate a string through JText::sprintf().
	 *
	 * @return  void
	 */
	public function translateSprintf()
	{
		echo forward_static_call_array(array('\JText', 'sprintf'), func_get_args());
	}

	/**
	 * Get the name of this extension.
	 *
	 * @return  string
	 */
	public function getName()
	{
		return 'jtext';
	}
}
