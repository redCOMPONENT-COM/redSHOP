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
     * Sends Codeception errors to Slack
     *
     * @param   string $slackChannel            The Slack Channel ID
     * @param   string $slackToken              Your Slack authentication token.
     * @param   string $codeceptionOutputFolder Optional. By default tests/_output
     *
     * @return mixed
     */
    public function sendCodeceptionOutputToSlack($slackChannel, $slackToken = null, $codeceptionOutputFolder = null)
    {
        if (is_null($slackToken))
        {
            $this->say('we are in Travis environment, getting token from ENV');

            // Remind to set the token in repo Travis settings,
            // see: http://docs.travis-ci.com/user/environment-variables/#Using-Settings
            $slackToken = getenv('SLACK_ENCRYPTED_TOKEN');
        }

        $result = $this->taskSendCodeceptionOutputToSlack(
            $slackChannel,
            $slackToken,
            $codeceptionOutputFolder
        )
            ->run();

        return $result;
    }

    /**
     * Downloads and prepares a Joomla CMS site for testing
     *
     * @param   int  $useHtaccess  (1/0) Rename and enable embedded Joomla .htaccess file
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

    public function runTestsJenkins()
    {
        $this->getComposer();

        $this->taskComposerInstall()->run();

        // $this->runSelenium();

        $this->taskSeleniumStandaloneServer()
            ->setURL("http://localhost:4444")
            ->runSelenium()
            ->waitForSelenium()
            ->run()
            ->stopOnFail();

        // Make sure to Run the B uild Command to Generate AcceptanceTester
        $this->_exec("vendor/bin/codecept build");

        $this->taskCodecept()
            ->arg('--steps')
            ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/install/')
            ->run()
            ->stopOnFail();

        $this->taskCodecept()
            ->arg('--steps')
            ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/administrator/')
            ->run()
            ->stopOnFail();

        $this->taskCodecept()
            ->arg('--steps')
            //  ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/integration/ManageProductsCheckoutFrontEndCest.php')
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
            //  ->arg('--steps')
            //  ->arg('--debug')
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
     * Executes Selenium System Tests in your machine
     *
     * @param   array  $opts  Use -h to see available options
     *
     * @return mixed
     */
    public function runTest($opts = array('test|t'  => null, 'suite|s' => 'acceptance'))
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
                    || strripos($iterator->getSubPathName(), 'cest.php'))
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
     * Function to Run tests in a Group
     *
     * @param   int  $useHtaccess  Use htacess.
     *
     * @return  void
     */
    public function runTests($useHtaccess = 0)
    {
        $this->prepareSiteForSystemTests($useHtaccess);

        $this->checkTravisWebserver();

        $this->getComposer();

        $this->taskComposerInstall()->run();

        // $this->runSelenium();

        $this->taskSeleniumStandaloneServer()
            ->setURL("http://localhost:4444")
            ->runSelenium()
            ->waitForSelenium()
            ->run()
            ->stopOnFail();

        // Make sure to Run the B uild Command to Generate AcceptanceTester
        $this->_exec("vendor/bin/codecept build");

        $this->taskCodecept()
            ->arg('--steps')
            // ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/install/')
            ->run()
            ->stopOnFail();

        $this->taskCodecept()
            ->arg('--steps')
            ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/administrator/')
            ->run()
            ->stopOnFail();

        $this->taskCodecept()
            ->arg('--steps')
            //  ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/integration/ManageProductsCheckoutFrontEndCest.php')
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
            //  ->arg('--steps')
            //  ->arg('--debug')
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
     * Method for run specific scenario
     *
     * @param   string  $testCase  Scenario case.
     *                            (example: "acceptance/install" for folder, "acceptance/integration/productCheckoutVatExemptUser" for file)
     *
     * @return  void
     */
    public function runTravis($testCase)
    {
        $this->prepareSiteForSystemTests(1);

        $this->checkTravisWebserver();

        $testPath = __DIR__ . '/tests/' . $testCase;

        // Populate test case. In case this path is not an exist folder.
        if (!file_exists($testPath) || !is_dir($testPath))
        {
            $testCase .= 'Cest.php';
        }

        $this->taskSeleniumStandaloneServer()
            ->setURL('http://localhost:4444')
            ->runSelenium()
            ->waitForSelenium()
            ->run()
            ->stopOnFail();

        // Make sure to Run the B uild Command to Generate AcceptanceTester
        $this->_exec('vendor/bin/codecept build');

        // Install Joomla + redSHOP
        $this->taskCodecept()
            // ->arg('--steps')
            // ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/install/')
            ->run()
            ->stopOnFail();

        // Run specific task
        $this->taskCodecept()
            ->test('tests/' . $testCase)
            // ->arg('--steps')
            // ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->run()
            ->stopOnFail();

        // Uninstall after test.
        $this->taskCodecept()
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/uninstall/')
            ->run()
            ->stopOnFail();

        $this->killSelenium();
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
     * Runs Selenium Standalone Server
     *
     * @param   string $path Optional path to selenium standalone server
     *
     * @return void
     */
    public function runSelenium($path = null)
    {
        $this->_exec("vendor/bin/selenium-server-standalone >> selenium.log 2>&1 &");
    }

    public function sendScreenshotFromTravisToGithub($cloudName, $apiKey, $apiSecret, $githubToken, $repoOwner, $repo, $pull)
    {
        $errorSelenium = true;
        $reportError   = false;
        $reportFile    = 'tests/selenium.log';
        $body          = 'Selenium log:' . chr(10) . chr(10);

        // Loop throught Codeception snapshots
        if (file_exists('tests/_output') && $handler = opendir('tests/_output'))
        {
            $reportFile    = 'tests/_output/report.tap.log';
            $body          = 'Codeception tap log:' . chr(10) . chr(10);
            $errorSelenium = false;
        }

        if (file_exists($reportFile))
        {
            if ($reportFile)
            {
                $body .= file_get_contents($reportFile, null, null, 15);
            }

            if (!$errorSelenium)
            {
                $handler = opendir('tests/_output');

                while (false !== ($errorSnapshot = readdir($handler)))
                {
                    // Avoid sending system files or html files
                    if (!('png' === pathinfo($errorSnapshot, PATHINFO_EXTENSION)))
                    {
                        continue;
                    }

                    $reportError = true;
                    $this->say("Uploading screenshots: $errorSnapshot");

                    Cloudinary::config(
                        array(
                            'cloud_name' => $cloudName,
                            'api_key'    => $apiKey,
                            'api_secret' => $apiSecret
                        )
                    );

                    $result = \Cloudinary\Uploader::upload(realpath(dirname(__FILE__) . '/tests/_output/' . $errorSnapshot));
                    $this->say($errorSnapshot . 'Image sent');
                    $body .= '![Screenshot](' . $result['secure_url'] . ')';
                }
            }

            // If it's a Selenium error log, it prints it in the regular output
            if ($errorSelenium)
            {
                $this->say($body);
            }

            // If it needs to, it creates the error log in a Github comment
            if ($reportError)
            {
                $this->say('Creating Github issue');
                $client = new \Github\Client;
                $client->authenticate($githubToken, \Github\Client::AUTH_HTTP_TOKEN);
                $client
                    ->api('issue')
                    ->comments()->create(
                        $repoOwner, $repo, $pull,
                        array(
                            'body' => $body
                        )
                    );
            }
        }
    }

    private function getDevelop()
    {
        // Get current develop branch of the extension for Extension update test
        if (is_dir('tests/develop'))
        {
            $this->taskDeleteDir('tests/develop')->run();
        }

        $this->_exec('git clone -b develop --single-branch --depth 1 git@github.com:redCOMPONENT-COM/redSHOP.git tests/develop');
        $this->say('Downloaded Develop Branch for Update test');
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
     * Build correct git clone command according to local configuration and OS
     *
     * @return string
     */
    private function buildGitCloneCommand()
    {
        $branch = empty($this->configuration->branch) ? '3.8-dev' : $this->configuration->branch;

        return "git" . $this->executableExtension . " clone -b $branch --single-branch --depth 1 https://github.com/joomla/joomla-cms.git tests/cache";
    }

    /**
     * Looks for Travis Webserver
     *
     * @return  void
     */
    public function checkTravisWebserver()
    {
        $this->_exec('php tests/checkers/traviswebserverckecker.php http://localhost/tests/joomla-cms3/installation/index.php');
    }
}
