<?php
/**
 * Copyright 2016 Klarna AB.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Klarna\XMLRPC;

/**
 * Checkout service response class.
 *
 * @example docs/examples/checkoutService.php How to use the checkout service.
 */
class CheckoutServiceResponse
{
    /**
     * Originating request.
     *
     * @var CheckoutServiceRequest
     */
    protected $request;

    /**
     * HTTP status code.
     *
     * @var int
     */
    protected $status;

    /**
     * Response data.
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param CheckoutServiceRequest $request The original request
     * @param int                    $status  HTTP status code
     * @param string                 $data    JSON string
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($request, $status, $data)
    {
        $this->request = $request;
        $this->status = $status;
        $data = json_decode($data, true);

        if ($data === null) {
            throw new \InvalidArgumentException('Expected $data to be a valid JSON string');
        }

        $this->data = $data;
    }

    /**
     * Get the original request.
     *
     * @return CheckoutServiceRequest The original request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the response status code.
     *
     * @return int Status code
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the response data.
     *
     * @return array Response data
     */
    public function getData()
    {
        return $this->data;
    }
}
