<?php
/**
 * @package     Redshop
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_BASE') or die;

/**
 * Base class for rendering a display layout
 * loaded from from a layout file
 *
 * @package     Redshop
 * @subpackage  Layout
 * @see         http://docs.joomla.org/Sharing_layouts_across_views_or_extensions_with_JLayout
 * @since       1.4
 */
class RedshopLayoutFile extends RedshopLayoutBase
{
	/**
	 * @var    string  Dot separated path to the layout file, relative to base path
	 */
	protected $layoutId = '';

	/**
	 * @var    string  Base path to use when loading layout files
	 */
	protected $basePath = null;

	/**
	 * @var    string  Full path to actual layout files, after possible template override check
	 */
	protected $fullPath = null;

	/**
	 * Paths to search for layouts
	 *
	 * @var    array
	 */
	protected $includePaths = array();

	/**
	 * Method to instantiate the file-based layout.
	 *
	 * @param   string  $layoutId  Dot separated path to the layout file, relative to base path
	 * @param   string  $basePath  Base path to use when loading layout files
	 * @param   mixed   $options   Optional custom options to load. JRegistry or array format [@since 3.2]
	 */
	public function __construct($layoutId, $basePath = null, $options = null)
	{
		// Initialise / Load options
		$this->setOptions($options);

		// Main properties
		$this->setLayout($layoutId);
		$this->basePath = $basePath;

		// Init Environment
		$this->setComponent($this->options->get('component', 'auto'));
		$this->setClient($this->options->get('client', 'auto'));
	}

	/**
	 * Method to render the layout.
	 *
	 * @param   array  $displayData  List properties are used inside the layout file to build displayed output
	 *
	 * @return  string               The necessary HTML to display the layout
	 */
	public function render($displayData)
	{
		$layoutOutput = '';

		// Check possible overrides, and build the full path to layout file
		$path = $this->getPath();

		if ($this->options->get('debug', false))
		{
			echo "<pre>" . $this->renderDebugMessages() . "</pre>";
		}

		// If there exists such a layout file, include it and collect its output
		if (!empty($path))
		{
			ob_start();
			include $path;
			$layoutOutput = ob_get_contents();
			ob_end_clean();
		}

		return $layoutOutput;
	}

	/**
	 * Method to finds the full real file path, checking possible overrides
	 *
	 * @return  string  The full path to the layout file
	 */
	protected function getPath()
	{
		JLoader::import('joomla.filesystem.path');

		if (is_null($this->fullPath) && !empty($this->layoutId))
		{
			$this->addDebugMessage('<strong>Layout:</strong> ' . $this->layoutId);

			// Refresh paths
			$this->refreshIncludePaths();

			$this->addDebugMessage('<strong>Include Paths:</strong> ' . print_r($this->includePaths, true));

			$suffixes = $this->options->get('suffixes', array());

			// Search for suffixed versions. Example: tags.j31.php
			if (!empty($suffixes))
			{
				$this->addDebugMessage('<strong>Suffixes:</strong> ' . print_r($suffixes, true));

				foreach ($suffixes as $suffix)
				{
					$rawPath  = str_replace('.', '/', $this->layoutId) . '.' . $suffix . '.php';
					$this->addDebugMessage('<strong>Searching layout for:</strong> ' . $rawPath);

					if ($this->fullPath = JPath::find($this->includePaths, $rawPath))
					{
						$this->addDebugMessage('<strong>Found layout:</strong> ' . $this->fullPath);

						return $this->fullPath;
					}
				}
			}

			// Standard version
			$rawPath  = str_replace('.', '/', $this->layoutId) . '.php';
			$this->addDebugMessage('<strong>Searching layout for:</strong> ' . $rawPath);

			$this->fullPath = JPath::find($this->includePaths, $rawPath);

			if ($this->fullPath)
			{
				$this->addDebugMessage('<strong>Found layout:</strong> ' . $this->fullPath);
			}
		}

		return $this->fullPath;
	}

	/**
	 * Add one path to include in layout search. Proxy of addIncludePaths()
	 *
	 * @param   string  $path  The path to search for layouts
	 *
	 * @return  void
	 */
	public function addIncludePath($path)
	{
		$this->addIncludePaths($path);
	}

	/**
	 * Add one or more paths to include in layout search
	 *
	 * @param   string  $paths  The path or array of paths to search for layouts
	 *
	 * @return  void
	 */
	public function addIncludePaths($paths)
	{
		if (!empty($paths))
		{
			if (is_array($paths))
			{
				$this->includePaths = array_unique(array_merge($paths, $this->includePaths));
			}
			else
			{
				array_unshift($this->includePaths, $paths);
			}
		}
	}

	/**
	 * Remove one path from the layout search
	 *
	 * @param   string  $path  The path to remove from the layout search
	 *
	 * @return  void
	 */
	public function removeIncludePath($path)
	{
		$this->removeIncludePaths($path);
	}

	/**
	 * Remove one or more paths to exclude in layout search
	 *
	 * @param   string  $paths  The path or array of paths to remove for the layout search
	 *
	 * @return  void
	 */
	public function removeIncludePaths($paths)
	{
		if (!empty($paths))
		{
			$paths = (array) $paths;

			$this->includePaths = array_diff($this->includePaths, $paths);
		}
	}

	/**
	 * Validate that the active component is valid
	 *
	 * @param   string  $option  URL Option of the component. Example: com_content
	 *
	 * @return  boolean
	 */
	protected function validComponent($option = null)
	{
		// By default we will validate the active component
		$component = ($option !== null) ? $option : $this->options->get('component', null);

		if (!empty($component))
		{
			// Valid option format
			if (substr_count($component, 'com_'))
			{
				// Latest check: component exists and is enabled
				return JComponentHelper::isEnabled($component);
			}
		}

		return false;
	}

	/**
	 * Method to change the component where search for layouts
	 *
	 * @param   string  $option  URL Option of the component. Example: com_content
	 *
	 * @return  void
	 */
	public function setComponent($option)
	{
		$component = null;

		switch ((string) $option)
		{
			case 'none':
				$component = null;
				break;

			case 'auto':
				if (defined('JPATH_COMPONENT'))
				{
					$parts = explode('/', JPATH_COMPONENT);
					$component = end($parts);
				}

				break;

			default:
				$component = $option;
				break;
		}

		// Extra checks
		if (!$this->validComponent($component))
		{
			$component = null;
		}

		$this->options->set('component', $component);

		// Refresh include paths
		$this->refreshIncludePaths();
	}

	/**
	 * Function to initialise the application client
	 *
	 * @param   mixed  $client  Frontend: 'site' or 0 | Backend: 'admin' or 1
	 *
	 * @return  void
	 */
	public function setClient($client)
	{
		// Force string conversion to avoid unexpected states
		switch ((string) $client)
		{
			case 'site':
			case '0':
				$client = 0;
				break;

			case 'admin':
			case '1':
				$client = 1;
				break;

			default:
				$client = (int) JFactory::getApplication()->isAdmin();
				break;
		}

		$this->options->set('client', $client);

		// Refresh include paths
		$this->refreshIncludePaths();
	}

	/**
	 * Change the layout
	 *
	 * @param   string  $layoutId  Layout to render
	 *
	 * @return  void
	 */
	public function setLayout($layoutId)
	{
		$this->layoutId = $layoutId;
		$this->fullPath = null;
	}

	/**
	 * Refresh the list of include paths
	 *
	 * @return  void
	 */
	protected function refreshIncludePaths()
	{
		// Reset includePaths
		$this->includePaths = array();

		// (0 - lower priority) Frontend base layouts
		$this->addIncludePaths(JPATH_ROOT . '/layouts');

		// (1) Library path
		$this->addIncludePaths(JPATH_LIBRARIES . '/redshop/layouts');

		// (2) Standard Joomla! layouts overriden
		$this->addIncludePaths(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/layouts');

		// Component layouts & overrides if exist
		$component = $this->options->get('component', null);

		if (!empty($component))
		{
			// (3) Component path
			if ($this->options->get('client') == 0)
			{
				$this->addIncludePaths(JPATH_SITE . '/components/' . $component . '/layouts');
			}
			else
			{
				$this->addIncludePaths(JPATH_ADMINISTRATOR . '/components/' . $component . '/layouts');
			}

			// (4) Component template overrides path
			$this->addIncludePath(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/layouts/' . $component);
		}

		// Plugins layouts & overrides if exist
		$plugin = $this->options->get('plugin', null);

		if (!empty($plugin))
		{
			// (3) Plugins path
			$this->addIncludePaths(JPATH_PLUGINS . '/' . $plugin . '/layouts');

			// (4) Plugins template overrides path
			$this->addIncludePath(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/layouts/plugins/' . $plugin);
		}

		// Modules layouts & overrides if exist
		$module = $this->options->get('module', null);

		if (!empty($module))
		{
			// (3) Modules path
			$this->addIncludePaths(JPATH_SITE . '/modules/' . $module . '/layouts');

			// (4) Modules template overrides path
			$this->addIncludePath(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/layouts/modules/' . $module);
		}

		// (5 - highest priority) Received a custom high priority path ?
		if (!is_null($this->basePath))
		{
			$this->addIncludePath(rtrim($this->basePath, DIRECTORY_SEPARATOR));
		}
	}

	/**
	 * Change the debug mode
	 *
	 * @param   boolean  $debug  Enable / Disable debug
	 *
	 * @return  void
	 */
	public function setDebug($debug)
	{
		$this->options->set('debug', (boolean) $debug);
	}

	/**
	 * Render a layout with the same include paths & options
	 *
	 * @param   object  $layoutId     Object which properties are used inside the layout file to build displayed output
	 * @param   mixed   $displayData  Data to be rendered
	 *
	 * @return  string  The necessary HTML to display the layout
	 */
	public function sublayout($layoutId, $displayData)
	{
		// Sublayouts are searched in a subfolder with the name of the current layout
		if (!empty($this->layoutId))
		{
			$layoutId = $this->layoutId . '.' . $layoutId;
		}

		$sublayout = new static($layoutId, $this->basePath, $this->options);
		$sublayout->includePaths = $this->includePaths;

		return $sublayout->render($displayData);
	}
}
