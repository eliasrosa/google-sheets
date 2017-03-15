<?php
namespace Eliasrosa;

use Google_Client;
use Google_Service_Sheets;

class GoogleClient 
{
	private $application_name = null;
	private $client_secret_path = null;
	private $token_path = null;
	private $token = null;
	private $scopes = [];

	//
	public function __construct()
	{
		$this->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
	}

	//
	public function setApplicationName($name)
	{
		$this->application_name = $name;
	}

	//
	public function setClientSecretPath($path)
	{
		$this->client_secret_path = $path;
	}

	//
	public function setTokenPath($path)
	{
		$this->token_path = $path;
	}

	//
	public function setToken($token)
	{
		$this->token = $token;
	}

	//
	public function setScopes($scopes)
	{
		$this->scopes = implode(' ', $scopes);
	}


    /**
    * Returns an authorized API client.
    * @return Google_Client the authorized client object
    */
    public function getClient() {
        $client = new Google_Client();
        $client->setAccessType('offline');
        $client->setScopes($this->scopes);
        $client->setAuthConfig($this->client_secret_path);
        $client->setApplicationName($this->application_name);

        if (file_exists($this->token_path)) {
            $access_token = json_decode(file_get_contents($this->token_path), true);
        } else {
        
	        // Request authorization from the user.
	        $auth_url = $client->createAuthUrl();
	        printf("Abra o link abaixo em seu navegador para gerar o TOKEN de acesso:\n%s\n", $auth_url);
	        
	        if(is_null($this->token)){
        		die();
        	}
		    
    	    // Exchange authorization code for an access token.
	        $access_token = $client->fetchAccessTokenWithAuthCode($this->token);

	        // Store the credentials to disk.
	        if(!file_exists(dirname($this->token_path))) {
	            mkdir(dirname($this->token_path), 0700, true);
	        }
	        
	        file_put_contents($this->token_path, json_encode($access_token));
        }
        
        $client->setAccessToken($access_token);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($this->token_path, json_encode($client->getAccessToken()));
        }
        
        //
        return $client;
    }

}