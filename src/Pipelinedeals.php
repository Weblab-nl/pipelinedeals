<?php
// add the namespace
namespace Weblab;

/**
 * Class to access the Pipelinedeals Api
 *
 * @author Weblab.nl - Thomas Marinissen
 */
class Pipelinedeals {

    /**
     * The Pipelinedeals identification token
     *
     * @var string
     */
    private $key;

    /**
     * The base url to pipelinedeals
     *
     * @var string
     */
    private $baseUrl = 'https://api.pipelinedeals.com/api/v3/';

    /**
     * The pipelinedeals instance
     *
     * @var \Weblab\Pipelinedeals|null
     */
    protected static $connection = null;

    /**
     * Get the connection
     *
     * @return  \Weblab\Pipelinedeals               The pipelinedeals instance
     * @throws  \Exception                          Thrown whenever there is no instance set
     */
    public static function connection() {
        // if there is no connection set, throw an error
        if (is_null(self::$connection)) {
            throw new \Exception('No connection set');
        }

        // return the connection
        return self::$connection;
    }

    /**
     * Constructor
     *
     * @param   string                  The key needed to connect to pipelinedeals
     */
    public function __construct($key) {
        // set the access token
        $this->key = $key;

        // set the connection
        self::$connection = $this;
    }

    /**
     * Make a call on the pipelinedeals API
     *
     * @param   string                      The path to call
     * @param   string                      The type of request (GET, POST, PUT, DELETE)
     * @param   array                       A possible request body
     * @return  \stdClass                   The call result
     */
    public function call($path, $type = 'GET', array $data = null) {
        // create the url for the call
        $url = $this->parseUrl($path);

        // initiate the curl instance
        $curl = curl_init();

        // set the header
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );

        // if there is data given, add the post fields and set the content length
        // header
        if (!empty($data)) {
            // get the data as string
            $dataString = json_encode($data);
            // add the header
            $headers[] = 'Content-Length: ' . strlen($dataString);

            // set the post body
            curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
        }

        // set the curl options
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);

        // set the url
        curl_setopt($curl, CURLOPT_URL, $url);

        // execute the curl request
        $response = curl_exec($curl);

        // get the response code
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // close the curl session
        curl_close($curl);

        // if the response code is an error code, return null
        if ($responseCode != 200) {
            return null;
        }

        // done, return the response as xml object
        return json_decode($response);
    }

    /**
     * Helper function to create the entire call url, based on a given path
     *
     * @param   string                  The path for the url
     * @return  string                  The formatted url
     */
    private function parseUrl($path) {
        // get the get parameters from the path
        $pathParsed = parse_url($path);

        // set the query parameters
        $query = array();
        if (isset($pathParsed['query'])) {
            parse_str($pathParsed['query'], $query);
        }

        // remove the query parameters from the path
        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }

        // add the api key
        $query['api_key'] = $this->key;

        // create the entire url and return it
        return $this->baseUrl . $path . '?' . http_build_query($query);
    }

}
