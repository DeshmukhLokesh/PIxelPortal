<?php

/**
 * API Class for web part of the VCS
 */
class VcsApi
{
    /**
     * Constructor initializes connection parameters
     *
     * @param string $host
     * @param string $username
     * @param string $password
     */
    function __construct($host, $username, $password, $port = 443, $verbose = 0)
    {
        // check curl extension
        if (!function_exists('curl_exec')) {
            throw new VcsApiProtocolError(101, 'Extension "curl" is required to run API');
        }

        // by default we are using https
        $this->__uri_scheme = $port == 80 ? 'http://' : 'https://';
        
        // init parameters
        
        $this->__host = $host;
        $this->__port = $port;
        
        $this->__host = $host; 

        $this->__url = $this->__uri_scheme.$host.':'.$port.'/xmlrpc/';
        $this->__urlf = $this->__uri_scheme.$host.':'.$port.'/admin/';
        $this->__username = $username;
        $this->__password = $password;
        $this->__session_id = null;

        // build curl object
        $this->__ch = curl_init();
        curl_setopt($this->__ch, CURLOPT_URL, $this->__url);
        curl_setopt($this->__ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->__ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->__ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($this->__ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->__ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->__ch, CURLOPT_VERBOSE, $verbose);
    }

    /**
     * Closes curl object on descruction
     */
    function __destruct()
    {
        curl_close($this->__ch);
    }

    /**
     * Returns instance of pseudo-module for making call
     */
    function __get($module)
    {
        return new VcsApiCaller($this, $module);
    }

    /**
     * Makes call by XML-RPC
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    function __request($method, $params = array())
    {
        // pass only first param
        if ($params) {
            $params = array_shift($params);
        }

        // build authorization
        if ($this->__session_id) {
            $params['SID'] = $this->__session_id;
        } else {
            $params['auth'] = array(
                'login' => $this->__username,
                'password' => $this->__password
            );
        }

        // build request
        
        
        $request = xmlrpc_encode_request($method, $params);

        // make call [do not catch exception here]
        $response = $this->__call_curl($request);

        // parse XML-RPC reply
        $data = xmlrpc_decode($response);
        if (is_null($data)) {
            throw new VcsApiProtocolError(121, 'Response format error [response: '.substr($response, 0, 128).']');
        }
        if (xmlrpc_is_fault($data)) {
            throw new VcsApiError($data['faultCode'], $data['faultString']);
        }

        // save session_id if we have if
        if (isset($data['session_id']) && $data['session_id']) {
            $this->__session_id = $data['session_id'];
        }

        return new VcsApiResponse($data);
    }

    /**
     * Makes call with curl and return raw reply
     *
     * @param string $request
     * @param resource $ch  custom curl object
     * @return string
     */
    function __call_curl($request, $ch = null)
    {
        // build headers
        $headers = array(
            'Content-length: '.strlen($request),
            
            // fix problem with multipart request
            'Expect: ',
            'Transfer-Encoding: '
        );

        // check curl object
        if (!$ch) {
            $ch = $this->__ch;
            $headers[] = 'Content-type: text/xml';
        }

        // prepare call
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        // make call
        $data = curl_exec($ch);
        if ($error = curl_error($ch)) {
            throw new VcsApiProtocolError(110, 'Transport: '.$error);
        }

        // check http code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            throw new VcsApiProtocolError($httpCode, 'HTTP: '.$httpCode);
        }

        return $data;
    }

    /**
     * Download file by API request
     */
    function file($method, $params = array(), $dir = null, $realname = true)
    {
        // build and encode params
        $params['__api'] = 1;
        $params['auth'] = array(
            'login' => $this->__username,
            'password' => $this->__password
        );
        $uparams = http_build_query($params);

        // build url
        $url = $this->__urlf.str_replace('.', '/', $method);

        // init temp file
        $filename = tempnam($dir, 'api');
        $fp = fopen($filename, 'wb');

        // init new curl object
        $ch = curl_copy_handle($this->__ch);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, 'fileHeader'));

        // init headers storage
        $this->__headers = array();

        // make request
        try {
            $this->__call_curl($uparams, $ch);
            fclose($fp);
        } catch(Exception $e) {
            @unlink($filename);
            throw $e;
        }

        // check logical status
        if (isset($this->__headers['x-vcs-status'])) {
            @unlink($filename);
            throw new VcsApiError(intval($this->__headers['x-vcs-status']), $url);
        }

        // build result
        $R = array(
            'file' => $filename,
            'name' => null,
            'type' => null
        );

        // get filename
        if (isset($this->__headers['content-disposition'])) {
            $R['name'] = explode('filename=', $this->__headers['content-disposition']);
            $R['name'] = str_replace(array('"', '\''), array('', ''), $R['name'][1]);
        }

        // get content type
        $R['type'] = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $R['type'] = trim(array_shift(explode(';', $R['type'])));

        // rename if we need realname
        if ($R['name'] && $realname) {
            $dst = dirname($R['file']).'/'.$R['name'];
            rename($R['file'], $dst);
            $R['file'] = $dst;
        }

        return $R;
    }

    /**
     * Helper function
     */
    function fileHeader($ch, $header)
    {
        $_header = explode(':', $header, 2);
        if (count($_header) == 2) {
            $this->__headers[trim(strtolower($_header[0]))] = trim($_header[1]);
        }
        return strlen($header);
    }
}

/**
 * API Class for core part of the VCS
 */
class VcsCoreApi extends VcsApi
{
    /**
     * Re-init API for core
     */
    function __construct($host, $username, $password, $port = 2080, $verbose = 0)
    {
        parent::__construct($host, $username, $password, $port, $verbose);

        // change url
        $this->__url = 'http://'.$host.':'.$port.'/';
        //$this->__url = $this->__uri_scheme.$host.':'.$port.'/';
        curl_setopt($this->__ch, CURLOPT_URL, $this->__url);

        // set auth
        curl_setopt($this->__ch, CURLOPT_USERPWD, $username.':'.$password);
    }

    /**
     * Rework request
     */
    function __request($method, $params = array())
    {
        // build request
        $request = xmlrpc_encode_request($method, $params);

        // make call [do not catch exception here]
        $response = $this->__call_curl($request);

        // parse XML-RPC reply
        $data = xmlrpc_decode($response);
        if (is_null($data)) {
            throw new VcsApiProtocolError(121, 'Response format error [response: '.substr($response, 0, 128).']');
        }
        if (is_array($data) && xmlrpc_is_fault($data)) {
            throw new VcsApiError($data['faultCode'], $data['faultString']);
        }

        return $data;
    }
}

/**
 * Request error
 */
class VcsApiError extends Exception
{
    function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }
}

/**
 * Processing error [http error]
 */
class VcsApiProtocolError extends VcsApiError {}


/**
 * Normal response from the request
 */
class VcsApiResponse implements ArrayAccess
{
    /**
     * Constructor that initializes server response object
     *
     * @param array $data   data got from XML-RPC
     */
    function __construct($data)
    {
        $this->code = isset($data['code']) ? $data['code'] : null;
        $this->messages = isset($data['messages']) ? $data['messages'] : array();
        $this->data = isset($data['return']) ? $data['return'] : array();
    }

    // {{{ ArrayAccess

    function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }
    function offsetGet($offset)
    {
        return $this->data[$offset];
    }
    function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }
    function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    // }}}
}


/**
 * Technical class required to process sub-calls, like
 * $api->clients->list();
 */
class VcsApiCaller
{
    /**
     * Saves instance of BB API
     */
    function __construct(&$api, $module)
    {
        $this->__api = $api;
        $this->__module = $module;
    }

    /**
     * Makes call
     */
    function __call($function, $params)
    {
        $_params = array($this->__module.'.'.$function);
        if ($params) {
            $_params[] = $params;
        }
        // PHP 5.3 required
        //return forward_static_call_array(array($this->__api, '__request'), $_params);
        return call_user_func_array(array($this->__api, '__request'), $_params);
    }
}
