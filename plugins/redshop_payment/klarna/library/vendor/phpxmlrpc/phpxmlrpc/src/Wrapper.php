<?php
/**
 * @author Gaetano Giunta
 * @copyright (C) 2006-2015 G. Giunta
 * @license code licensed under the BSD License: see file license.txt
 */

namespace PhpXmlRpc;

/**
 * PHP-XMLRPC "wrapper" class - generate stubs to transparently access xmlrpc methods as php functions and vice-versa.
 * Note: this class implements the PROXY pattern, but it is not named so to avoid confusion with http proxies.
 *
 * @todo use some better templating system for code generation?
 * @todo implement method wrapping with preservation of php objs in calls
 * @todo when wrapping methods without obj rebuilding, use return_type = 'phpvals' (faster)
 */
class Wrapper
{
    /// used to hold a reference to object instances whose methods get wrapped by wrapPhpFunction(), in 'create source' mode
    public static $objHolder = array();

    /**
     * Given a string defining a php type or phpxmlrpc type (loosely defined: strings
     * accepted come from javadoc blocks), return corresponding phpxmlrpc type.
     * Notes:
     * - for php 'resource' types returns empty string, since resources cannot be serialized;
     * - for php class names returns 'struct', since php objects can be serialized as xmlrpc structs
     * - for php arrays always return array, even though arrays sometimes serialize as json structs
     * - for 'void' and 'null' returns 'undefined'
     *
     * @param string $phpType
     *
     * @return string
     */
    public function php2XmlrpcType($phpType)
    {
        switch (strtolower($phpType)) {
            case 'string':
                return Value::$xmlrpcString;
            case 'integer':
            case Value::$xmlrpcInt: // 'int'
            case Value::$xmlrpcI4:
            case Value::$xmlrpcI8:
                return Value::$xmlrpcInt;
            case Value::$xmlrpcDouble: // 'double'
                return Value::$xmlrpcDouble;
            case 'bool':
            case Value::$xmlrpcBoolean: // 'boolean'
            case 'false':
            case 'true':
                return Value::$xmlrpcBoolean;
            case Value::$xmlrpcArray: // 'array':
                return Value::$xmlrpcArray;
            case 'object':
            case Value::$xmlrpcStruct: // 'struct'
                return Value::$xmlrpcStruct;
            case Value::$xmlrpcBase64:
                return Value::$xmlrpcBase64;
            case 'resource':
                return '';
            default:
                if (class_exists($phpType)) {
                    return Value::$xmlrpcStruct;
                } else {
                    // unknown: might be any 'extended' xmlrpc type
                    return Value::$xmlrpcValue;
                }
        }
    }

    /**
     * Given a string defining a phpxmlrpc type return the corresponding php type.
     *
     * @param string $xmlrpcType
     *
     * @return string
     */
    public function xmlrpc2PhpType($xmlrpcType)
    {
        switch (strtolower($xmlrpcType)) {
            case 'base64':
            case 'datetime.iso8601':
            case 'string':
                return Value::$xmlrpcString;
            case 'int':
            case 'i4':
            case 'i8':
                return 'integer';
            case 'struct':
            case 'array':
                return 'array';
            case 'double':
                return 'float';
            case 'undefined':
                return 'mixed';
            case 'boolean':
            case 'null':
            default:
                // unknown: might be any xmlrpc type
                return strtolower($xmlrpcType);
        }
    }

    /**
     * Given a user-defined PHP function, create a PHP 'wrapper' function that can
     * be exposed as xmlrpc method from an xmlrpc server object and called from remote
     * clients (as well as its corresponding signature info).
     *
     * Since php is a typeless language, to infer types of input and output parameters,
     * it relies on parsing the javadoc-style comment block associated with the given
     * function. Usage of xmlrpc native types (such as datetime.dateTime.iso8601 and base64)
     * in the @param tag is also allowed, if you need the php function to receive/send
     * data in that particular format (note that base64 encoding/decoding is transparently
     * carried out by the lib, while datetime vals are passed around as strings)
     *
     * Known limitations:
     * - only works for user-defined functions, not for PHP internal functions
     *   (reflection does not support retrieving number/type of params for those)
     * - functions returning php objects will generate special structs in xmlrpc responses:
     *   when the xmlrpc decoding of those responses is carried out by this same lib, using
     *   the appropriate param in php_xmlrpc_decode, the php objects will be rebuilt.
     *   In short: php objects can be serialized, too (except for their resource members),
     *   using this function.
     *   Other libs might choke on the very same xml that will be generated in this case
     *   (i.e. it has a nonstandard attribute on struct element tags)
     *
     * Note that since rel. 2.0RC3 the preferred method to have the server call 'standard'
     * php functions (ie. functions not expecting a single Request obj as parameter)
     * is by making use of the functions_parameters_type class member.
     *
     * @param callable $callable the PHP user function to be exposed as xmlrpc method/ a closure, function name, array($obj, 'methodname') or array('class', 'methodname') are ok
     * @param string $newFuncName (optional) name for function to be created. Used only when return_source in $extraOptions is true
     * @param array $extraOptions (optional) array of options for conversion. valid values include:
     *                            - bool return_source     when true, php code w. function definition will be returned, instead of a closure
     *                            - bool encode_php_objs   let php objects be sent to server using the 'improved' xmlrpc notation, so server can deserialize them as php objects
     *                            - bool decode_php_objs   --- WARNING !!! possible security hazard. only use it with trusted servers ---
     *                            - bool suppress_warnings remove from produced xml any warnings generated at runtime by the php function being invoked
     *
     * @return array|false false on error, or an array containing the name of the new php function,
     *                     its signature and docs, to be used in the server dispatch map
     *
     * @todo decide how to deal with params passed by ref in function definition: bomb out or allow?
     * @todo finish using phpdoc info to build method sig if all params are named but out of order
     * @todo add a check for params of 'resource' type
     * @todo add some trigger_errors / error_log when returning false?
     * @todo what to do when the PHP function returns NULL? We are currently returning an empty string value...
     * @todo add an option to suppress php warnings in invocation of user function, similar to server debug level 3?
     * @todo add a verbatim_object_copy parameter to allow avoiding usage the same obj instance?
     * @todo add an option to allow generated function to skip validation of number of parameters, as that is done by the server anyway
     */
    public function wrapPhpFunction($callable, $newFuncName = '', $extraOptions = array())
    {
        $buildIt = isset($extraOptions['return_source']) ? !($extraOptions['return_source']) : true;

        if (is_string($callable) && strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }
        if (is_array($callable)) {
            if (count($callable) < 2 || (!is_string($callable[0]) && !is_object($callable[0]))) {
                error_log('XML-RPC: ' . __METHOD__ . ': syntax for function to be wrapped is wrong');
                return false;
            }
            if (is_string($callable[0])) {
                $plainFuncName = implode('::', $callable);
            } elseif (is_object($callable[0])) {
                $plainFuncName = get_class($callable[0]) . '->' . $callable[1];
            }
            $exists = method_exists($callable[0], $callable[1]);
        } else if ($callable instanceof \Closure) {
            // we do not support creating code which wraps closures, as php does not allow to serialize them
            if (!$buildIt) {
                error_log('XML-RPC: ' . __METHOD__ . ': a closure can not be wrapped in generated source code');
                return false;
            }

            $plainFuncName = 'Closure';
            $exists = true;
        } else {
            $plainFuncName = $callable;
            $exists = function_exists($callable);
        }

        if (!$exists) {
            error_log('XML-RPC: ' . __METHOD__ . ': function to be wrapped is not defined: ' . $plainFuncName);
            return false;
        }

        $funcDesc = $this->introspectFunction($callable, $plainFuncName);
        if (!$funcDesc) {
            return false;
        }

        $funcSigs = $this->buildMethodSignatures($funcDesc);

        if ($buildIt) {
            $callable = $this->buildWrapFunctionClosure($callable, $extraOptions, $plainFuncName, $funcDesc);
        } else {
            $newFuncName = $this->newFunctionName($callable, $newFuncName, $extraOptions);
            $code = $this->buildWrapFunctionSource($callable, $newFuncName, $extraOptions, $plainFuncName, $funcDesc);
        }

        $ret = array(
            'function' => $callable,
            'signature' => $funcSigs['sigs'],
            'docstring' => $funcDesc['desc'],
            'signature_docs' => $funcSigs['sigsDocs'],
        );
        if (!$buildIt) {
            $ret['function'] = $newFuncName;
            $ret['source'] = $code;
        }
        return $ret;
    }

    /**
     * Introspect a php callable and its phpdoc block and extract information about its signature
     *
     * @param callable $callable
     * @param string $plainFuncName
     * @return array|false
     */
    protected function introspectFunction($callable, $plainFuncName)
    {
        // start to introspect PHP code
        if (is_array($callable)) {
            $func = new \ReflectionMethod($callable[0], $callable[1]);
            if ($func->isPrivate()) {
                error_log('XML-RPC: ' . __METHOD__ . ': method to be wrapped is private: ' . $plainFuncName);
                return false;
            }
            if ($func->isProtected()) {
                error_log('XML-RPC: ' . __METHOD__ . ': method to be wrapped is protected: ' . $plainFuncName);
                return false;
            }
            if ($func->isConstructor()) {
                error_log('XML-RPC: ' . __METHOD__ . ': method to be wrapped is the constructor: ' . $plainFuncName);
                return false;
            }
            if ($func->isDestructor()) {
                error_log('XML-RPC: ' . __METHOD__ . ': method to be wrapped is the destructor: ' . $plainFuncName);
                return false;
            }
            if ($func->isAbstract()) {
                error_log('XML-RPC: ' . __METHOD__ . ': method to be wrapped is abstract: ' . $plainFuncName);
                return false;
            }
            /// @todo add more checks for static vs. nonstatic?
        } else {
            $func = new \ReflectionFunction($callable);
        }
        if ($func->isInternal()) {
            // Note: from PHP 5.1.0 onward, we will possibly be able to use invokeargs
            // instead of getparameters to fully reflect internal php functions ?
            error_log('XML-RPC: ' . __METHOD__ . ': function to be wrapped is internal: ' . $plainFuncName);
            return false;
        }

        // retrieve parameter names, types and description from javadoc comments

        // function description
        $desc = '';
        // type of return val: by default 'any'
        $returns = Value::$xmlrpcValue;
        // desc of return val
        $returnsDocs = '';
        // type + name of function parameters
        $paramDocs = array();

        $docs = $func->getDocComment();
        if ($docs != '') {
            $docs = explode("\n", $docs);
            $i = 0;
            foreach ($docs as $doc) {
                $doc = trim($doc, " \r\t/*");
                if (strlen($doc) && strpos($doc, '@') !== 0 && !$i) {
                    if ($desc) {
                        $desc .= "\n";
                    }
                    $desc .= $doc;
                } elseif (strpos($doc, '@param') === 0) {
                    // syntax: @param type $name [desc]
                    if (preg_match('/@param\s+(\S+)\s+(\$\S+)\s*(.+)?/', $doc, $matches)) {
                        $name = strtolower(trim($matches[2]));
                        //$paramDocs[$name]['name'] = trim($matches[2]);
                        $paramDocs[$name]['doc'] = isset($matches[3]) ? $matches[3] : '';
                        $paramDocs[$name]['type'] = $matches[1];
                    }
                    $i++;
                } elseif (strpos($doc, '@return') === 0) {
                    // syntax: @return type [desc]
                    if (preg_match('/@return\s+(\S+)(\s+.+)?/', $doc, $matches)) {
                        $returns = $matches[1];
                        if (isset($matches[2])) {
                            $returnsDocs = trim($matches[2]);
                        }
                    }
                }
            }
        }

        // execute introspection of actual function prototype
        $params = array();
        $i = 0;
        foreach ($func->getParameters() as $paramObj) {
            $params[$i] = array();
            $params[$i]['name'] = '$' . $paramObj->getName();
            $params[$i]['isoptional'] = $paramObj->isOptional();
            $i++;
        }

        return array(
            'desc' => $desc,
            'docs' => $docs,
            'params' => $params, // array, positionally indexed
            'paramDocs' => $paramDocs, // array, indexed by name
            'returns' => $returns,
            'returnsDocs' =>$returnsDocs,
        );
    }

    /**
     * Given the method description given by introspection, create method signature data
     *
     * @todo support better docs with multiple types separated by pipes by creating multiple signatures
     *       (this is questionable, as it might produce a big matrix of possible signatures with many such occurrences)
     *
     * @param array $funcDesc as generated by self::introspectFunction()
     *
     * @return array
     */
    protected function buildMethodSignatures($funcDesc)
    {
        $i = 0;
        $parsVariations = array();
        $pars = array();
        $pNum = count($funcDesc['params']);
        foreach ($funcDesc['params'] as $param) {
            /* // match by name real param and documented params
            $name = strtolower($param['name']);
            if (!isset($funcDesc['paramDocs'][$name])) {
                $funcDesc['paramDocs'][$name] = array();
            }
            if (!isset($funcDesc['paramDocs'][$name]['type'])) {
                $funcDesc['paramDocs'][$name]['type'] = 'mixed';
            }*/

            if ($param['isoptional']) {
                // this particular parameter is optional. save as valid previous list of parameters
                $parsVariations[] = $pars;
            }

            $pars[] = "\$p$i";
            $i++;
            if ($i == $pNum) {
                // last allowed parameters combination
                $parsVariations[] = $pars;
            }
        }

        if (count($parsVariations) == 0) {
            // only known good synopsis = no parameters
            $parsVariations[] = array();
        }

        $sigs = array();
        $sigsDocs = array();
        foreach ($parsVariations as $pars) {
            // build a signature
            $sig = array($this->php2XmlrpcType($funcDesc['returns']));
            $pSig = array($funcDesc['returnsDocs']);
            for ($i = 0; $i < count($pars); $i++) {
                $name = strtolower($funcDesc['params'][$i]['name']);
                if (isset($funcDesc['paramDocs'][$name]['type'])) {
                    $sig[] = $this->php2XmlrpcType($funcDesc['paramDocs'][$name]['type']);
                } else {
                    $sig[] = Value::$xmlrpcValue;
                }
                $pSig[] = isset($funcDesc['paramDocs'][$name]['doc']) ? $funcDesc['paramDocs'][$name]['doc'] : '';
            }
            $sigs[] = $sig;
            $sigsDocs[] = $pSig;
        }

        return array(
            'sigs' => $sigs,
            'sigsDocs' => $sigsDocs
        );
    }

    /**
     * Creates a closure that will execute $callable
     * @todo validate params? In theory all validation is left to the dispatch map...
     * @todo add support for $catchWarnings
     *
     * @param $callable
     * @param array $extraOptions
     * @param string $plainFuncName
     * @param string $funcDesc
     * @return \Closure
     */
    protected function buildWrapFunctionClosure($callable, $extraOptions, $plainFuncName, $funcDesc)
    {
        $function = function($req) use($callable, $extraOptions, $funcDesc)
        {
            $nameSpace = '\\PhpXmlRpc\\';
            $encoderClass = $nameSpace.'Encoder';
            $responseClass = $nameSpace.'Response';
            $valueClass = $nameSpace.'Value';

            // validate number of parameters received
            // this should be optional really, as we assume the server does the validation
            $minPars = count($funcDesc['params']);
            $maxPars = $minPars;
            foreach ($funcDesc['params'] as $i => $param) {
                if ($param['isoptional']) {
                    // this particular parameter is optional. We assume later ones are as well
                    $minPars = $i;
                    break;
                }
            }
            $numPars = $req->getNumParams();
            if ($numPars < $minPars || $numPars > $maxPars) {
                return new $responseClass(0, 3, 'Incorrect parameters passed to method');
            }

            $encoder = new $encoderClass();
            $options = array();
            if (isset($extraOptions['decode_php_objs']) && $extraOptions['decode_php_objs']) {
                $options[] = 'decode_php_objs';
            }
            $params = $encoder->decode($req, $options);

            $result = call_user_func_array($callable, $params);

            if (! is_a($result, $responseClass)) {
                if ($funcDesc['returns'] == Value::$xmlrpcDateTime || $funcDesc['returns'] == Value::$xmlrpcBase64) {
                    $result = new $valueClass($result, $funcDesc['returns']);
                } else {
                    $options = array();
                    if (isset($extraOptions['encode_php_objs']) && $extraOptions['encode_php_objs']) {
                        $options[] = 'encode_php_objs';
                    }

                    $result = $encoder->encode($result, $options);
                }
                $result = new $responseClass($result);
            }

            return $result;
        };

        return $function;
    }

    /**
     * Return a name for a new function, based on $callable, insuring its uniqueness
     * @param mixed $callable a php callable, or the name of an xmlrpc method
     * @param string $newFuncName when not empty, it is used instead of the calculated version
     * @return string
     */
    protected function newFunctionName($callable, $newFuncName, $extraOptions)
    {
        // determine name of new php function

        $prefix = isset($extraOptions['prefix']) ? $extraOptions['prefix'] : 'xmlrpc';

        if ($newFuncName == '') {
            if (is_array($callable)) {
                if (is_string($callable[0])) {
                    $xmlrpcFuncName = "{$prefix}_" . implode('_', $callable);
                } else {
                    $xmlrpcFuncName = "{$prefix}_" . get_class($callable[0]) . '_' . $callable[1];
                }
            } else {
                if ($callable instanceof \Closure) {
                    $xmlrpcFuncName = "{$prefix}_closure";
                } else {
                    $callable = preg_replace(array('/\./', '/[^a-zA-Z0-9_\x7f-\xff]/'),
                        array('_', ''), $callable);
                    $xmlrpcFuncName = "{$prefix}_$callable";
                }
            }
        } else {
            $xmlrpcFuncName = $newFuncName;
        }

        while (function_exists($xmlrpcFuncName)) {
            $xmlrpcFuncName .= 'x';
        }

        return $xmlrpcFuncName;
    }

    /**
     * @param $callable
     * @param string $newFuncName
     * @param array $extraOptions
     * @param string $plainFuncName
     * @param array $funcDesc
     * @return string
     *
     * @todo add a nice phpdoc block in the generated source
     */
    protected function buildWrapFunctionSource($callable, $newFuncName, $extraOptions, $plainFuncName, $funcDesc)
    {
        $namespace = '\\PhpXmlRpc\\';

        $encodePhpObjects = isset($extraOptions['encode_php_objs']) ? (bool)$extraOptions['encode_php_objs'] : false;
        $decodePhpObjects = isset($extraOptions['decode_php_objs']) ? (bool)$extraOptions['decode_php_objs'] : false;
        $catchWarnings = isset($extraOptions['suppress_warnings']) && $extraOptions['suppress_warnings'] ? '@' : '';

        $i = 0;
        $parsVariations = array();
        $pars = array();
        $pNum = count($funcDesc['params']);
        foreach ($funcDesc['params'] as $param) {

            if ($param['isoptional']) {
                // this particular parameter is optional. save as valid previous list of parameters
                $parsVariations[] = $pars;
            }

            $pars[] = "\$p[$i]";
            $i++;
            if ($i == $pNum) {
                // last allowed parameters combination
                $parsVariations[] = $pars;
            }
        }

        if (count($parsVariations) == 0) {
            // only known good synopsis = no parameters
            $parsVariations[] = array();
            $minPars = 0;
            $maxPars = 0;
        } else {
            $minPars = count($parsVariations[0]);
            $maxPars = count($parsVariations[count($parsVariations)-1]);
        }

        // build body of new function

        $innerCode = "\$paramCount = \$req->getNumParams();\n";
        $innerCode .= "if (\$paramCount < $minPars || \$paramCount > $maxPars) return new {$namespace}Response(0, " . PhpXmlRpc::$xmlrpcerr['incorrect_params'] . ", '" . PhpXmlRpc::$xmlrpcstr['incorrect_params'] . "');\n";

        $innerCode .= "\$encoder = new {$namespace}Encoder();\n";
        if ($decodePhpObjects) {
            $innerCode .= "\$p = \$encoder->decode(\$req, array('decode_php_objs'));\n";
        } else {
            $innerCode .= "\$p = \$encoder->decode(\$req);\n";
        }

        // since we are building source code for later use, if we are given an object instance,
        // we go out of our way and store a pointer to it in a static class var var...
        if (is_array($callable) && is_object($callable[0])) {
            self::$objHolder[$newFuncName] = $callable[0];
            $innerCode .= "\$obj = PhpXmlRpc\\Wrapper::\$objHolder['$newFuncName'];\n";
            $realFuncName = '$obj->' . $callable[1];
        } else {
            $realFuncName = $plainFuncName;
        }
        foreach ($parsVariations as $i => $pars) {
            $innerCode .= "if (\$paramCount == " . count($pars) . ") \$retval = {$catchWarnings}$realFuncName(" . implode(',', $pars) . ");\n";
            if ($i < (count($parsVariations) - 1))
                $innerCode .= "else\n";
        }
        $innerCode .= "if (is_a(\$retval, '{$namespace}Response')) return \$retval; else\n";
        if ($funcDesc['returns'] == Value::$xmlrpcDateTime || $funcDesc['returns'] == Value::$xmlrpcBase64) {
            $innerCode .= "return new {$namespace}Response(new {$namespace}Value(\$retval, '{$funcDesc['returns']}'));";
        } else {
            if ($encodePhpObjects) {
                $innerCode .= "return new {$namespace}Response(\$encoder->encode(\$retval, array('encode_php_objs')));\n";
            } else {
                $innerCode .= "return new {$namespace}Response(\$encoder->encode(\$retval));\n";
            }
        }
        // shall we exclude functions returning by ref?
        // if($func->returnsReference())
        //     return false;

        $code = "function $newFuncName(\$req) {\n" . $innerCode . "\n}";

        return $code;
    }

    /**
     * Given a user-defined PHP class or php object, map its methods onto a list of
     * PHP 'wrapper' functions that can be exposed as xmlrpc methods from an xmlrpc server
     * object and called from remote clients (as well as their corresponding signature info).
     *
     * @param string|object $className the name of the class whose methods are to be exposed as xmlrpc methods, or an object instance of that class
     * @param array $extraOptions see the docs for wrapPhpMethod for basic options, plus
     *                            - string method_type    'static', 'nonstatic', 'all' and 'auto' (default); the latter will switch between static and non-static depending on whether $className is a class name or object instance
     *                            - string method_filter  a regexp used to filter methods to wrap based on their names
     *                            - string prefix         used for the names of the xmlrpc methods created
     *
     * @return array|false false on failure
     */
    public function wrapPhpClass($className, $extraOptions = array())
    {
        $methodFilter = isset($extraOptions['method_filter']) ? $extraOptions['method_filter'] : '';
        $methodType = isset($extraOptions['method_type']) ? $extraOptions['method_type'] : 'auto';
        $prefix = isset($extraOptions['prefix']) ? $extraOptions['prefix'] : '';

        $results = array();
        $mList = get_class_methods($className);
        foreach ($mList as $mName) {
            if ($methodFilter == '' || preg_match($methodFilter, $mName)) {
                $func = new \ReflectionMethod($className, $mName);
                if (!$func->isPrivate() && !$func->isProtected() && !$func->isConstructor() && !$func->isDestructor() && !$func->isAbstract()) {
                    if (($func->isStatic() && ($methodType == 'all' || $methodType == 'static' || ($methodType == 'auto' && is_string($className)))) ||
                        (!$func->isStatic() && ($methodType == 'all' || $methodType == 'nonstatic' || ($methodType == 'auto' && is_object($className))))
                    ) {
                        $methodWrap = $this->wrapPhpFunction(array($className, $mName), '', $extraOptions);
                        if ($methodWrap) {
                            if (is_object($className)) {
                                $realClassName = get_class($className);
                            }else {
                                $realClassName = $className;
                            }
                            $results[$prefix."$realClassName.$mName"] = $methodWrap;
                        }
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Given an xmlrpc client and a method name, register a php wrapper function
     * that will call it and return results using native php types for both
     * params and results. The generated php function will return a Response
     * object for failed xmlrpc calls.
     *
     * Known limitations:
     * - server must support system.methodsignature for the wanted xmlrpc method
     * - for methods that expose many signatures, only one can be picked (we
     *   could in principle check if signatures differ only by number of params
     *   and not by type, but it would be more complication than we can spare time)
     * - nested xmlrpc params: the caller of the generated php function has to
     *   encode on its own the params passed to the php function if these are structs
     *   or arrays whose (sub)members include values of type datetime or base64
     *
     * Notes: the connection properties of the given client will be copied
     * and reused for the connection used during the call to the generated
     * php function.
     * Calling the generated php function 'might' be slow: a new xmlrpc client
     * is created on every invocation and an xmlrpc-connection opened+closed.
     * An extra 'debug' param is appended to param list of xmlrpc method, useful
     * for debugging purposes.
     *
     * @todo allow caller to give us the method signature instead of querying for it, or just say 'skip it'
     * @todo if we can not retrieve method signature, create a php function with varargs
     * @todo allow the created function to throw exceptions on method calls failures
     * @todo if caller did not specify a specific sig, shall we support all of them?
     *       It might be hard (hence slow) to match based on type and number of arguments...
     *
     * @param Client $client an xmlrpc client set up correctly to communicate with target server
     * @param string $methodName the xmlrpc method to be mapped to a php function
     * @param array $extraOptions array of options that specify conversion details. Valid options include
     *                            - integer signum              the index of the method signature to use in mapping (if method exposes many sigs)
     *                            - integer timeout             timeout (in secs) to be used when executing function/calling remote method
     *                            - string  protocol            'http' (default), 'http11' or 'https'
     *                            - string  new_function_name   the name of php function to create, when return_source is used. If unspecified, lib will pick an appropriate name
     *                            - string  return_source       if true return php code w. function definition instead of function itself (closure)
     *                            - bool    encode_php_objs     let php objects be sent to server using the 'improved' xmlrpc notation, so server can deserialize them as php objects
     *                            - bool    decode_php_objs     --- WARNING !!! possible security hazard. only use it with trusted servers ---
     *                            - mixed   return_on_fault     a php value to be returned when the xmlrpc call fails/returns a fault response (by default the Response object is returned in this case). If a string is used, '%faultCode%' and '%faultString%' tokens will be substituted with actual error values
     *                            - bool    debug               set it to 1 or 2 to see debug results of querying server for method synopsis
     *                            - int     simple_client_copy  set it to 1 to have a lightweight copy of the $client object made in the generated code (only used when return_source = true)
     *
     * @return \closure|array|false false on failure, closure by default and array for return_source = true
     */
    public function wrapXmlrpcMethod($client, $methodName, $extraOptions = array())
    {
        $newFuncName = isset($extraOptions['new_function_name']) ? $extraOptions['new_function_name'] : '';

        $buildIt = isset($extraOptions['return_source']) ? !($extraOptions['return_source']) : true;

        $mSig = $this->retrieveMethodSignature($client, $methodName, $extraOptions);
        if (!$mSig) {
            return false;
        }

        if ($buildIt) {
            return $this->buildWrapMethodClosure($client, $methodName, $extraOptions, $mSig);
        } else {
            // if in 'offline' mode, retrieve method description too.
            // in online mode, favour speed of operation
            $mDesc = $this->retrieveMethodHelp($client, $methodName, $extraOptions);

            $newFuncName = $this->newFunctionName($methodName, $newFuncName, $extraOptions);

            $results = $this->buildWrapMethodSource($client, $methodName, $extraOptions, $newFuncName, $mSig, $mDesc);
            /* was: $results = $this->build_remote_method_wrapper_code($client, $methodName,
                $newFuncName, $mSig, $mDesc, $timeout, $protocol, $simpleClientCopy,
                $prefix, $decodePhpObjects, $encodePhpObjects, $decodeFault,
                $faultResponse, $namespace);*/

            $results['function'] = $newFuncName;

            return $results;
        }

    }

    /**
     * Retrieves an xmlrpc method signature from a server which supports system.methodSignature
     * @param Client $client
     * @param string $methodName
     * @param array $extraOptions
     * @return false|array
     */
    protected function retrieveMethodSignature($client, $methodName, array $extraOptions = array())
    {
        $namespace = '\\PhpXmlRpc\\';
        $reqClass = $namespace . 'Request';
        $valClass = $namespace . 'Value';
        $decoderClass = $namespace . 'Encoder';

        $debug = isset($extraOptions['debug']) ? ($extraOptions['debug']) : 0;
        $timeout = isset($extraOptions['timeout']) ? (int)$extraOptions['timeout'] : 0;
        $protocol = isset($extraOptions['protocol']) ? $extraOptions['protocol'] : '';
        $sigNum = isset($extraOptions['signum']) ? (int)$extraOptions['signum'] : 0;

        $req = new $reqClass('system.methodSignature');
        $req->addparam(new $valClass($methodName));
        $client->setDebug($debug);
        $response = $client->send($req, $timeout, $protocol);
        if ($response->faultCode()) {
            error_log('XML-RPC: ' . __METHOD__ . ': could not retrieve method signature from remote server for method ' . $methodName);
            return false;
        }

        $mSig = $response->value();
        if ($client->return_type != 'phpvals') {
            $decoder = new $decoderClass();
            $mSig = $decoder->decode($mSig);
        }

        if (!is_array($mSig) || count($mSig) <= $sigNum) {
            error_log('XML-RPC: ' . __METHOD__ . ': could not retrieve method signature nr.' . $sigNum . ' from remote server for method ' . $methodName);
            return false;
        }

        return $mSig[$sigNum];
    }

    /**
     * @param Client $client
     * @param string $methodName
     * @param array $extraOptions
     * @return string in case of any error, an empty string is returned, no warnings generated
     */
    protected function retrieveMethodHelp($client, $methodName, array $extraOptions = array())
    {
        $namespace = '\\PhpXmlRpc\\';
        $reqClass = $namespace . 'Request';
        $valClass = $namespace . 'Value';

        $debug = isset($extraOptions['debug']) ? ($extraOptions['debug']) : 0;
        $timeout = isset($extraOptions['timeout']) ? (int)$extraOptions['timeout'] : 0;
        $protocol = isset($extraOptions['protocol']) ? $extraOptions['protocol'] : '';

        $mDesc = '';

        $req = new $reqClass('system.methodHelp');
        $req->addparam(new $valClass($methodName));
        $client->setDebug($debug);
        $response = $client->send($req, $timeout, $protocol);
        if (!$response->faultCode()) {
            $mDesc = $response->value();
            if ($client->return_type != 'phpvals') {
                $mDesc = $mDesc->scalarval();
            }
        }

        return $mDesc;
    }

    /**
     * @param Client $client
     * @param string $methodName
     * @param array $extraOptions
     * @param string $mSig
     * @return \Closure
     *
     * @todo should we allow usage of parameter simple_client_copy to mean 'do not clone' in this case?
     */
    protected function buildWrapMethodClosure($client, $methodName, array $extraOptions, $mSig)
    {
        // we clone the client, so that we can modify it a bit independently of the original
        $clientClone = clone $client;
        $function = function() use($clientClone, $methodName, $extraOptions, $mSig)
        {
            $timeout = isset($extraOptions['timeout']) ? (int)$extraOptions['timeout'] : 0;
            $protocol = isset($extraOptions['protocol']) ? $extraOptions['protocol'] : '';
            $encodePhpObjects = isset($extraOptions['encode_php_objs']) ? (bool)$extraOptions['encode_php_objs'] : false;
            $decodePhpObjects = isset($extraOptions['decode_php_objs']) ? (bool)$extraOptions['decode_php_objs'] : false;
            if (isset($extraOptions['return_on_fault'])) {
                $decodeFault = true;
                $faultResponse = $extraOptions['return_on_fault'];
            } else {
                $decodeFault = false;
            }

            $namespace = '\\PhpXmlRpc\\';
            $reqClass = $namespace . 'Request';
            $encoderClass = $namespace . 'Encoder';
            $valueClass = $namespace . 'Value';

            $encoder = new $encoderClass();
            $encodeOptions = array();
            if ($encodePhpObjects) {
                $encodeOptions[] = 'encode_php_objs';
            }
            $decodeOptions = array();
            if ($decodePhpObjects) {
                $decodeOptions[] = 'decode_php_objs';
            }

            /// @todo check for insufficient nr. of args besides excess ones? note that 'source' version does not...

            // support one extra parameter: debug
            $maxArgs = count($mSig)-1; // 1st element is the return type
            $currentArgs = func_get_args();
            if (func_num_args() == ($maxArgs+1)) {
                $debug = array_pop($currentArgs);
                $clientClone->setDebug($debug);
            }

            $xmlrpcArgs = array();
            foreach($currentArgs as $i => $arg) {
                if ($i == $maxArgs) {
                    break;
                }
                $pType = $mSig[$i+1];
                if ($pType == 'i4' || $pType == 'i8' || $pType == 'int' || $pType == 'boolean' || $pType == 'double' ||
                    $pType == 'string' || $pType == 'dateTime.iso8601' || $pType == 'base64' || $pType == 'null'
                ) {
                    // by building directly xmlrpc values when type is known and scalar (instead of encode() calls),
                    // we make sure to honour the xmlrpc signature
                    $xmlrpcArgs[] = new $valueClass($arg, $pType);
                } else {
                    $xmlrpcArgs[] = $encoder->encode($arg, $encodeOptions);
                }
            }

            $req = new $reqClass($methodName, $xmlrpcArgs);
            // use this to get the maximum decoding flexibility
            $clientClone->return_type = 'xmlrpcvals';
            $resp = $clientClone->send($req, $timeout, $protocol);
            if ($resp->faultcode()) {
                if ($decodeFault) {
                    if (is_string($faultResponse) && ((strpos($faultResponse, '%faultCode%') !== false) ||
                            (strpos($faultResponse, '%faultString%') !== false))) {
                        $faultResponse = str_replace(array('%faultCode%', '%faultString%'),
                            array($resp->faultCode(), $resp->faultString()), $faultResponse);
                    }
                    return $faultResponse;
                } else {
                    return $resp;
                }
            } else {
                return $encoder->decode($resp->value(), $decodeOptions);
            }
        };

        return $function;
    }

    /**
     * @param Client $client
     * @param string $methodName
     * @param array $extraOptions
     * @param string $newFuncName
     * @param array $mSig
     * @param string $mDesc
     * @return array
     */
    public function buildWrapMethodSource($client, $methodName, array $extraOptions, $newFuncName, $mSig, $mDesc='')
    {
        $timeout = isset($extraOptions['timeout']) ? (int)$extraOptions['timeout'] : 0;
        $protocol = isset($extraOptions['protocol']) ? $extraOptions['protocol'] : '';
        $encodePhpObjects = isset($extraOptions['encode_php_objs']) ? (bool)$extraOptions['encode_php_objs'] : false;
        $decodePhpObjects = isset($extraOptions['decode_php_objs']) ? (bool)$extraOptions['decode_php_objs'] : false;
        $clientCopyMode = isset($extraOptions['simple_client_copy']) ? (int)($extraOptions['simple_client_copy']) : 0;
        $prefix = isset($extraOptions['prefix']) ? $extraOptions['prefix'] : 'xmlrpc';
        if (isset($extraOptions['return_on_fault'])) {
            $decodeFault = true;
            $faultResponse = $extraOptions['return_on_fault'];
        } else {
            $decodeFault = false;
            $faultResponse = '';
        }

        $namespace = '\\PhpXmlRpc\\';

        $code = "function $newFuncName (";
        if ($clientCopyMode < 2) {
            // client copy mode 0 or 1 == full / partial client copy in emitted code
            $verbatimClientCopy = !$clientCopyMode;
            $innerCode = $this->buildClientWrapperCode($client, $verbatimClientCopy, $prefix, $namespace);
            $innerCode .= "\$client->setDebug(\$debug);\n";
            $this_ = '';
        } else {
            // client copy mode 2 == no client copy in emitted code
            $innerCode = '';
            $this_ = 'this->';
        }
        $innerCode .= "\$req = new {$namespace}Request('$methodName');\n";

        if ($mDesc != '') {
            // take care that PHP comment is not terminated unwillingly by method description
            $mDesc = "/**\n* " . str_replace('*/', '* /', $mDesc) . "\n";
        } else {
            $mDesc = "/**\nFunction $newFuncName\n";
        }

        // param parsing
        $innerCode .= "\$encoder = new {$namespace}Encoder();\n";
        $plist = array();
        $pCount = count($mSig);
        for ($i = 1; $i < $pCount; $i++) {
            $plist[] = "\$p$i";
            $pType = $mSig[$i];
            if ($pType == 'i4' || $pType == 'i8' || $pType == 'int' || $pType == 'boolean' || $pType == 'double' ||
                $pType == 'string' || $pType == 'dateTime.iso8601' || $pType == 'base64' || $pType == 'null'
            ) {
                // only build directly xmlrpc values when type is known and scalar
                $innerCode .= "\$p$i = new {$namespace}Value(\$p$i, '$pType');\n";
            } else {
                if ($encodePhpObjects) {
                    $innerCode .= "\$p$i = \$encoder->encode(\$p$i, array('encode_php_objs'));\n";
                } else {
                    $innerCode .= "\$p$i = \$encoder->encode(\$p$i);\n";
                }
            }
            $innerCode .= "\$req->addparam(\$p$i);\n";
            $mDesc .= '* @param ' . $this->xmlrpc2PhpType($pType) . " \$p$i\n";
        }
        if ($clientCopyMode < 2) {
            $plist[] = '$debug=0';
            $mDesc .= "* @param int \$debug when 1 (or 2) will enable debugging of the underlying {$prefix} call (defaults to 0)\n";
        }
        $plist = implode(', ', $plist);
        $mDesc .= '* @return ' . $this->xmlrpc2PhpType($mSig[0]) . " (or an {$namespace}Response obj instance if call fails)\n*/\n";

        $innerCode .= "\$res = \${$this_}client->send(\$req, $timeout, '$protocol');\n";
        if ($decodeFault) {
            if (is_string($faultResponse) && ((strpos($faultResponse, '%faultCode%') !== false) || (strpos($faultResponse, '%faultString%') !== false))) {
                $respCode = "str_replace(array('%faultCode%', '%faultString%'), array(\$res->faultCode(), \$res->faultString()), '" . str_replace("'", "''", $faultResponse) . "')";
            } else {
                $respCode = var_export($faultResponse, true);
            }
        } else {
            $respCode = '$res';
        }
        if ($decodePhpObjects) {
            $innerCode .= "if (\$res->faultcode()) return $respCode; else return \$encoder->decode(\$res->value(), array('decode_php_objs'));";
        } else {
            $innerCode .= "if (\$res->faultcode()) return $respCode; else return \$encoder->decode(\$res->value());";
        }

        $code = $code . $plist . ") {\n" . $innerCode . "\n}\n";

        return array('source' => $code, 'docstring' => $mDesc);
    }

    /**
     * Similar to wrapXmlrpcMethod, but will generate a php class that wraps
     * all xmlrpc methods exposed by the remote server as own methods.
     * For more details see wrapXmlrpcMethod.
     *
     * For a slimmer alternative, see the code in demo/client/proxy.php
     *
     * Note that unlike wrapXmlrpcMethod, we always have to generate php code here. It seems that php 7 will have anon classes...
     *
     * @param Client $client the client obj all set to query the desired server
     * @param array $extraOptions list of options for wrapped code. See the ones from wrapXmlrpcMethod plus
     *              - string method_filter      regular expression
     *              - string new_class_name
     *              - string prefix
     *              - bool   simple_client_copy set it to true to avoid copying all properties of $client into the copy made in the new class
     *
     * @return mixed false on error, the name of the created class if all ok or an array with code, class name and comments (if the appropriatevoption is set in extra_options)
     */
    public function wrapXmlrpcServer($client, $extraOptions = array())
    {
        $methodFilter = isset($extraOptions['method_filter']) ? $extraOptions['method_filter'] : '';
        $timeout = isset($extraOptions['timeout']) ? (int)$extraOptions['timeout'] : 0;
        $protocol = isset($extraOptions['protocol']) ? $extraOptions['protocol'] : '';
        $newClassName = isset($extraOptions['new_class_name']) ? $extraOptions['new_class_name'] : '';
        $encodePhpObjects = isset($extraOptions['encode_php_objs']) ? (bool)$extraOptions['encode_php_objs'] : false;
        $decodePhpObjects = isset($extraOptions['decode_php_objs']) ? (bool)$extraOptions['decode_php_objs'] : false;
        $verbatimClientCopy = isset($extraOptions['simple_client_copy']) ? !($extraOptions['simple_client_copy']) : true;
        $buildIt = isset($extraOptions['return_source']) ? !($extraOptions['return_source']) : true;
        $prefix = isset($extraOptions['prefix']) ? $extraOptions['prefix'] : 'xmlrpc';
        $namespace = '\\PhpXmlRpc\\';

        $reqClass = $namespace . 'Request';
        $decoderClass = $namespace . 'Encoder';

        $req = new $reqClass('system.listMethods');
        $response = $client->send($req, $timeout, $protocol);
        if ($response->faultCode()) {
            error_log('XML-RPC: ' . __METHOD__ . ': could not retrieve method list from remote server');

            return false;
        } else {
            $mList = $response->value();
            if ($client->return_type != 'phpvals') {
                $decoder = new $decoderClass();
                $mList = $decoder->decode($mList);
            }
            if (!is_array($mList) || !count($mList)) {
                error_log('XML-RPC: ' . __METHOD__ . ': could not retrieve meaningful method list from remote server');

                return false;
            } else {
                // pick a suitable name for the new function, avoiding collisions
                if ($newClassName != '') {
                    $xmlrpcClassName = $newClassName;
                } else {
                    $xmlrpcClassName = $prefix . '_' . preg_replace(array('/\./', '/[^a-zA-Z0-9_\x7f-\xff]/'),
                            array('_', ''), $client->server) . '_client';
                }
                while ($buildIt && class_exists($xmlrpcClassName)) {
                    $xmlrpcClassName .= 'x';
                }

                /// @todo add function setdebug() to new class, to enable/disable debugging
                $source = "class $xmlrpcClassName\n{\npublic \$client;\n\n";
                $source .= "function __construct()\n{\n";
                $source .= $this->buildClientWrapperCode($client, $verbatimClientCopy, $prefix, $namespace);
                $source .= "\$this->client = \$client;\n}\n\n";
                $opts = array(
                    'return_source' => true,
                    'simple_client_copy' => 2, // do not produce code to copy the client object
                    'timeout' => $timeout,
                    'protocol' => $protocol,
                    'encode_php_objs' => $encodePhpObjects,
                    'decode_php_objs' => $decodePhpObjects,
                    'prefix' => $prefix,
                );
                /// @todo build phpdoc for class definition, too
                foreach ($mList as $mName) {
                    if ($methodFilter == '' || preg_match($methodFilter, $mName)) {
                        // note: this will fail if server exposes 2 methods called f.e. do.something and do_something
                        $opts['new_function_name'] = preg_replace(array('/\./', '/[^a-zA-Z0-9_\x7f-\xff]/'),
                            array('_', ''), $mName);
                        $methodWrap = $this->wrapXmlrpcMethod($client, $mName, $opts);
                        if ($methodWrap) {
                            if (!$buildIt) {
                                $source .= $methodWrap['docstring'];
                            }
                            $source .= $methodWrap['source'] . "\n";
                        } else {
                            error_log('XML-RPC: ' . __METHOD__ . ': will not create class method to wrap remote method ' . $mName);
                        }
                    }
                }
                $source .= "}\n";
                if ($buildIt) {
                    $allOK = 0;
                    eval($source . '$allOK=1;');
                    if ($allOK) {
                        return $xmlrpcClassName;
                    } else {
                        error_log('XML-RPC: ' . __METHOD__ . ': could not create class ' . $xmlrpcClassName . ' to wrap remote server ' . $client->server);
                        return false;
                    }
                } else {
                    return array('class' => $xmlrpcClassName, 'code' => $source, 'docstring' => '');
                }
            }
        }
    }

    /**
     * Given necessary info, generate php code that will build a client object just like the given one.
     * Take care that no full checking of input parameters is done to ensure that
     * valid php code is emitted.
     * @param Client $client
     * @param bool $verbatimClientCopy when true, copy all of the state of the client, except for 'debug' and 'return_type'
     * @param string $prefix used for the return_type of the created client
     * @param string $namespace
     *
     * @return string
     */
    protected function buildClientWrapperCode($client, $verbatimClientCopy, $prefix = 'xmlrpc', $namespace = '\\PhpXmlRpc\\' )
    {
        $code = "\$client = new {$namespace}Client('" . str_replace("'", "\'", $client->path) .
            "', '" . str_replace("'", "\'", $client->server) . "', $client->port);\n";

        // copy all client fields to the client that will be generated runtime
        // (this provides for future expansion or subclassing of client obj)
        if ($verbatimClientCopy) {
            foreach ($client as $fld => $val) {
                if ($fld != 'debug' && $fld != 'return_type') {
                    $val = var_export($val, true);
                    $code .= "\$client->$fld = $val;\n";
                }
            }
        }
        // only make sure that client always returns the correct data type
        $code .= "\$client->return_type = '{$prefix}vals';\n";
        //$code .= "\$client->setDebug(\$debug);\n";
        return $code;
    }
}
