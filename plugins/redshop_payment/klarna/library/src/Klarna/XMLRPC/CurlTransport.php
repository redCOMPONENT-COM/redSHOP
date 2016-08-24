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
 * Class for handling curl requests.
 */
class CurlTransport
{
    /**
     * Wrapper for the cURL resource.
     *
     * @var CurlHandle
     */
    protected $handle;

    /**
     * Constructor.
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
     * Get time-out seconds.
     *
     * @return int Time-out seconds
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Send a request object.
     *
     * @param object $request The request to send
     *
     * @throws Exceptions\KlarnaException For e.g. a timeout
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
            throw new Exceptions\KlarnaException(
                "Connection failed with error: {$error}"
            );
        }

        return $request->createResponse(intval($info['http_code']), $data);
    }
}
