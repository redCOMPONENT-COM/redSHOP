<?php
namespace AcceptanceTester;

class TestingSteps extends AdminManagerSteps
{
	public function testingNotice()
	{
		$I = $this;
		$I->executeInSelenium(
			function (\WebDriver $webdriver) {
				$webdriver->get('http://bugsquad.eu/errors/notice.php');

			}
		);
		$text = $I->grabTextFrom('//body');
		codecept_debug($text);
		$I->verifyNotices(false, $this->checkForNotices(), 'Testing Notices');
	}
}
