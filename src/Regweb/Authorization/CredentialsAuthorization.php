<?php
namespace Regweb\Authorization;

use Regweb\Rest\RestRequest,
    Regweb\Rest\Exceptions\BadRequest,
    Regweb\Rest\Exceptions\Unauthorized,
    Regweb\Rest\Exceptions\Forbidden,
    Regweb\Rest\Exceptions\UnexpectedResponse;

class CredentialsAuthorization implements AuthSchemeInterface {
	
	protected $regwebBaseUrl;
	protected $clientId;
	protected $clientSecret;
	protected $session;
	
	function __construct($regwebBaseUrl, $clientId, $clientSecret, AuthSessionInterface $session) {
		$this->regwebBaseUrl = $regwebBaseUrl;
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->session = $session;
	}
	
	public function authorizeCredentials($username, $password) {
		$request = new RestRequest($this->regwebBaseUrl . '/api/v1/oauth2/token', RestRequest::POST);
		
		$request->postParams = array(
			'grant_type' 	=> 'password',
			'client_id' 	=> $this->clientId,
			'client_secret' => $this->clientSecret,
			'username' 		=> $username,
			'password' 		=> $password);
		
		$response = $request->execute();
		
		switch ($response->statusCode) {
			case 200:
				// Success
				$this->session->setValues(array(
					'access_token' 	=> $response->body['access_token'],
					'refresh_token' => $response->body['refresh_token'],
					'refresh_at' 	=> time() + $response->body['expires_in'] - 5));
				
				return true;
				break;
			default:
				throw new UnexpectedResponse(	'unexpected_response',
												'Status code not expected.',
												$response,
												array('status_code' => $response->statusCode));
		}
	}
	
	public function getAccessToken() {
		if (!$this->session->hasValue('access_token') || $this->session->getValue('access_token') == '') {
			throw new Unauthorized();
		}
		$this->handleRefreshing();
		return $this->session->getValue('access_token');
	}
	
	public function handleRefreshing() {
		if (time() > $this->session->getValue('refresh_at')) {
			$this->refreshAccessToken();
		}
	}
	
	public function refreshAccessToken() {
		$request = new RestRequest($this->regwebBaseUrl . '/api/v1/oauth2/token', RestRequest::POST);
		
		$request->postParams = array(
			'grant_type' 	=> 'refresh_token',
			'refresh_token' => $this->session->getValue('refresh_token'),
			'client_id' 	=> $this->clientId,
			'client_secret' => $this->clientSecret);
		
		$response = $request->execute();
		
		switch ($response->statusCode) {
			case 200:
				// Success
				$this->session->setValues(array(
					'access_token' 	=> $response->body['access_token'],
					'refresh_at' 	=> time() + $response->body['expires_in'] - 5));
				
				// The server may or may not send a new refresh token
				if (isset($response->body['refresh_token']) && $response->body['refresh_token']  != '') {
					$this->session->setValue('refresh_token', $response->body['refresh_token']);
				}
				
				return true;
				break;
			default:
				throw new UnexpectedResponse(	'unexpected_response',
												'Status code not expected.',
												array('status_code' => $response->statusCode));
		}
	}
	
	public function logout() {
		$this->session->unsetKey('access_token');
		$this->session->unsetKey('refresh_token');
		$this->session->unsetKey('refresh_at');
	}
}