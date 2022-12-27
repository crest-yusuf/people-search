<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SearchPeopleService;
use DOMDocument;
use DOMXPath;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserSearchController extends Controller
{

    protected $searchPeopleServiceObject;
    protected $perPage = 20;

    function __construct(SearchPeopleService $searchPeopleService) {
        $this->searchPeopleServiceObject = $searchPeopleService;
    }

    function requestPage() {
        try{
            $response2 = $this->searchPeopleServiceObject->getSearchResult();
            $doc = new DOMDocument();
            $doc->loadHtml($response2, LIBXML_NOERROR);
            
            $xpath = new DOMXPath($doc);
            
            $labels = ['Name','Age','Phone Numbers','Releted People','Location'];
            
            $data = $this->getRows($xpath);
    
            $formatArray = $this->getArrayFormat($data);
            $totalRecords = $this->getTotalrecords($xpath);
     
            $response['titles'] = $labels;
            $response['list'] = $formatArray;
            $response['total'] = $totalRecords;
                        
            return response()->json([
                'data' => $response,
                'message' => 'Search List', 'code' => 200]);
    
        } catch(Exception $e) {
            Log::info('Search Result Error'.$e->getMessage());
            return response()->json([
                'data' => null,
                'msg' => 'Something wnet wrong',
                'code' => 500
            ]);
        }
    }

    function search() {
    try {

        $request = request()->all(); 
        $requestData = "firstName=".$request['firstName']."&lastName=".$request['lastName']."&city=".$request['city']."&state=".$request['state'];
        
        // Search Result By Value
        $this->searchPeopleServiceObject->search($requestData);
    
        // get Result Response
        $response2 = $this->searchPeopleServiceObject->getSearchResult();
        $doc = new DOMDocument();
        $doc->loadHtml($response2, LIBXML_NOERROR);
        
        $xpath = new DOMXPath($doc);
        
        $labels = ['Name','Age','Phone Numbers','Releted People','Location'];
        
        $data = $this->getRows($xpath);

        $formatArray = $this->getArrayFormat($data);
        $totalRecords = $this->getTotalrecords($xpath);
 
        $response['titles'] = $labels;
        $response['list'] = $formatArray;
        $response['total'] = $totalRecords;
        
        return response()->json([
            'data' => $response,
            'message' => 'Search List', 'code' => 200]);

    } catch(Exception $e) {
        Log::info('Search Result Error'.$e->getMessage());
        return response()->json([
            'data' => null,
            'msg' => 'Something wnet wrong',
            'code' => 500
        ]);
    }
    }

    protected function getTotalrecords ($xpath) {
        $queryTotal = '//div[@class="results-header-wrap"]//div//div//div//h1[@class="text-4xl"]';
        $totalFound = $xpath->query($queryTotal);
        $totalFoundString = $totalFound->item(0)->nodeValue;
        $totalRecords = (int)filter_var($totalFoundString, FILTER_SANITIZE_NUMBER_INT);
        return $totalRecords;
    }

    protected function getArrayFormat($data) {
        $formatArray = [];
        foreach($data['Name'] as $key => $value) {
            $formatArray[] = [
                'Name' => $value,
                'Age' => $data['Age'][$key],
                'phone_numbers' => $data['phone_numbers'][$key],
                'releted_people' => $data['releted_people'][$key],
                'Location' => $data['Location'][$key]
            ];
        }
        return $formatArray;
    }

    protected function getRows($xpath) {
        $query = '//ul[@class="list-result"]//li[@class="result-list-item"]//div[@class="item-wrapper"]';        
        $entries = $xpath->query($query);
        $data = [];
        $titlesArray = ['Name','Age','phone_numbers','releted_people','Location'];
        foreach ($entries as $key => $entry) {
            $title =  $xpath->evaluate('string(div[@class="result-label"])', $entry);
            $info =  $xpath->evaluate('string(div[@class="result-info"])', $entry);
            
            if(in_array($title,['Name','Age']))
                $info  = $xpath->evaluate('string(div[@class="result-info bold"])', $entry);    
    
            if($title == 'Phone Numbers') {
                $title = 'phone_numbers';
                $info = $xpath->evaluate('string(div[@class="result-info"]//span)', $entry);    
            }
    
            if(in_array($title,['Releted People','Location'])) {
                $title = $title == 'Releted People' ? 'releted_people' : $title; 
                $subArray = $xpath->query('div[@class="result-info"]/span', $entry);
                $subInfoArray = [];
                foreach($subArray as $subInfo)
                {
                    $subInfoArray[] = $subInfo->textContent;
                }    
                $info = implode(", ", $subInfoArray);
            }
    
            if(in_array($title, $titlesArray)){
                $data[$title][] = $info;
            }     
        }

        return $data;
    }
}
