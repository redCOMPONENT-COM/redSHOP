<?php
/**
 * File containing the CurlTransport class.
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
 * CurlTransport
 *
 * @category  Payment
 * @package   KlarnaAPI
 * @author    MINT <ms.modules@klarna.com>
 * @copyright 2014 Klarna AB
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2
 * @link      https://developers.klarna.com/
 */
class CurlTransport
{
    /**
     * @var CurlHandle
     */
    protected $handle;

    /**
     * Constructor
     *
     * @param CurlHandle $handle  Curl handle to use
     * @param int        $timeout Time-out in seconds
     */
    public function __construct($handle, $timeout)
    {
        $this->handle = $handle;
        $this->timeout = $timeout;
    }

    /**
     * Get time-out seconds
     *
     * @return int Time-out seconds
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Send a request object
     *
     * @param object $request The request to send
     *
     * @throws KlarnaException For e.g. a timeout
     *
     * @return object A response to the request sent
     */
    public function send($request)
    {
        $this->handle->init();

        $this->handle->setOption(CURLOPT_URL, $request->getURL());
        $this->handle->setOption(CURLOPT_HTTPHEADER, $request->getHeaders());
        $this->handle->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->handle->setOption(CURLOPT_CONNECTTIMEOUT, $this->getTimeout());
        $this->handle->setOption(CURLOPT_TIMEOUT, $this->getTimeout());
        $this->handle->setOption(CURLOPT_SSL_VERIFYHOST, 2);
        $this->handle->setOption(CURLOPT_SSL_VERIFYPEER, true);

        $data = $this->handle->execute();
        $info = $this->handle->getInfo();
        $error = $this->handle->getError();

        $this->handle->close();

        /*
         * A failure occurred if:
         * payload is false (e.g. HTTP timeout?).
         * info is false, then it has no HTTP status code.
         */
        if (strlen($error) > 0) {
            throw new KlarnaException(
                "Connection failed with error: {$error}"
            );
        }

        return $request->createResponse(intval($info['http_code']), $data);
    }
}
