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
    private $token;
    
    /**
     * The base url to pipelinedeals
     * 
     * @var string
     */
    private $baseUrl = 'https://api.pipelinedeals.com/api/v3/';

    /**
     * The total time (there is a maximum of 10 calls every 5 seconds)
     * 
     * @var float
     */
    private $totalTime = 0;
    
    /**
     * The total number of calls the last 5 seconds
     * 
     * @var int
     */
    private $totalCalls = 0;

    /**
     * Constructor
     *
     * @param   string                  The key needed to connect to pipelinedeals
     */
    public function __construct($token) {
        // set the access token
        $this->token = $token;
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
        // make sure it is possible to call the pipelinedeals api
        $this->readyForCall();
        
        // create the url for the call
        $url = $this->parseUrl($path);

        // initiate the curl instance
        $curl = curl_init();
        
        // get the data as string
        
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

        // enable tracking the header that is sent out
        //curl_setopt($curl, CURLINFO_HEADER_OUT, true);

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
     * Get the company for a given pipelinedeals id
     * 
     * @param   int                                           The Pipelinedeals company identifier
     * @return  \Weblab\Pipelinedeals\Company|null            The Pipelinedeals company, null if there was no company for the given id
     */
    public function company($id) {
        return \Weblab\Pipelinedeals\Company::get($this, $id);
    }
    
    /**
     * Get the person for a given pipelinedeals id
     * 
     * @param   int                                          The Pipelinedeals person identifier
     * @return  \Weblab\Pipelinedeals\Person|null            The Pipelinedeals person, null if there was no person for the given id
     */
    public function person($id) {
        return \Weblab\Pipelinedeals\Person::get($this, $id);
    }

    /**
     * Get the user (account) for a given pipelinedeals id
     *
     * @param   int                                          The Pipelinedeals user (account) identifier
     * @return  \Weblab\Pipelinedeals\Person|null            The Pipelinedeals person, null if there was no person for the given id
     */
    public function user($id) {
        return \Weblab\Pipelinedeals\User::get($this, $id);
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
        $query['api_key'] = $this->token;
        
        // create the entire url and return it
        return $this->baseUrl . $path . '?' . http_build_query($query);
    }
    
    /**
     * Return true whenever it is possible to call the pipelinedeals api (10 calls
     * every 5 seconds are allowed)
     * 
     * @return boolean
     */
    private function readyForCall() {
        // get the total time
        $totalTime = $this->totalTime();
        
        // get whether it is possible to make a call (not possible if less than
        // 5 seconds have past and the number of calls above 10 already
        $canNotCall = ($totalTime < 5 && $this->totalCalls > 10);
        
        // if it is not possible to call, wait till we can call again
        if ($canNotCall) {
            sleep(5 - $totalTime);
        }

        // if the total time is 0 or the total time is over 5 seconds, or if it
        // was not possible to call before, reset the timer
        if ($totalTime == 0 || $totalTime > 5 || $canNotCall) {
            // reset the time
            $this->resetTimer();
        }
        
        // add 1 to the total number of calls made
        $this->totalCalls++;
        
        // done, return
        return true;
    }
    
    /**
     * Get the total run time since the start of the timer
     * 
     * @return float            The total time
     */
    private function totalTime() {
        // if the timer is not running, just return 0
        if ($this->totalTime == 0) {
            return $this->totalTime;
        }
        
        // the timer is running, return the total time
        return microtime(true) - $this->totalTime;
    }
    
    /**
     * Reset the timer and total number of calls made
     */
    private function resetTimer() {
        $this->totalTime = microtime(true);
        $this->totalCalls = 0;
    }
}
