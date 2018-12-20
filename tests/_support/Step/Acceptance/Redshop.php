<?php

namespace Step\Acceptance;

/**
 * Class Redshop
 *
 * @package Step\Acceptance
 *
 * @since  2.1.0
 */
class Redshop extends \AcceptanceTester
{
	/**
	 * Asserts the system message contains the given message.
	 *
	 * @param   string $message The message
	 *
	 * @return  void
	 */
	public function assertSystemMessageContains($message)
	{
		$browser = $this;
		$browser->waitForElement(['id' => 'system-message-container'], 60);
		$browser->waitForText($message, 30, ['id' => 'system-message-container']);
	}
}
