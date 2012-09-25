<?php
/**
 * @package     redSHOP
 * @subpackage  Core
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Default controller of Redshop for backward/forward compatibility with Joomla.
 *
 * @package     redSHOP
 * @subpackage  Core
 */
abstract class RedshopCoreController extends JControllerLegacy
{
    /**
     * The application object.
     *
     * @var  JApplication
     */
    protected $app;

    /**
     * The input object.
     *
     * @var  JInput
     */
    protected $input;

    /**
     * Constructor.
     * Backward and forward compatibility.
     *
     * @param   array         $config  An array of optional constructor options.
     * @param   JInput        $input   The input object.
     * @param   JApplication  $app     The application object.
     */
    public function __construct($config = array(), JInput $input = null, JApplication $app = null)
    {
        parent::__construct($config);
        $this->app   = isset($app) ? $app : $this->loadApplication();
        $this->input = isset($input) ? $input : $this->loadInput();
    }

    /**
     * Get the application object.
     *
     * @return  JApplicationBase The application object.
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * Get the input object.
     *
     * @return  JInput The input object.
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Serialize the controller.
     *
     * @return   string  The serialized controller.
     */
    public function serialize()
    {
        return serialize($this->input);
    }

    /**
     * Unserialize the controller.
     *
     * @param   string   $input  The serialized controller.
     *
     * @return  JController  Supports chaining.
     *
     * @throws  UnexpectedValueException if input is not the right class.
     */
    public function unserialize($input)
    {
        // Setup dependencies.
        $this->app = $this->loadApplication();

        // Unserialize the input.
        $this->input = unserialize($input);

        if (!($this->input instanceof JInput))
        {
            throw new UnexpectedValueException(sprintf('%s::unserialize would not accept a `%s`.', get_class($this), gettype($this->input)));
        }

        return $this;
    }

    /**
     * Load the application object.
     *
     * @return   JApplicationBase The application object.
     */
    protected function loadApplication()
    {
        return JFactory::getApplication();
    }

    /**
     * Load the input object.
     *
     * @return   JInput The input object.
     */
    protected function loadInput()
    {
        return $this->app->input;
    }
}

