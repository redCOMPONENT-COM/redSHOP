<?php
/**
 * File containing the class to perform a checkout service call
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
 * CheckoutService
 *
 * @category  Payment
 * @package   KlarnaAPI
 * @author    MINT <ms.modules@klarna.com>
 * @copyright 2014 Klarna AB
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2
 * @link      https://developers.klarna.com/
 */
class CheckoutServiceRequest
{
    /**
     * @var ArrayAccess
     */
    protected $config;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $uri = 'https://api.klarna.com/touchpoint/checkout/';

    /**
     * @var string
     */
    protected $accept = 'application/vnd.klarna.touchpoint-checkout.payment-methods-v1+json';

    /**
     * Constructor
     *
     * @param ArrayAccess $config Configuration
     * @param array       $params Parameters used to build query.
     */
    public function __construct($config, $params)
    {
        $this->config = $config;
        $this->params = array_filter($params);

        if (isset($config['checkout_service_uri'])) {
            $this->uri = $config['checkout_service_uri'];
        }
    }

    /**
     * Create a checkout service response
     *
     * @param int    $code HTTP status code
     * @param string $body HTTP body
     *
     * @return CheckoutServiceResponse Checkout service response
     */
    public function createResponse($code, $body)
    {
        return new CheckoutServiceResponse($this, $code, $body);
    }

    /**
     * Get the url associated to this request
     *
     * @return string URL with query
     */
    public function getURL()
    {
        return $this->uri . '?'. http_build_query($this->params);
    }

    /**
     * Get the headers associated to this request
     *
     * @return array Array of headers strings
     */
    public function getHeaders()
    {
        $digest = Klarna::digest(
            Klarna::colon(
                $this->config['eid'],
                $this->params['currency'],
                $this->config['secret']
            )
        );

        return array(
            "Accept: {$this->accept}",
            "Authorization: xmlrpc-4.2 {$digest}"
        );
    }
}
