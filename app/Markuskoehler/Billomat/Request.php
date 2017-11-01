<?php
namespace App\Markuskoehler\Billomat;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Request
{
    protected $api_url;
    protected $api_key;
    protected $api_id;
    protected $api_secret;
    protected $client;

    public function __construct() {
        $this->api_url = 'https://' . env('BILLOMAT_APP_ID') . '.billomat.net/api/';
        $this->api_key = env('BILLOMAT_API_KEY');
        $this->api_id = env('BILLOMAT_API_ID');
        $this->api_secret = env('BILLOMAT_API_SECRET');

        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-BillomatApiKey' => $this->api_key,
                'X-AppId' => $this->api_id,
                'X-AppSecret' => $this->api_secret
            ]
        ]);
    }

    /**
     * @param string $endpoint The endpoint to be queried
     * @param int|null $id Retrieve single entity or not (null)
     * @param array $filters Filter results
     * @param array $groups Group results
     * @param array $uriAdditions Additional URI parts like /pdf
     * @return mixed
     */
    public function get($endpoint, $id = null, array $filters = [], array $groups = [], array $uriAdditions = []) {
        try {
            $url = $this->api_url . $endpoint;
            if(!is_null($id)) $url .= '/' . $id;
            if(!empty($uriAdditions)) $url .= '/' . implode('/', $uriAdditions);
            if(!empty($filters)) {
                $prefix = strpos($url, '?') === false ? '?' : '&';
                $url .= $prefix . implode('&', array_map(
                        function ($v, $k) { return sprintf("%s=%s", $k, $v); },
                        $filters,
                        array_keys($filters)
                    ));
            }
            if(!empty($groups)) {
                $prefix = strpos($url, '?') === false ? '?' : '&';
                $url .= $prefix . 'group_by=' . implode($groups, ',');
            }
            //dd($url);

            $result = $this->client->request('GET', $url);
        } catch(ClientException $ex) {
            return json_decode($ex->getResponse()->getBody())->errors;
        }
        return json_decode((string) $result->getBody());
        // see also: https://stackoverflow.com/a/41206252/1557027
        //return json_decode((string) $result->getBody())->pdf->base64file; // if returned pdf directly
    }
}