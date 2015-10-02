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
    use \redcomponent\robo\loadTasks;

    /**
     * Current RoboFile version
     */
    private $version = '1.5';

    /**
     * Hello World example task.
     *
     * @see  https://github.com/redCOMPONENT-COM/robo/blob/master/src/HelloWorld.php
     * @link https://packagist.org/packages/redcomponent/robo
     *
     * @return object Result
     */
    public function sayHelloWorld()
    {
        $result = $this->taskHelloWorld()->run();

        return $result;
    }

    /**
     * Sends Codeception errors to Slack
     *
     * @param   string  $slackChannel             The Slack Channel ID
     * @param   string  $slackToken               Your Slack authentication token.
     * @param   string  $codeceptionOutputFolder  Optional. By default tests/_output
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

        $result = $this
            ->taskSendCodeceptionOutputToSlack(
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
     * @return mixed
     */
    public function prepareSiteForSystemTests()
    {
        // Get Joomla Clean Testing sites
        if (is_dir('tests/joomla-cms3'))
        {
            $this->taskDeleteDir('tests/joomla-cms3')->run();
        }

		$version = 'staging';

		/*
		 * When joomla Staging branch has a bug you can uncomment the following line as a tmp fix for the tests layer.
		 * Use as $version value the latest tagged stable version at: https://github.com/joomla/joomla-cms/releases
		 */
		$version = '3.4.4';

		$this->_exec("git clone -b $version --single-branch --depth 1 https://github.com/joomla/joomla-cms.git tests/joomla-cms3");

		$this->say("Joomla CMS ($version) site created at tests/joomla-cms3");
	}

    /**
     * Executes Selenium System Tests in your machine
     *
     * @param   array  $options  Use -h to see available options
     *
     * @return mixed
     */
    public function runTest($options = [
        'test'          => null,
        'suite'         => 'acceptance',
        'selenium_path' => null
    ])
    {
        $this->getComposer();

        $this->taskComposerInstall()->run();

		if (isset($options['suite']) && 'api' === $options['suite'])
		{
			// Do not launch selenium when running API tests
		}
		else
		{
			$this->runSelenium();

            $this->taskWaitForSeleniumStandaloneServer()
                 ->run()
                 ->stopOnFail();
        }

        // Make sure to Run the Build Command to Generate AcceptanceTester
        $this->_exec("vendor/bin/codecept build");

        if (!$options['test'])
        {
            $this->say('Available tests in the system:');

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    'tests/' . $options['suite'],
                    RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST);

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
            $testNumber     = $this->ask('Type the number of the test  in the list that you want to run...');
            $options['test'] = $tests[$testNumber];
        }

        $pathToTestFile = 'tests/' . $options['suite'] . '/' . $options['test'];

        $this->taskCodecept()
             ->test($pathToTestFile)
             ->arg('--steps')
             ->arg('--debug')
             ->run()
             ->stopOnFail();

        if (!'api' == $options['suite'])
        {
            $this->killSelenium();
        }
    }

    /**
     * Function to Run tests in a Group
     *
     * @return void
     */
    public function runTests()
    {
        $this->prepareSiteForSystemTests();

        $this->getComposer();

        $this->taskComposerInstall()->run();

        $this->runSelenium();

        $this->taskWaitForSeleniumStandaloneServer()
             ->run()
             ->stopOnFail();

        // Make sure to Run the Build Command to Generate AcceptanceTester
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
             ->arg('--debug')
             ->arg('--tap')
             ->arg('--fail-fast')
             ->arg('tests/acceptance/uninstall/')
             ->run()
             ->stopOnFail();

        $this->say('preparing for update test');
        $this->getDevelop();
        $this->taskCodecept()
             ->arg('--steps')
             ->arg('--debug')
             ->arg('--fail-fast')
             ->arg('tests/acceptance/update/')
             ->run()
             ->stopOnFail();

        $this->killSelenium();
    }

    /**
     * This function ensures that you have the latest version of RoboFile in your project.
     * All redCOMPONENT RoboFiles are clones. All special needs for a project are stored in a robofile.yml file
     *
     * @return void
     */
    public function checkRoboFileVersion()
    {
        $this->taskCheckRoboFileVersion($this->version)
             ->run()
             ->stopOnFail();
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
     * @param   string  $path  Optional path to selenium standalone server
     *
     * @return void
     */
    public function runSelenium($path = null)
    {
        $this->_exec("vendor/bin/selenium-server-standalone >> selenium.log 2>&1 &");
    }

    public function sendScreenshotFromTravisToGithub($cloudName, $apiKey, $apiSecret, $GithubToken, $repoOwner, $repo, $pull)
    {
        // Loop throught Codeception snapshots
        if ($handler = opendir('tests/_output'))
        {
            while (false !== ($errorSnapshot = readdir($handler)))
            {
                // Avoid sending system files or html files
                if ('.' === substr($errorSnapshot, 0, 1)
                    || 'html' == substr($errorSnapshot, -4)
                    || 'log' == substr($errorSnapshot, -3))
                {
                    continue;
                }

                $this->say('Uploading screenshots...');

                Cloudinary::config(
                    array(
                        'cloud_name' => $cloudName,
                        'api_key'    => $apiKey,
                        'api_secret' => $apiSecret
                    )
                );

                $result = \Cloudinary\Uploader::upload(realpath(dirname(__FILE__) . '/tests/_output/' . $errorSnapshot));
                $this->say($errorSnapshot . 'Image sent');

                $this->say('Creating Github issue');
                $body = file_get_contents('tests/_output/report.tap.log', NULL, NULL, 15);
                $body .= '![Screenshot](' . $result['secure_url'] . ')';

                $client = new \Github\Client;
                $client->authenticate($GithubToken, \Github\Client::AUTH_HTTP_TOKEN);
                $client
                    ->api('issue')
                    ->comments()->create(
                        $repoOwner, $repo, $pull,
                        array(
                            'body'  => $body
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
}
