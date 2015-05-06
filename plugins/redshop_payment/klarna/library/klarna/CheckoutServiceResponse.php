<?php
/**
 * File containing the CheckoutServiceResponse class.
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
 * CheckoutServiceResponse
 *
 * @category  Payment
 * @package   KlarnaAPI
 * @author    MINT <ms.modules@klarna.com>
 * @copyright 2014 Klarna AB
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2
 * @link      https://developers.klarna.com/
 */
class CheckoutServiceResponse
{
    /**
     * @var CheckoutServiceRequest
     */
    protected $request;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var array
     */
    protected $data;

    /**
     * Constructor
     *
     * @param CheckoutServiceRequest $request The original request
     * @param int                    $status  HTTP status code
     * @param string                 $data    JSON string
     */
    public function __construct($request, $status, $data)
    {
        $this->request = $request;
        $this->status = $status;
        $data = json_decode($data, true);

        if ($data === null) {
            throw new InvalidArgumentException('data must be a valid JSON string');
        }

        $this->data = $data;
    }

    /**
     * Get the original request
     *
     * @return CheckoutServiceRequest The original request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the response status code
     *
     * @return int Status code
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the response data
     *
     * @return array Response data
     */
    public function getData()
    {
        return $this->data;
    }
}
