<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SearchPeopleService;
use App\Http\Traits\HttpResponse;
use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Support\Facades\Log;

class UserSearchController extends Controller
{

    use HttpResponse;

    protected $searchPeopleServiceObject;
    protected $perPage = 20;
    const HTTP_POST = 'POST';

    function __construct(SearchPeopleService $searchPeopleService) {
        $this->searchPeopleServiceObject = $searchPeopleService;
    }

    function search() {
    try {
        
        // call curl for Requested Search 
        if(request()->getMethod() == self::HTTP_POST) {
            $request = request()->all(); 
            $requestData = "firstName=".$request['firstName']."&lastName=".$request['lastName']."&city=".$request['city']."&state=".$request['state'];
            
            $this->searchPeopleServiceObject->search($requestData);
        }
    
        // get Result Response and also same for pagination
        $response2 = $this->searchPeopleServiceObject->getSearchResult();

        $doc = new DOMDocument();
        $doc->loadHtml($response2, LIBXML_NOERROR); // Load Target Html
        
        $xpath = new DOMXPath($doc);
        
        $labels = ['Name','Age','Phone','Releted People','Location'];
        
        $rows = $this->getRows($xpath); // Get rows from HTML content
        $formatArray = $this->getArrayFormat($rows); // Prepare Array For Response 
        $totalRecords = $this->getTotalrecords($xpath); // Get Total 
 
        $response = [
            'titles' => $labels,
            'list' => $formatArray,
            'total' => $totalRecords
        ];
        
        return $this->success($response, 'Search List', 200);

    } catch(Exception $e) {
        Log::info('Search Result Error'.$e->getMessage());
        return $this->error('Something went wrong', 500);
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
                'Location' => $data['Location'][$key],
                'readableLocation' => $data['readableLocation'][$key]
            ];
        }
        return $formatArray;
    }

    protected function getRows($xpath) {
        $query = '//ul[@class="list-result"]//li[@class="result-list-item"]//div[@class="item-wrapper"]';        
        $locationsQuery = '//ul[@class="list-result"]//li[@class="result-list-item"]';
        $locations = $xpath->query($locationsQuery);
        
        $fullLocation = [];
        foreach ($locations as $key => $location) {
            $fullLocation[] = $location->getAttribute('data-location');
        }

        $entries = $xpath->query($query);
        $data = [];
        $titlesArray = ['Name','Age','phone_numbers','releted_people','Location'];
        foreach ($entries as $key => $entry) {

            $title =  $xpath->evaluate('string(div[@class="result-label"])', $entry);
            $info =  $xpath->evaluate('string(div[@class="result-info"])', $entry);
            
            // dd($entry->parentNode->getAttribute('data-location'));
            if(in_array($title,['Name','Age'])){
                $info  = $xpath->evaluate('string(div[@class="result-info bold"])', $entry);
            }
    
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
        $data['readableLocation'] = $fullLocation;
        return $data;
    }
}
