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
 * Configuration class for the Klarna instance.
 *
 * You add settings using the ArrayAccess:<br>
 * $arr['field'] = $val or $arr->offsetSet('field', $val);<br>
 *
 * Available settings are:<br>
 * eid         - Merchant ID (int)
 * secret      - Shared secret (string)
 * country     - Country constant or code  (int|string)
 * language    - Language constant or code (int|string)
 * currency    - Currency constant or code (int|string)
 * mode        - Klarna::BETA or Klarna::LIVE
 * xmlrpcDebug - XMLRPC debugging (bool)
 * debug       - Normal debugging (bool)
 * timeout     - XMLRPC timeout in seconds (int)
 */
class Config implements \ArrayAccess
{
    /**
     * An array containing all the options for this config.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Clears the config.
     */
    public function clear()
    {
        $this->options = array();
    }

    /**
     * Returns true whether the field exists.
     *
     * @param mixed $offset field
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->options[$offset]);
    }

    /**
     * Used to get the value of a field.
     *
     * @param mixed $offset field
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            return;
        }

        return $this->options[$offset];
    }

    /**
     * Used to set a value to a field.
     *
     * @param mixed $offset field
     * @param mixed $value  value
     */
    public function offsetSet($offset, $value)
    {
        $this->options[$offset] = $value;
    }

    /**
     * Removes the specified field.
     *
     * @param mixed $offset field
     */
    public function offsetUnset($offset)
    {
        unset($this->options[$offset]);
    }
}
