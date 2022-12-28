<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchPeopleService
{

    protected $baseUrl;

    public function __construct() {
        $this->baseUrl = config('constant.people_search.base_url');
    }

    // Call for Requested Data 
    function search($requestData) {
        
        // Prepare Url and token
        $url = $this->baseUrl.config('constant.people_search.slugs.search');
        $token = config('constant.people_search.tokens.search'); 

        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $requestData,
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: XSRF-TOKEN='.$token
                ),
        ));

        curl_exec($curl);
        curl_close($curl);
    }

    // Call For get Data
    function getSearchResult() {

        // Prepare Url and token
        $url = $this->baseUrl.config('constant.people_search.slugs.search-result');
        $url .=  request()->has('page') ? '?page='.request()->page : '' ;
        $token = config('constant.people_search.tokens.result');

        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: XSRF-TOKEN='.$token
            ),
        ));
    
        $response2 = curl_exec($curl2);
    
        curl_close($curl2);
        return $response2;
    }

}