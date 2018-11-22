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
     * @var   array
     * @var   array
     * @since 5.6.0
     */
    private $defaultArgs = [
        '--tap',
        '--fail-fast'
    ];

    /**
     * Downloads and prepares a Joomla CMS site for testing
     *
     * @param   int $use_htaccess (1/0) Rename and enable embedded Joomla .htaccess file
     *
     * @return mixed
     */
    public function prepareSiteForSystemTests($use_htaccess = 1)
    {
        // Get Joomla Clean Testing sites
        if (is_dir('tests/joomla-cms'))
        {
            $this->taskDeleteDir('tests/joomla-cms')->run();
        }

        $version = 'staging';

        /*
         * When joomla Staging branch has a bug you can uncomment the following line as a tmp fix for the tests layer.
         * Use as $version value the latest tagged stable version at: https://github.com/joomla/joomla-cms/releases
         */
        $version = '3.8.13';

        $this->_exec("git clone -b $version --single-branch --depth 1 https://github.com/joomla/joomla-cms.git tests/joomla-cms");

        $this->say("Joomla CMS ($version) site created at tests/joomla-cms");

        // Optionally uses Joomla default htaccess file
        if ($use_htaccess == 1)
        {
            $this->_copy('tests/joomla-cms/htaccess.txt', 'tests/joomla-cms/.htaccess');
            $this->_exec('sed -e "s,# RewriteBase /,RewriteBase /tests/joomla-cms/,g" --in-place tests/joomla-cms/.htaccess');
        }
    }

    /**
     * Clone joomla
     */
    public function runTestSetupJenkins()
    {
        $this->taskSeleniumStandaloneServer()
            ->setURL("http://localhost:4444")
            ->runSelenium()
            ->waitForSelenium()
            ->run()
            ->stopOnFail();

        $this->_exec("vendor/bin/codecept build");

        $this->taskCodecept()
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/install/')
            ->run()
            ->stopOnFail();
    }

    public function runJenkins($folder)
    {
        $this->taskSeleniumStandaloneServer()
            ->setURL("http://localhost:4444")
            ->runSelenium()
            ->waitForSelenium()
            ->run()
            ->stopOnFail();
        $this->_exec("vendor/bin/codecept build");

        $this->taskCodecept()
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg($folder . '/')
            ->run()
            ->stopOnFail();
    }
    /**
     * Tests setup
     *
     * @param   boolean  $debug   Add debug to the parameters
     * @param   boolean  $steps   Add steps to the parameters
     *
     * @return  void
     * @since   5.6.0
     */
    public function testsSetup($debug = true, $steps = true)
    {
        $args = [];
        if ($debug)
        {
            $args[] = '--debug';
        }
        if ($steps)
        {
            $args[] = '--steps';
        }
        $args = array_merge(
            $args,
            $this->defaultArgs
        );
        // Sets the output_append variable in case it's not yet
        if (getenv('output_append') === false)
        {
            $this->say('Setting output_append');
            putenv('output_append=');
        }
        // Builds codeception
        $this->_exec("vendor/bin/codecept build");
        // Executes the initial set up
        $this->taskCodecept()
            ->args($args)
            ->arg('tests/acceptance/install')
            ->run()
            ->stopOnFail();
    }

    /**
     * Method for run specific scenario
     *
     * @param   string $testCase  Scenario case.
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
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/install/')
            ->run()
            ->stopOnFail();

        // Run specific task
        $this->taskCodecept()
            ->test('tests/' . $testCase)
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
     * Looks for Travis Webserver
     *
     * @return  void
     */
    public function checkTravisWebserver()
    {
        $this->_exec('php tests/checkers/traviswebserverckecker.php http://localhost/tests/joomla-cms/installation/index.php');
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
     * @param $githubToken
     * @param $repoOwner
     * @param $repo
     * @param $pull
     */
    public function uploadPatchFromJenkinsToTestServer($githubToken, $repoOwner, $repo, $pull)
    {
        $body = 'Please Download the Patch Package for testing from the following Path: http://test.redcomponent.com/redshop/PR/' . $pull . '/redshop.zip';

        $this->say('Creating Github Comment');
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
        $directories = glob('tests/_output/*' , GLOB_ONLYDIR);

        foreach ($directories as $directory)
        {
            $this->sendBuildReportErrorSlackDirectory($directory, $cloudinaryName, $cloudinaryApiKey, $cloudinaryApiSecret, $githubRepository, $githubPRNo, $slackWebhook, $slackChannel, $buildURL);
        }
    }

    /**
     * Sends the build report error back to Slack
     *
     * @param   string  $directory            Directory to explore
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
    public function sendBuildReportErrorSlackDirectory($directory, $cloudinaryName, $cloudinaryApiKey, $cloudinaryApiSecret, $githubRepository, $githubPRNo, $slackWebhook, $slackChannel, $buildURL = '')
    {
        $errorSelenium = true;
        $reportError = false;
        $reportFile = $directory . '/selenium.log';
        $errorLog = 'Selenium log in ' . $directory . ':' . chr(10). chr(10);
        $this->say('Starting to Prepare Build Report');

        $this->say('Exploring folder ' . $directory . ' for error reports');
        // Loop through Codeception snapshots
        if (file_exists($directory) && $handler = opendir($directory))
        {
            $reportFile = $directory . '/report.tap.log';
            $errorLog = 'Codeception tap log in ' . $directory . ':' . chr(10). chr(10);
            $errorSelenium = false;
        }

        if (file_exists($reportFile))
        {
            $this->say('Report File Prepared');
            if ($reportFile)
            {
                $errorLog .= file_get_contents($reportFile, null, null, 15);
            }

            if (!$errorSelenium)
            {
                $handler = opendir($directory);
                $errorImage = '';

                while (!$reportError && false !== ($errorSnapshot = readdir($handler)))
                {
                    // Avoid sending system files or html files
                    if (!('png' === pathinfo($errorSnapshot, PATHINFO_EXTENSION)))
                    {
                        continue;
                    }

                    $reportError = true;
                    $errorImage = $directory . '/' . $errorSnapshot;
                }
            }

            if ($reportError || $errorSelenium)
            {
                // Sends the error report to Slack
                $this->say('Sending Error Report');
                $reportingTask = $this->taskReporting()
                    ->setCloudinaryCloudName($cloudinaryName)
                    ->setCloudinaryApiKey($cloudinaryApiKey)
                    ->setCloudinaryApiSecret($cloudinaryApiSecret)
                    ->setGithubRepo($githubRepository)
                    ->setGithubPR($githubPRNo)
                    ->setBuildURL($buildURL . 'display/redirect')
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

    /**
     * Sends the build report error back to Slack
     *
     * @param   string $cloudinaryName      Cloudinary cloud name
     * @param   string $cloudinaryApiKey    Cloudinary API key
     * @param   string $cloudinaryApiSecret Cloudinary API secret
     * @param   string $githubRepository    GitHub repository (owner/repo)
     * @param   string $githubPRNo          GitHub PR #
     * @param   string $slackWebhook        Slack Webhook URL
     * @param   string $slackChannel        Slack channel
     * @param   string $buildURL            Build URL
     *
     * @return  void
     *
     * @since   5.1
     */
    public function sendBuildReportErrorTravisToSlack($cloudinaryName, $cloudinaryApiKey, $cloudinaryApiSecret, $githubRepository, $githubPRNo, $slackWebhook, $slackChannel, $buildURL)
    {
        $errorSelenium = true;
        $reportError   = false;
        $reportFile    = 'tests/selenium.log';
        $errorLog      = 'Selenium log:' . chr(10) . chr(10);

        // Loop through Codeception snapshots
        if (file_exists('tests/_output') && $handler = opendir('tests/_output'))
        {
            $reportFile    = 'tests/_output/report.tap.log';
            $errorLog      = 'Codeception tap log:' . chr(10) . chr(10);
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
                $handler    = opendir('tests/_output');
                $errorImage = '';

                while (!$reportError && false !== ($errorSnapshot = readdir($handler)))
                {
                    // Avoid sending system files or html files
                    if (!('png' === pathinfo($errorSnapshot, PATHINFO_EXTENSION)))
                    {
                        continue;
                    }

                    $reportError = true;
                    $errorImage  = __DIR__ . '/tests/_output/' . $errorSnapshot;
                }
            }

            echo $errorImage;

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

    /**
     * Downloads and Install redFORM for Integration Testing testing
     *
     * @param   integer  $cleanUp  Clean up the directory when present (or skip the cloning process)
     *
     * @return  void
     * @since   1.0.0
     */
    protected function getredFORMExtensionForIntegrationTests($cleanUp = 1)
    {
        // Get redFORM Clean Testing sites
        if (is_dir('build/redFORM'))
        {
            if (!$cleanUp)
            {
                $this->say('Using cached version of redFORM and skipping clone process');
                return;
            }
            $this->taskDeleteDir('build/redFORM')->run();
        }
        $version = '3.3.15';
        $this->_exec("git clone -b $version --single-branch --depth 1 https://travisredweb:travisredweb2013github@github.com/redCOMPONENT-COM/redFORM.git build/redFORM");
        $this->say("redFORM ($version) cloned at build/");
    }

    /**
     * Nightly build
     *
     * @return  void
     * @since   2.1.0
     */
    public function buildNightly()
    {
        // Read version
        $version = $this->getVersion();

        // Increase nightly build
        $version = explode('.', $version);

        if (!isset($version[3]))
        {
            $version[3] = 0;
        }
        else
        {
            $version[3] = (int) $version[3] + 1;
        }

        $version = implode('.', $version);

        $this->updateVersion($version);
        $this->release();
    }

    /**
     *
     * @return string
     *
     * @since  2.1.0
     */
    private function getVersion()
    {
        $versionFile = __DIR__ . '/redshop.xml';
        $xml         = simplexml_load_file($versionFile);

        return (string) $xml->version;
    }

    /**
     * @param   string $version Version
     *
     * @return  void
     *
     * @since   2.1.0
     */
    private function updateVersion($version)
    {
        $redShopFile = __DIR__ . '/redshop.xml';

        $xml                     = simplexml_load_file($redShopFile);
        $result                  = $xml->xpath("/extension");
        $result[0]->creationDate = date('Y-m-d H:i:s');
        $result[0]->version      = $version;

        $xml->asXML($redShopFile);
    }

    /**
     * @return  void
     *
     * @since   2.1.0
     */
    private function release()
    {
        $this->_exec('git add redshop.xml');
        $this->_exec('git commit -m "Nightly build"');
        $this->_exec('git push');
    }
    public function testsSitePreparation($use_htaccess = 1, $cleanUp = 1)
    {
        $skipCleanup = false;
        // Get Joomla Clean Testing sites
        if (is_dir('tests/joomla-cms'))
        {
            if (!$cleanUp)
            {
                $skipCleanup = true;
                $this->say('Using cached version of Joomla CMS and skipping clone process');
            }
            else
            {
                $this->taskDeleteDir('tests/joomla-cms')->run();
            }
        }
        if (!$skipCleanup)
        {
            $version = 'staging';
            /*
            * When joomla Staging branch has a bug you can uncomment the following line as a tmp fix for the tests layer.
            * Use as $version value the latest tagged stable version at: https://github.com/joomla/joomla-cms/releases
            */
            $version = '3.9.0';
            $this->_exec("git clone -b $version --single-branch --depth 1 https://github.com/joomla/joomla-cms.git tests/joomla-cms");
            $this->say("Joomla CMS ($version) site created at tests/joomla-cms");
        }
        // Optionally uses Joomla default htaccess file
        if ($use_htaccess == 1)
        {
            $this->_copy('tests/joomla-cms/htaccess.txt', 'tests/joomla-cms/.htaccess');
            $this->_exec('sed -e "s,# RewriteBase /,RewriteBase /tests/joomla-cms/,g" --in-place tests/joomla-cms/.htaccess');
        }
    }


    /**
     * Downloads and Install redSHOP for Integration Testing testing
     *
     * @param   integer  $cleanUp  Clean up the directory when present (or skip the cloning process)
     *
     * @return  void
     * @since   1.0.0
     */
    protected function getredSHOPExtensionForIntegrationTests($cleanUp = 1)
    {
        // Get redFORM Clean Testing sites
        if (is_dir('tests/extension/redSHOP'))
        {
            if (!$cleanUp)
            {
                $this->say('Using cached version of redSHOP and skipping clone process');

                return;
            }
            $this->taskDeleteDir('tests/extension/redSHOP')->run();
        }

        $version = '2.0.6';
        $this->_exec("git clone -b $version --single-branch --depth 1 https://redJOHNNY:redjohnnyredweb2013github@github.com/redCOMPONENT-COM/redSHOP.git tests/extension/redSHOP");

        $this->say("redSHOP ($version) cloned at tests/extension/");
    }
    /**
     * Individual test folder execution
     *
     * @param   string   $folder  Folder to execute codecept run to
     * @param   boolean  $debug   Add debug to the parameters
     * @param   boolean  $steps   Add steps to the parameters
     *
     * @return  void
     * @since   5.6.0
     */
    public function testsRun($folder, $debug = true, $steps = true)
    {
        $args = [];

        if ($debug)
        {
            $args[] = '--debug';
        }

        if ($steps)
        {
            $args[] = '--steps';
        }

        $args = array_merge(
            $args,
            $this->defaultArgs
        );

        $this->getredSHOPExtensionForIntegrationTests(0);

        // Sets the output_append variable in case it's not yet
        if (getenv('output_append') === false)
        {
            putenv('output_append=');
        }

        // Codeception build
        $this->_exec("vendor/bin/codecept build");

        // Actual execution of Codeception test
        $this->taskCodecept()
            ->args($args)
            ->arg('tests/' . $folder . '/')
            ->run()
            ->stopOnFail();
    }

}