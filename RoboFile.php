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
	 * @param   int  $use_htaccess  (1/0) Rename and enable embedded Joomla .htaccess file
	 *
     * @return mixed
     */
    public function prepareSiteForSystemTests($use_htaccess = 0)
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
		$version = '3.6.0-beta1';

		$this->_exec("git clone -b $version --single-branch --depth 1 https://github.com/joomla/joomla-cms.git tests/joomla-cms3");

		$this->say("Joomla CMS ($version) site created at tests/joomla-cms3");

		// Optionally uses Joomla default htaccess file
		if ($use_htaccess == 1)
		{
			$this->_copy('tests/joomla-cms3/htaccess.txt', 'tests/joomla-cms3/.htaccess');
			$this->_exec('sed -e "s,# RewriteBase /,RewriteBase /tests/joomla-cms3/,g" --in-place tests/joomla-cms3/.htaccess');
		}
	}

    /**
     * Executes Selenium System Tests in your machine
     *
     * @param   array  $options  Use -h to see available options
     *
     * @return mixed
     */
	public function runTest($opts = [
		'test|t'	    => null,
		'suite|s'	    => 'acceptance'
    ])
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

            $this->taskWaitForSeleniumStandaloneServer()
                 ->run()
                 ->stopOnFail();
        }

        // Make sure to Run the Build Command to Generate AcceptanceTester
        $this->_exec("vendor/bin/codecept build");

		if (!$opts['test'])
        {
            $this->say('Available tests in the system:');

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    'tests/' . $opts['suite'],
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
			$opts['test'] = $tests[$testNumber];
        }

        $pathToTestFile = 'tests/' . $opts['suite'] . '/' . $opts['test'];

		//loading the class to display the methods in the class
		require 'tests/' . $opts['suite'] . '/' . $opts['test'];

		$classes = Nette\Reflection\AnnotationsParser::parsePhp(file_get_contents($pathToTestFile));
		$className = array_keys($classes)[0];

		// If test is Cest, give the option to execute individual methods
		if (strripos($className, 'cest'))
		{
			$testFile = new Nette\Reflection\ClassType($className);
			$testMethods = $testFile->getMethods(ReflectionMethod::IS_PUBLIC);

			foreach ($testMethods as $key => $method)
			{
				$this->say('[' . $key . '] ' . $method->name);
			}

			$this->say('');
			$methodNumber = $this->askDefault('Choose the method in the test to run (hit ENTER for All)', 'All');

			if($methodNumber != 'All')
			{
				$method = $testMethods[$methodNumber]->name;
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
     * @return void
     */
    public function runTests($use_htaccess = 0)
    {
        $this->prepareSiteForSystemTests($use_htaccess);

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
             ->arg('--steps')
             ->arg('--debug')
             ->arg('--tap')
             ->arg('--fail-fast')
             ->arg('tests/acceptance/uninstall/')
             ->run()
             ->stopOnFail();

		/* @todo: REDSHOP-2884
        $this->say('preparing for update test');
        $this->getDevelop();
        $this->taskCodecept()
             ->arg('--steps')
             ->arg('--debug')
             ->arg('--fail-fast')
             ->arg('tests/acceptance/update/')
             ->run()
             ->stopOnFail();
		*/

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
				if (!('png' === pathinfo($errorSnapshot, PATHINFO_EXTENSION)))
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
