<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Configuration\ConfigurationPluginREDSHOPSteps as PluginSteps;
use ConfigurationPluginREDSHOPPage as PluginManagerPage;

/**
 * Class ConfigurationPluginREDSHOPCest
 * @since 3.0.3
 */
class ConfigurationPluginREDSHOPCest
{
	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $namePlugin;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $filterStatus;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $status;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $filterComponent;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $component;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $filterType;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $type;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $filterElement;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $element;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $filterAccess;

	/**
	 * @var string
	 * @since 3.0.3
	 */
	protected $access;

	/**
	 * ConfigurationPluginREDSHOPCest constructor.
	 * @since 3.0.3
	 */
	public function __construct()
	{
		$this->namePlugin = 'Smart Search - Redshop';
		$this->filterStatus = 'status';
		$this->status = 'Disable';
		$this->filterComponent = 'component';
		$this->component = 'Plugin redshop';
		$this->filterType = 'type';
		$this->type = 'redshop_payment';
		$this->filterElement = 'element';
		$this->element = 'attribute';
		$this->filterAccess = 'access';
		$this->access = 'public';
	}

	/**
	 * @param PluginSteps $I
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function checkAllCase(PluginSteps $I)
	{
		$I->doAdministratorLogin();
		$I->changeStatus($this->namePlugin, PluginManagerPage::$buttonCheckIn);
		$I->changeStatus($this->namePlugin, PluginManagerPage::$buttonUnpublish);
		$I->filterPlugin($this->filterStatus, $this->status);
		$I->changeStatus($this->namePlugin, PluginManagerPage::$buttonPublish);
		$I->filterPlugin($this->filterComponent, $this->component);
		$I->filterPlugin($this->filterType, $this->type);
		$I->filterPlugin($this->filterElement, $this->element);
		$I->filterPlugin($this->filterAccess, $this->access);
	}
}