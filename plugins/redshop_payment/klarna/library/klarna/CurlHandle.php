<?php
/**
 * File containing the CurlHandle class.
 *
 * PHP version 5.3
 *
 * @category  Payment
 * @package   KlarnaAPI
 * @author    MINT <ms.modules@klarna.com>
 * @copyright 2014 Klarna AB
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2
 * @link      https://developers.klarna.com/
 */

/**
 * CurlHandle
 *
 * @category  Payment
 * @package   KlarnaAPI
 * @author    MINT <ms.modules@klarna.com>
 * @copyright 2014 Klarna AB
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2
 * @link      https://developers.klarna.com/
 */
class CurlHandle
{
    /**
     * @var cURL
     */
    protected $handle;

    /**
     * Constructor
     *
     * @throws RuntimeException if cURL extension is not loaded
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new RuntimeException(
                'cURL extension is requred.'
            );
        }
    }

    /**
     * Initialize the handle
     *
     * @return void
     */
    public function init()
    {
        $this->handle = curl_init();
    }

    /**
     * Set a cURL option
     *
     * @param int   $name  CURLOPT_* constant
     * @param mixed $value Option value
     *
     * @return void
     */
    public function setOption($name, $value)
    {
        curl_setopt($this->handle, $name, $value);
    }

    /**
     * Execute cURL handle
     *
     * @return void
     */
    public function execute()
    {
        return curl_exec($this->handle);
    }

    /**
     * Get cURL info
     *
     * @return array|false Array with info or false if error occurred
     */
    public function getInfo()
    {
        return curl_getinfo($this->handle);
    }

    /**
     * Get cURL error
     *
     * @return string|false Error message or false if no error occurred
     */
    public function getError()
    {
        return curl_error($this->handle);
    }

    /**
     * Close the cURL handle
     *
     * @return void
     */
    public function close()
    {
        curl_close($this->handle);
    }
}
