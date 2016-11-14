<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PhotoController  extends Controller
{

    public function index()
    {
        try {
            $apiKey = 'cd51c35deb0b194c8c3ccbf6e18954c5';
            $method = 'flickr.photos.getRecent';
            $url = "https://api.flickr.com/services/rest/?method=".$method."&api_key=".$apiKey;

            $new = curl_init();
            curl_setopt($new, CURLOPT_URL, $url);
            curl_setopt($new, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($new, CURLOPT_HEADER, true);
//            curl_setopt($new,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

            $result =$this->namespacedXMLToArray(curl_exec($new));
//            dd($result);
            curl_close($new);

        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'photos' => $result
        ]);
    }
    public function photo(Request $request){
    }
    public function  getBySize(){
    }
    function namespacedXMLToArray($xml)
    {
        function removeNamespaceFromXML( $xml )
        {
            // Because I know all of the the namespaces that will possibly appear in
            // in the XML string I can just hard code them and check for
            // them to remove them
            $toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
            // This is part of a regex I will use to remove the namespace declaration from string
            $nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';

            // Cycle through each namespace and remove it from the XML string
            foreach( $toRemove as $remove ) {
                // First remove the namespace from the opening of the tag
                $xml = str_replace('<' . $remove . ':', '<', $xml);
                // Now remove the namespace from the closing of the tag
                $xml = str_replace('</' . $remove . ':', '</', $xml);
                // This XML uses the name space with CommentText, so remove that too
                $xml = str_replace($remove . ':commentText', 'commentText', $xml);
                // Complete the pattern for RegEx to remove this namespace declaration
                $pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
                // Remove the actual namespace declaration using the Pattern
                $xml = preg_replace($pattern, '', $xml, 1);
            }

            // Return sanitized and cleaned up XML with no namespaces
            return $xml;
        }
        // One function to both clean the XML string and return an array
        return json_decode(json_encode(simplexml_load_string(removeNamespaceFromXML($xml))), true);
    }
}
