<?php

namespace App\Http\Controllers;

use App\Candidate;
use Illuminate\Http\Request;
use App\Http\Requests;
use Http\Adapter\Guzzle6\Client;
use Happyr\LinkedIn\LinkedIn;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Support\Facades\App;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\DomCrawler\Crawler;

class MainController extends Controller
{
    private $config;

    public function __construct()
    {
        $config = array();

        $config['LINKEDIN_APPKEY'] = '75ufdx6yj8nfmj';
        $config['LINKEDIN_APPSECRET'] ='wDlkhSvCNld7c8Ya';

        if( isset($_SERVER['LINKEDIN_APPKEY']) &&
            isset($_SERVER['LINKEDIN_APPSECRET']) )
        {
            if( $_SERVER['LINKEDIN_APPKEY']    != "ENTER VALUE" &&
                $_SERVER['LINKEDIN_APPSECRET'] != "ENTER VALUE" )
            {
                $config['LINKEDIN_APPKEY']    = $_SERVER['LINKEDIN_APPKEY'];
                $config['LINKEDIN_APPSECRET'] = $_SERVER['LINKEDIN_APPSECRET'];
            }

        }

        $this->config = $config;
    }
    /*******************************************************************************************************************
     *                                                LinkedIn Login
     * @return array
     */
    public function auth(Request $request)
    {
        $linkedIn=new LinkedIn(
            $this->config['LINKEDIN_APPKEY'],
            $this->config['LINKEDIN_APPSECRET']
        );
        $client =  Client::createWithConfig(array(
            'timeout' => 60,
            'verify' => false
        ));
        $linkedIn->setHttpClient($client);
        $linkedIn->setHttpMessageFactory(new GuzzleMessageFactory());
        if ($linkedIn->isAuthenticated()) {
            $field = "id,formatted-name,headline,location,industry,summary,specialties,positions,picture-url,public-profile-url,email-address";
            $data=$linkedIn->get("v1/people/~:($field)");
            $user = $this->transformProfile($data);
//------------------------------- for debug
//            var_dump($linkedIn);
//            $url = "https://www.linkedin.com/in/wang-xiaotian-996baa11a";
//            $req = new \GuzzleHttp\Psr7\Request('GET', $url);
//            $fulldata = $client->sendRequest($req);
//            var_dump($fulldata->getBody()->read(10000));
//-------------------------------
            // get user full profile
            try{
                $profileHtml = $this->getHtmlFromURL($user['publicProfileUrl']);
                $profile = $this->extractData($profileHtml);
                $user['data'] .= $profile;
            }catch (\Exception $ex){}

            // save user to candidate table
            try{
                $candidate = Candidate::where('_id',$user['id'])->first();
                if(count($candidate)==0){
                    $candidate = new Candidate();
                    $candidate->_id = $user['id'];
                }
                $candidate->name = $user['fullName'];
                $candidate->text = $user['data'];
                $candidate->profile = $user['data'];
                $candidate->save();
            }catch (\Exception $ex){}

            $request->session()->put('user', $user);
            $request->session()->save();

            header('Location: /jobsearch');
            exit();
        } elseif ($linkedIn->hasError()) {
            echo "User canceled the login.";
        }else{
            $url = $linkedIn->getLoginUrl();
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
            exit();
        }
    }
    /*******************************************************************************************************************
     *                                                  Api Parse
     * @return array
     */
    public function parse(Request $request)
    {
        $user = $this->cleanTextProfile($_POST);
        $request->session()->put('user', $user);
        $request->session()->save();
        return $this->response_json($user);
    }
    /*******************************************************************************************************************
     *                                                 Web jobsearch
     */
    public function jobSearch(Request $request){
        $this->clearSession();
        $user = $request->session()->get('user');
        return view('user-dashboard',['user'=>$user]);
    }

    /*******************************************************************************************************************
     *                                               Clean Text Profile
     * @param $data
     * @return array
     */
    function cleanTextProfile($data) {
        $profile = array();
        $profile['id']               = isset($data['id'])?$data['id']:'';
        $profile['fullName']         = isset($data['name'])?$data['name']:'';
        $profile['pictureUrl']       = isset($data['pictureUrl']) ? $data['pictureUrl'] : '/images/user.png';
        $profile['publicProfileUrl'] = isset($data['publicProfileUrl'])?$data['publicProfileUrl']:'';
        $profile['emailAddress']     = isset($data['emailAddress'])?$data['emailAddress']:'';
        $profile['headline']         = '';
        $profile['data']             = isset($data['text'])?$data['text']:'';

        $profile['data'] = $this->clearText($profile['data']);

        return $profile;
    }

    /*******************************************************************************************************************
     *                                              Clear Text
     * @param $text
     * @return mixed
     */
    function clearText($text){
        $cleanText = $text;
        $cleanText = preg_replace('/(\n)/', ' ', $cleanText);
        $cleanText = preg_replace('/\s(\s)+/', ' ', $cleanText);
        $cleanText = preg_replace('/\,\s(\,\s)+/', ', ', $cleanText);
        $cleanText = preg_replace('/\.\s(\.\s)+/', '. ', $cleanText);
        $cleanText = preg_replace('/\.\,/', '.', $cleanText);
        $cleanText = preg_replace('/\,\./', '.', $cleanText);
        $cleanText = preg_replace('/\,\s\.\s/', '. ', $cleanText);
        $cleanText = preg_replace('/\\\/', '', $cleanText);
        return $cleanText;
    }

    /*******************************************************************************************************************
     *                                              Transform Profile
     * @param $data
     * @return mixed
     */
    function transformProfile($data) {
        $profile = array();
        $profile['id']               = isset($data['id'])?$data['id']:'';
        $profile['fullName']         = isset($data['formattedName'])?$data['formattedName']:'';
        $profile['headline']         = $data['headline'];
        $profile['pictureUrl']       = isset($data['pictureUrl']) ? $data['pictureUrl'] : '/images/user.png';
        $profile['publicProfileUrl'] = isset($data['publicProfileUrl'])?$data['publicProfileUrl']:'';
        $profile['emailAddress']     = isset($data['emailAddress'])?$data['emailAddress']:'';
        $profile['data']             = isset($data['summary'])?$data['summary']:'';

        $profile['data'] .= isset($data['associations'])?$data['associations']:'';
        $profile['data'] .= ". ";

        if (isset($data['certifications'])) {
            for ($i = 0; $i < ($data['certifications']['_total']); $i++) {
                $value = $data['certifications']['values'][$i];
                $profile['data'] .= (isset($value['name'])?$value['name'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }
        if (isset($data['courses'])) {
            for ($i = 0; $i < ($data['courses']['_total']); $i++) {
                $value = $data['courses']['values'][$i];
                $profile['data'] .= (isset($value['name'])?$value['name'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        if (isset($data['educations'])) {
            for ($i = 0; $i < ($data['educations']['_total']); $i++) {
                $value = $data['educations']['values'][$i];
                $profile['data'] .= (isset($value['fieldOfStudy'])?$value['fieldOfStudy'] : "") . ", ";
                $profile['data'] .= (isset($value['degree'])?$value['degree'] : "") . ", ";
                $profile['data'] .= (isset($value['notes'])?$value['notes'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        if (isset($data['honorsAwards'])) {
            for ($i = 0; $i < ($data['honorsAwards']['_total']); $i++) {
                $value = $data['skills']['values'][$i];
                $profile['data'] .= (isset($value['name'])?$value['name'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        $profile['data'] .= (isset($data['industry']) ?$data['industry'] : "") . ", ";
        $profile['data'] .= (isset($data['interests'])?$data['interests'] : "") . ", ";

        if (isset($data['languages'])) {
            for ($i = 0; $i < ($data['languages']['_total']); $i++) {
                $value = $data['languages']['values'][$i]['language'];
                $profile['data'] .= (isset($value['name'])?$value['name'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        $profile['data'] .= (isset($data['location']['name'])?$data['location']['name'] : "") . ", ";

        if (isset($data['positions'])) {
            for ($i = 0; $i < ($data['positions']['_total']); $i++) {
                $value = $data['positions']['values'][$i];
                $profile['data'] .= (isset($value['title'])?$value['title'] : "") . ", ";
                $profile['data'] .= (isset($value['summary'])?$value['summary'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        if (isset($data['projects'])) {
            for ($i = 0; $i < ($data['projects']['_total']); $i++) {
                $value = $data['projects']['values'][$i];
                $profile['data'] .= (isset($value['name'])?$value['name'] : "") . ", ";
                $profile['data'] .= (isset($value['description'])?$value['description'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        if (isset($data['publications'])) {
            for ($i = 0; $i < ($data['publications']['_total']); $i++) {
                $value = $data['publications']['values'][$i];
                $profile['data'] .= (isset($value['title'])?$value['title'] : "") . ", ";
                $profile['data'] .= (isset($value['summary'])?$value['summary'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        if (isset($data['patents'])) {
            for ($i = 0; $i < ($data['patents']['_total']); $i++) {
                $value = $data['patents']['values'][$i];
                $profile['data'] .= (isset($value['title'])?$value['title'] : "") . ", ";
                $profile['data'] .= (isset($value['summary'])?$value['summary'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        if (isset($data['recommendationsReceived'])) {
            for ($i = 0; $i < ($data['recommendationsReceived']['_total']); $i++) {
                $value = $data['recommendationsReceived']['values'][$i];
                $profile['data'] .= (isset($value['recommendationText'])?$value['recommendationText'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        if (isset($data['skills'])) {
            for ($i = 0; $i < ($data['skills']['_total']); $i++) {
                $value = $data['skills']['values'][$i]['skill'];
                $profile['data'] .= (isset($value['name'])?$value['name'] : "") . ", ";
            }
            $profile['data'] .= ". ";
        }

        $profile['data'] .= (isset($data['specialties'])?$data['specialties'] : "") . ", ";

        $profile['data'] = $this->clearText($profile['data']);

        return $profile;
    }

    /*******************************************************************************************************************
     *                         Extrate Profile Data from LinkedIn Full Profile HTML
     * @param $url
     * @return string
     */
    private function extractData($html)
    {
        $filter = new Crawler($html);

        $summary = "";
        $experience = "";

        if(count($obj = $filter->filter('.summary .description')))
        {
            $summary .= $obj->text();
        }

        if(count($obj = $filter->filter('#background-experience .editable-item')))
        {
            $result = $obj->each(function (Crawler $node) {
                return $node->text();
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#languages-view ol')))
        {
            $result = $obj->each(function (Crawler $node) {
                return $node->text();
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#profile-skills ul')))
        {
            $result = $obj->each(function (Crawler $node) {
                $result = $node->filter("a")->each(function(Crawler $node){
                    return $node->text();
                });
                return implode(' ', $result);
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#background-education .editable-item')))
        {
            $result = $obj->each(function (Crawler $node) {
                $result = $node->filter("header, h4, h5, p")->each(function(Crawler $node){
                    return $node->text();
                });
                return implode(' ', $result);
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#background-certifications .editable-item')))
        {
            $result = $obj->each(function (Crawler $node) {
                $result = $node->filter("h4, p")->each(function(Crawler $node){
                    return $node->text();
                });
                return implode(' ', $result);
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#background-projects .editable-item')))
        {
            $result = $obj->each(function (Crawler $node) {
                $result = $node->filter("hgroup, p")->each(function(Crawler $node){
                    return $node->text();
                });
                return implode(' ', $result);
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#background-publications .editable-item')))
        {
            $result = $obj->each(function (Crawler $node) {
                $result = $node->filter("hgroup, p")->each(function(Crawler $node){
                    return $node->text();
                });
                return implode(' ', $result);
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#background-courses .editable-item')))
        {
            $result = $obj->each(function (Crawler $node) {
                $result = $node->filter("h4, ul")->each(function(Crawler $node){
                    return $node->text();
                });
                return implode(' ', $result);
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#background-interests .editable-item')))
        {
            $result = $obj->each(function (Crawler $node) {
                return $node->text();
            });
            $experience .= implode(" ",$result);
        }

        if(count($obj = $filter->filter('#background-patents .editable-item')))
        {
            $result = $obj->each(function (Crawler $node) {
                $result = $node->filter("hgroup, h4, p")->each(function(Crawler $node){
                    return $node->text();
                });
                return implode(' ', $result);
            });
            $experience .= implode(" ",$result);
        }
        return $experience;

    }
    /*******************************************************************************************************************
     *                                          Get Html From Profile URL
     * @param $url
     * @return string
     */
    private function getHtmlFromURL($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");
        $headers = array();
        $headers[] = 'cookie: li_at=AQEDAR24lwIBx2n7AAABVNMntJMAAAFU05WRk0sAGFXhRjWXXDY6V8H999In5pvQCuwrThGvaa2Goxcae30Sfdy6dxq_8fi5MICpR0agvz_-sY6j6Nwb13S9VqyY-UsWZcvs2tO5eYiJBLdfzCLL3k8C;';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);
        if(curl_errno($ch))
        {
            echo 'Curl error: '.curl_error($ch)."\n";
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        return $output;
    }
    /*******************************************************************************************************************
     *                                              Get Html From File
     * @param $url
     * @return string
     */
    private function getHtmlFromFile($file){
        return file_get_contents($file);
    }

    /*******************************************************************************************************************
     *                                            Parse Test from file
     * GET /test_parse
     *
     * @param $url
     * @return string
     */
    public function testParse(){
        $html = $this->getHtmlFromFile(__DIR__.'/profile.html'); // for test
        return $this->extractData($html);
    }
    public function testEnv(){
        var_dump($_SERVER);
    }
}
