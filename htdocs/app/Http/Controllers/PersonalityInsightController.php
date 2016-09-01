<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class PersonalityInsightController extends Controller
{
    protected $config;
    /*******************************************************************************************************************
     *                                                  Construct
     */
    public function __construct()
    {
        $config = array();
        $config['AUTHURL'] = 'gateway.watsonplatform.net/authorization/api/v1/token';
        $config['WATSONURL'] = 'gateway.watsonplatform.net/personality-insights/api/v2/profile';

        //get concept insight parameters
        $services = json_decode(getenv('VCAP_SERVICES'), true);
        if($services)
        {
            // Bluemix Server Mode
            $data = $services['personality_insights'][0]['credentials'];
            $config['APIKEY'] = $data['username'];
            $config['APIPASSWORD'] = $data['password'];
        }
        else
        {
            // Local or Dedicated Server Mode
            $config['APIKEY'] = '8070f9ba-2b81-4bbd-859d-fa4577534adc';
            $config['APIPASSWORD'] = '6jsTGhILDWWv';
        }
        $this->config = $config;
    }
    /******************************************************************************************************************
     *                                       Call Personality Insight API
     * POST /pi
     */
    public function pi()
    {
        if (!empty($_POST)){
            try{
                $content = $_POST['text'];
                $token = $this->gettoken();
                $url ='https://'.$this->config['WATSONURL'].'?include_raw=false&headers=false&watson-token='.$token;
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: text/plain\r\n".
                            "Accept: application/json\r\n".
                            "Content-Language: en\r\n".
                            "Accept-Language: en",

                        'method'  => 'POST',
                        'content' => $content
                    ));

                //perform http
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
            }catch (\Exception $ex){
                $result = '{"error":"We require 70 matching words to calculate characteristics with any confidence."}';
            }
            return response($result, 200)->header('Content-Type', 'application/json');
        }else{
            return "Request Error";
        }
    }
    /******************************************************************************************************************
     *                                       Call Personality Insight API (debug)
     * GET /piTest
     */
    public function piTest()
    {
        $content = 'API Overview The Concept Insights service is composed of three inter-connected endpoints: Accounts endpoint: /accounts. The Accounts endpoint allows user to retrieve Concept Insights identification information. This identifier is system generated and used throughout the other APIs to allow users to create, and name, their own resources. Graph endpoint: /graphs. The Graph endpoint allows users to navigate and explore the Concept Insights knowledge graph. One can easily discover relationships between concepts as well as extract concept entities from user provided text. Corpora endpoint: /corpora. The Corpora endpoint allows users to upload their own set of documents into Concept Insights, while organizing them into collections. The raw documents are processed by our system, and automatically annotated and indexed. The analysis and indexing of documents becomes automatically accessible through query methods of this endpoint. A Note on IDs The Concept Insights service uses a variety of IDs to denote various entities used in the system. IDs, with the exception of account_id, are valid endpoint URIs (minus the https://gateway.watsonplatform.net/concept-insights/api/v2 prefix) and are composed in a structured fashion. In particular: A graph id is in the format: /graphs/{account_id}/{graph} A concept id is in the format: /graphs/{account_id}/{graph}/concepts/{concept} A corpus id is in the format: /corpora/{account_id}/{corpus} A document id is in the format: /corpora/{account_id}/{corpus}/documents/{document} When creating a corpus a user is allowed only to use the {account_id} returned by the /accounts endpoint. The {corpus} portion is specified by the corpus creator and can be any URL-encoded UTF-8 string (in particular "/" and "?" must be escaped). The {document} portion is specified by the document creator and can be any URL-encoded UTF-8 string (in particular "/" and "?" must be escaped).';
        $token = $this->gettoken();
        $url ='https://'.$this->config['WATSONURL'].'?include_raw=false&headers=false&watson-token='.$token;
        $options = array(
            'http' => array(
                'header'  => "Content-type: text/plain\r\n".
                    "Accept: application/json\r\n".
                    "Accept-Language: en",
                'method'  => 'POST',
                'content' => $content
            ));

        //perform http
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return response($result, 200)->header('Content-Type', 'application/json');
    }
    /******************************************************************************************************************
     *                                              Get Api Token
     * @return string
     */
    public function gettoken()
    {
        $AUTHURL = $this->config['AUTHURL'];
        $WATSONURL = $this->config['WATSONURL'];
        $APIKEY = $this->config['APIKEY'];
        $APIPASSWORD = $this->config['APIPASSWORD'];

        //Prepare details to request token
        $PIAUTHURL = 'https://'.$AUTHURL.'?url=https://'.$WATSONURL;
        $creds = $APIKEY.':'.$APIPASSWORD;

        //Create HTTP GET Request
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header' => "Authorization: Basic " . base64_encode($creds)
            )
        );

        //Request
        $context = stream_context_create($opts);
        //Get token
        $token = file_get_contents($PIAUTHURL, false, $context);

        return $token;
    }

}
