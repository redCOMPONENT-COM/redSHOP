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

    // Load tasks from composer, see composer.json
    use Joomla\Testing\Robo\Tasks\LoadTasks;

    /**
     * Downloads and prepares a Joomla CMS site for testing
     *
     * @param   int  $use_htaccess  (1/0) Rename and enable embedded Joomla .htaccess file
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
        $version = '3.8.7';

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
			->arg('--steps')
			->arg('--debug')
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
				->arg('--steps')
				->arg('--debug')
				->arg('--fail-fast')
				->arg( $folder . '/')
				->run()
				->stopOnFail();
	}
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
	public function sendBuildReportErrorSlack($cloudinaryName, $cloudinaryApiKey, $cloudinaryApiSecret, $githubRepository, $githubPRNo, $slackWebhook, $slackChannel, $buildURL)
	{
		$errorSelenium = true;
		$reportError = false;
		$reportFile = 'selenium.log';
		$errorLog = 'Selenium log:' . chr(10). chr(10);

		// Loop through Codeception snapshots
		if (file_exists('_output') && $handler = opendir('_output'))
		{


			$reportFile = '_output/report.tap.log';
			$errorLog = 'Codeception tap log:' . chr(10). chr(10);
			$errorSelenium = false;
			$this->_exec($reportFile);
		}

		if (file_exists($reportFile))
		{

			if ($reportFile)
			{
				$errorLog .= file_get_contents($reportFile, null, null, 15);
				$this->_exec('curl'.$errorLog) ;
			}

			if (!$errorSelenium)
			{
				$handler = opendir('_output');
				$errorImage = '';

				while (!$reportError && false !== ($errorSnapshot = readdir($handler)))
				{
					// Avoid sending system files or html files
					if (!('png' === pathinfo($errorSnapshot, PATHINFO_EXTENSION)))
					{
						continue;
					}

					$reportError = true;

					Cloudinary::config(
						array(
							'cloud_name' => $cloudinaryName,
							'api_key'    => $cloudinaryApiKey,
							'api_secret' => $cloudinaryApiSecret
						)
					);



					$result = \Cloudinary\Uploader::upload(realpath(dirname(__FILE__) . '/_output/' . $errorSnapshot));

					$this->say($errorSnapshot . 'Image sent');

					$errorLog .= '![Screenshot](' . $result['secure_url'] . ')';

					$this->_exec('curl'.$errorLog) ;

					echo $errorLog;

				}
			}

			echo $errorLog;

			if ($reportError || $errorSelenium)
			{
				// Sends the error report to Slack
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
					$reportingTask->setImagesToUpload($errorLog)
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
	public function sendSystemBuildReportErrorSlack($cloudinaryName, $cloudinaryApiKey, $cloudinaryApiSecret, $githubRepository, $githubPRNo, $slackWebhook, $slackChannel, $buildURL = '')
	{
		$errorSelenium = true;
		$reportError = false;
		$reportFile = 'selenium.log';
		$errorLog = 'Selenium log:' . chr(10). chr(10);

		// Loop through Codeception snapshots
		if (file_exists('_output') && $handler = opendir('tests/_output'))
		{


			$reportFile = '_output';
			$errorLog = 'Codeception tap log:' . chr(10). chr(10);
			$errorSelenium = false;
			$this->_exec($reportFile);
		}

		if (file_exists($reportFile))
		{

			if ($reportFile)
			{
				$errorLog .= file_get_contents($reportFile, null, null, 15);
				$this->_exec('curl'.$errorLog) ;
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

					Cloudinary::config(
						array(
							'cloud_name' => $cloudinaryName,
							'api_key'    => $cloudinaryApiKey,
							'api_secret' => $cloudinaryApiSecret
						)
					);



					$result = \Cloudinary\Uploader::upload(realpath(dirname(__FILE__) . '/tests//_output/' . $errorSnapshot));

					$this->say($errorSnapshot . 'Image sent');

					$errorLog .= '![Screenshot](' . $result['secure_url'] . ')';

					$this->_exec('curl'.$errorLog) ;

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
					->setBuildURL($buildURL . 'display/redirect')
					->setSlackWebhook($slackWebhook)
					->setSlackChannel($slackChannel)
					->setTapLog($errorLog);

				if (!empty($errorImage))
				{
					$reportingTask->setImagesToUpload($errorLog)
						->publishCloudinaryImages();
				}

				$reportingTask->publishBuildReportToSlack()
					->run()
					->stopOnFail();
			}
		}
	}

}
