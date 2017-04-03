<?php
/**
 * @package     Redshop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\View;

defined('_JEXEC') or die;

use Doctrine\Common\Inflector\Inflector;

jimport('joomla.application.component.viewlegacy');

/**
 * Abstract view
 *
 * @package     Redshob.Libraries
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
abstract class AbstractView extends \JViewLegacy
{
	/**
	 * Layout used to render the component
	 *
	 * @var  string
	 */
	protected $componentLayout = null;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = true;

	/**
	 * Do we have to disable a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $disableSidebar = false;

	/**
	 * @var  string
	 */
	protected $instancesName;

	/**
	 * @var  string
	 */
	protected $instanceName;

	/**
	 * @var    \JModelLegacy
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $model;

	/**
	 * @var    string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $layout;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @throws  \Exception
	 */
	public function display($tpl = null)
	{
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode('<br />', $errors));
		}

		$this->layout = $tpl;
		$this->model  = $this->getModel();

		// Before display
		$this->beforeDisplay($tpl);

		// Add page title
		$this->addTitle();

		// Add toolbar
		$this->addToolbar();

		$render = \RedshopLayoutHelper::render(
			$this->componentLayout,
			array(
				'view'            => $this,
				'tpl'             => $tpl,
				'sidebar_display' => $this->displaySidebar,
				'disableSidebar'  => $this->disableSidebar
			)
		);

		\JPluginHelper::importPlugin('system');
		\RedshopHelperUtility::getDispatcher()->trigger('onRedshopAdminRender', array(&$render));

		if ($render instanceof \Exception)
		{
			return $render;
		}

		echo $render;

		return true;
	}

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string  &$tpl  Template name
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function beforeDisplay(&$tpl)
	{
		return;
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addTitle()
	{
		\JToolbarHelper::title($this->getTitle());
	}

	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTitle()
	{
		return \JText::_('COM_REDSHOP_' . strtoupper($this->getInstanceName()));
	}

	/**
	 * Method for add toolbar.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function addToolbar()
	{
		return;
	}

	/**
	 * Method for get instance name with multi (Ex: products, categories,...) of current view
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getInstancesName()
	{
		if (is_null($this->instancesName))
		{
			$this->instancesName = strtolower(str_replace('RedshopView', '', get_class($this)));
		}

		return $this->instancesName;
	}

	/**
	 * Method for get instance name with single (Ex: product, category,...) of current view
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getInstanceName()
	{
		if (is_null($this->instanceName))
		{
			$this->instanceName = Inflector::singularize($this->getInstancesName());
		}

		return $this->instanceName;
	}
}
