<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * Download robo.phar from http://robo.li/robo.phar and type in the root of the repo: $ php robo.phar
 * Or do: $ composer update, and afterwards you will be able to execute robo like $ php vendor/bin/robo
 *
 * @see  http://robo.li/
 */

require_once 'vendor/autoload.php';

/**
 * Class RoboFile
 *
 * @since  1.6
 */
class RoboFile extends \Robo\Tasks
{
	// Load tasks from composer, see composer.json
	use Joomla\Testing\Robo\Tasks\LoadTasks;

	/**
	 * File extension for executables
	 *
	 * @var string
	 */
	private $executableExtension = '';

	/**
	 * Local configuration parameters
	 *
	 * @var array
	 */
	private $configuration = array();

	/**
	 * Path to the local CMS root
	 *
	 * @var string
	 */
	private $cmsPath = '';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->configuration       = $this->getConfiguration();
		$this->cmsPath             = $this->getCmsPath();
		$this->executableExtension = $this->getExecutableExtension();

		// Set default timezone (so no warnings are generated if it is not set)
		date_default_timezone_set('UTC');
	}

	/**
	 * Get (optional) configuration from an external file
	 *
	 * @return \stdClass|null
	 */
	public function getConfiguration()
	{
		$configurationFile = __DIR__ . '/tests/RoboFile.ini';

		if (!file_exists($configurationFile))
		{
			$this->say("No local configuration file");

			return null;
		}

		$configuration = parse_ini_file($configurationFile);

		if ($configuration === false)
		{
			$this->say('Local configuration file is empty or wrong (check is it in correct .ini format');

			return null;
		}

		return json_decode(json_encode($configuration));
	}

	/**
	 * Get the correct CMS root path
	 *
	 * @return string
	 */
	private function getCmsPath()
	{
		if (empty($this->configuration->cmsPath))
		{
			return 'tests/joomla-cms3';
		}

		if (!file_exists(dirname($this->configuration->cmsPath)))
		{
			$this->say('Cms path written in local configuration does not exists or is not readable');

			return 'tests/joomla-cms3';
		}

		return $this->configuration->cmsPath;
	}

	/**
	 * Get the executable extension according to Operating System
	 *
	 * @return  string
	 */
	private function getExecutableExtension()
	{
		if ($this->isWindows())
		{
			// Check whether git.exe or git as command should be used, as on windows both are possible
			if (!$this->_exec('git.exe --version')->getMessage())
			{
				return '';
			}
			else
			{
				return '.exe';
			}
		}

		return '';
	}

	/**
	 * Check if local OS is Windows
	 *
	 * @return  boolean
	 */
	private function isWindows()
	{
		return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
	}

	public function runTestsDrone()
	{
		$this->getComposer();

		$this->taskSeleniumStandaloneServer()
			->setURL("http://localhost:4444")
			->runSelenium()
			->waitForSelenium()
			->run()
			->stopOnFail();

		// Make sure to Run the B uild Command to Generate AcceptanceTester
		$this->_exec("vendor/bin/codecept build");

		$this->taskCodecept()
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/install/')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/administrator/')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/CheckoutSpecificShopperGroupsCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/ProductsCheckoutFrontEndCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/GiftCardCheckoutProductCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--tap')
			->arg('--steps')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/CouponVoucherMixCheckoutCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/MassDiscountCheckoutCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/CheckoutDiscountOnProductCest.php')
			->run()
			->stopOnFail();


		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/CheckoutDiscountTotalCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/CheckoutWithStockroomCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/QuotationFrontendCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/CompareProductsCest.php')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--fail-fast')
			->arg('tests/acceptance/integration/OnePageCheckoutCest.php')
			->run()
			->stopOnFail();

		/*
		$this->taskCodecept()
			->arg('--steps')
			->arg('--debug')
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/checkout/')
			->run();
			// ->stopOnFail();
		*/

		$this->taskCodecept()
			->arg('--tap')
			->arg('--fail-fast')
			->arg('tests/acceptance/uninstall/')
			->run()
			->stopOnFail();

		/* @todo: REDSHOP-2884
		 * $this->say('preparing for update test');
		 * $this->getDevelop();
		 * $this->taskCodecept()
		 * ->arg('--steps')
		 * ->arg('--debug')
		 * ->arg('--fail-fast')
		 * ->arg('tests/acceptance/update/')
		 * ->run()
		 * ->stopOnFail();
		 */

		$this->killSelenium();
	}

	/**
	 * Downloads Composer
	 *
	 * @return void
	 */
	private function getComposer()
	{
		// Make sure we have Composer
		if (!file_exists('./composer.phar'))
		{
			$this->_exec('curl --retry 3 --retry-delay 5 -sS https://getcomposer.org/installer | php');
		}
	}

	/**
	 * Stops Selenium Standalone Server
	 *
	 * @return void
	 */
	public function killSelenium()
	{
		$this->_exec('curl http://localhost:4444/selenium-server/driver/?cmd=shutDownSeleniumServer');
	}

	/**
	 * Executes Selenium System Tests in your machine
	 *
	 * @param   array $opts Use -h to see available options
	 *
	 * @return mixed
	 */
	public function runTest($opts = array('test|t' => null, 'suite|s' => 'acceptance'))
	{
		$this->getComposer();

		$this->taskComposerInstall()->run();

		if (isset($opts['suite']) && 'api' === $opts['suite'])
		{
			// Do not launch selenium when running API tests
		}
		else
		{
			$this->runSelenium();

			if (!$this->isWindows())
			{
				$this->taskWaitForSeleniumStandaloneServer()
					->run()
					->stopOnFail();
			};
		}

		// Make sure to Run the Build Command to Generate AcceptanceTester
		$this->_exec("vendor/bin/codecept build");

		if (!$opts['test'])
		{
			$this->say('Available tests in the system:');

			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					'tests/' . $opts['suite'],
					RecursiveDirectoryIterator::SKIP_DOTS
				),
				RecursiveIteratorIterator::SELF_FIRST
			);

			$tests = array();

			$iterator->rewind();
			$i = 1;

			while ($iterator->valid())
			{
				if (strripos($iterator->getSubPathName(), 'cept.php')
					|| strripos($iterator->getSubPathName(), 'cest.php')
				)
				{
					$this->say('[' . $i . '] ' . $iterator->getSubPathName());
					$tests[$i] = $iterator->getSubPathName();
					$i++;
				}

				$iterator->next();
			}

			$this->say('');
			$testNumber   = $this->ask('Type the number of the test  in the list that you want to run...');
			$opts['test'] = $tests[$testNumber];
		}

		$pathToTestFile = 'tests/' . $opts['suite'] . '/' . $opts['test'];

		// Loading the class to display the methods in the class
		require 'tests/' . $opts['suite'] . '/' . $opts['test'];

		$classes   = Nette\Reflection\AnnotationsParser::parsePhp(file_get_contents($pathToTestFile));
		$className = array_keys($classes);
		$className = $className[0];

		// If test is Cest, give the option to execute individual methods
		if (strripos($className, 'cest'))
		{
			$testFile    = new Nette\Reflection\ClassType($className);
			$testMethods = $testFile->getMethods(ReflectionMethod::IS_PUBLIC);

			foreach ($testMethods as $key => $method)
			{
				$this->say('[' . $key . '] ' . $method->name);
			}

			$this->say('');
			$methodNumber = $this->askDefault('Choose the method in the test to run (hit ENTER for All)', 'All');

			if ($methodNumber != 'All')
			{
				$method         = $testMethods[$methodNumber]->name;
				$pathToTestFile = $pathToTestFile . ':' . $method;
			}
		}

		$this->taskCodecept()
			->test($pathToTestFile)
			->arg('--steps')
			->arg('--debug')
			->arg('--fail-fast')
			->run()
			->stopOnFail();

		if (!'api' == $opts['suite'])
		{
			$this->killSelenium();
		}
	}

	/**
	 * Downloads and prepares a Joomla CMS site for testing
	 *
	 * @param   int $useHtaccess (1/0) Rename and enable embedded Joomla .htaccess file
	 *
	 * @return mixed
	 */
	public function prepareSiteForSystemTests($useHtaccess = 0)
	{
		// Caching cloned installations locally
		if (!is_dir('tests/cache') || (time() - filemtime('tests/cache') > 60 * 60 * 24))
		{
			if (file_exists('tests/cache'))
			{
				$this->taskDeleteDir('tests/cache')->run();
			}

			$this->_exec($this->buildGitCloneCommand());
		}

		// Get Joomla Clean Testing sites
		if (is_dir($this->cmsPath))
		{
			try
			{
				$this->taskDeleteDir($this->cmsPath)->run();
			}
			catch (Exception $e)
			{
				// Sorry, we tried :(
				$this->say('Sorry, you will have to delete ' . $this->cmsPath . ' manually. ');
				exit(1);
			}
		}

		$this->_copyDir('tests/cache', $this->cmsPath);

		// Optionally change owner to fix permissions issues
		if (!empty($this->configuration->localUser) && !$this->isWindows())
		{
			$this->_exec('chown -R ' . $this->configuration->localUser . ' ' . $this->cmsPath);
		}

		// Optionally uses Joomla default htaccess file
		if ($useHtaccess == 1)
		{
			$this->_copy($this->cmsPath . '/htaccess.txt', $this->cmsPath . '/.htaccess');
			$this->_exec('sed -e "s,# RewriteBase /,RewriteBase /' . $this->cmsPath . '/,g" --in-place ' . $this->cmsPath . '/.htaccess');
		}
	}

	/**
	 * Sends the build report error back to Slack
	 *
	 * @param   string  $cloudinaryName       Cloudinary cloud name
	 * @param   string  $cloudinaryApiKey     Cloudinary API key
	 * @param   string  $cloudinaryApiSecret  Cloudinary API secret
	 * @param   string  $githubRepository     GitHub repository (owner/repo)
	 * @param   string  $githubPRNo           GitHub PR #
	 * @param   string  $slackWebhook         Slack Webhook URL
	 * @param   string  $slackChannel         Slack channel
	 * @param   string  $buildURL             Build URL
	 *
	 * @return  void
	 *
	 * @since   5.1
	 */
	 public function sendBuildReportErrorSlack($cloudinaryName, $cloudinaryApiKey, $cloudinaryApiSecret, $githubRepository, $githubPRNo, $slackWebhook, $slackChannel, $buildURL = '')
	{
		$errorSelenium = true;
		$reportError = false;
		$reportFile = 'tests/selenium.log';
		$errorLog = 'Selenium log:' . chr(10). chr(10);

		// Loop through Codeception snapshots
		if (file_exists('tests/_output') && $handler = opendir('tests/_output'))
		{
			$reportFile = 'tests/_output/report.tap.log';
		    $errorLog = 'Codeception tap log:' . chr(10). chr(10);
			$errorSelenium = false;
		}

		if (file_exists($reportFile))
		{
			if ($reportFile)
			{
			    $errorLog .= file_get_contents($reportFile, null, null, 15);
			}

			if (!$errorSelenium)
			{
				$handler = opendir('tests/_output');
				$errorImage = '';

				while (!$reportError && false !== ($errorSnapshot = readdir($handler)))
				{
					// Avoid sending system files or html files
					if (!('png' === pathinfo($errorSnapshot, PATHINFO_EXTENSION)))
					{
						continue;
					}

				    $reportError = true;
					$errorImage = __DIR__ . '/tests/_output/' . $errorSnapshot;
				}
			}

			if ($reportError || $errorSelenium)
		    {
			    // Sends the error report to Slack
			    $reportingTask = $this->taskReporting()
				    ->setCloudinaryCloudName($cloudinaryName)
				    ->setCloudinaryApiKey($cloudinaryApiKey)
				    ->setCloudinaryApiSecret($cloudinaryApiSecret)
				    ->setGithubRepo($githubRepository)
				    ->setGithubPR($githubPRNo)
				    ->setBuildURL($buildURL)
				    ->setSlackWebhook($slackWebhook)
				    ->setSlackChannel($slackChannel)
				    ->setTapLog($errorLog);

			    if (!empty($errorImage))
			    {
				    $reportingTask->setImagesToUpload($errorImage)
					    ->publishCloudinaryImages();
			    }

			    $reportingTask->publishBuildReportToSlack()
				    ->run()
				    ->stopOnFail();
		    }
		}
	}

	private function buildGitCloneCommand()
	{
		$branch = empty($this->configuration->branch) ? 'staging' : $this->configuration->branch;

		return "git" . $this->executableExtension . " clone -b $branch --single-branch --depth 1 https://github.com/joomla/joomla-cms.git tests/cache";
	}
}
