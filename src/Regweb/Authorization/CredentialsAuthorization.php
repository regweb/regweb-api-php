<?php
namespace Regweb\Authorization;

use Regweb\Rest\RestRequest,
    Regweb\Rest\Exceptions\BadRequest,
    Regweb\Rest\Exceptions\Unauthorized,
    Regweb\Rest\Exceptions\Forbidden,
    Regweb\Rest\Exceptions\UnexpectedResponse;
use Regweb\Logger\Logger;
use Regweb\Rest\MetaData;

class CredentialsAuthorization implements AuthSchemeInterface {
	
	protected $regwebBaseUrl;
	protected $clientId;
	protected $clientSecret;
	protected $session;
	protected $meta;
	protected $logger;
	
	function __construct($regwebBaseUrl, $clientId, $clientSecret, AuthSessionInterface $session, MetaData $meta, Logger $logger) {
		$regwebBaseUrl = rtrim($regwebBaseUrl, '/');
		
		$this->meta = $meta;
		$this->regwebBaseUrl = $regwebBaseUrl.'/api/v1/';
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->session = $session;
		$this->logger = $logger;
	}
	
	public function createRequest($apiUrl, $urlParams = null, $method = RestRequest::GET) {
		return new RestRequest($this->regwebBaseUrl, $apiUrl, $urlParams, $method, $this->meta, $this->logger);
	}
	
	/**
	 * 
	 * @param string $username
	 * @param string $password
	 * @throws UnexpectedResponse
	 * @return LoginResult
	 */
	public function authorizeCredentials($username, $password) {
		$request = $this->createRequest('oauth2/token', null, RestRequest::POST);
		
		$request->postParams = array(
			'grant_type' 	=> 'password',
			'client_id' 	=> $this->clientId,
			'client_secret' => $this->clientSecret,
			'username' 		=> $username,
			'password' 		=> $password);
		
		try {
			$response = $request->execute();
			
			switch ($response->statusCode) {
				case 200:
					// Success
					$this->session->setValues(array(
						'access_token' 	=> $response->body['access_token'],
						'refresh_token' => $response->body['refresh_token'],
						'refresh_at' 	=> time() + $response->body['expires_in'] - 5));
					
					return new LoginResult(true, false, false, false);
					break;
				default:
					throw new UnexpectedResponse(	'unexpected_response',
						'Status code not expected.',
						$response,
						array('status_code' => $response->statusCode));
			}
		} catch (Unauthorized $e) {
			return new LoginResult(	false,
									false,
									($e->response->body['member_active_check_failed']) ? true : false,
									($e->response->body['unique_email_check_failed']) ? true : false);
		} catch (BadRequest $e) {
			return new LoginResult(false, true, false, false);
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
		$request = $this->createRequest('oauth2/token', null, RestRequest::POST);
		
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