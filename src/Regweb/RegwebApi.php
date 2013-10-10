<?php
namespace Regweb;

use Regweb\Authorization\AuthSchemeInterface;

use Regweb\Rest\RestRequest;

use Regweb\Rest\ResourceType\Member,
    Regweb\Rest\ResourceType\User;
use Regweb\Rest\Exceptions\UnexpectedResponse;
use Regweb\Rest\UpdateResult;

class RegwebApi {
	
	protected $regwebBaseUrl;
	protected $authHandler;
	
	public function __construct($regwebBaseUrl, AuthSchemeInterface $authorizationHandler) {
		$this->regwebBaseUrl = $regwebBaseUrl;
		$this->authHandler = $authorizationHandler;
	}
	
	/**
	 * Returns data about the currently logged in user
	 * 
	 * @return \Regweb\ResourceType\User
	 */
	public function getUser() {
		$request = new RestRequest($this->regwebBaseUrl . '/api/v1/user');
		$request->getParams['access_token'] = $this->authHandler->getAccessToken();
		$request->getParams['expand'] = 'member';
		$response = $request->execute();
		
		$user = new User();
		$user->username = $response->body['username'];
		$user->firstname = $response->body['firstname'];
		$user->lastname = $response->body['lastname'];
		$user->isMember = $response->body['is_member'];
		
		$member = new Member();
		$member->id = $response->body['member.id'];
		$member->firstname = $response->body['member.firstname'];
		$member->lastname = $response->body['member.lastname'];
		$member->address1 = $response->body['member.address1'];
		$member->address2 = $response->body['member.address2'];
		$member->postalcode = $response->body['member.postalcode'];
		$member->phone1 = $response->body['member.phone1'];
		$member->phone2 = $response->body['member.phone2'];
		$member->mobile = $response->body['member.mobile'];
		$member->email = $response->body['member.email'];
		
		$user->member = $member;
		
		return $user;
	}
	
	
	/**
	 * Returns data about a member identified by id
	 * 
	 * @param int $id
	 * @return \Regweb\ResourceType\Member
	 */
	public function getMember($id) {
		$request = new RestRequest($this->regwebBaseUrl . '/api/v1/members/' . $id);
		$request->getParams['access_token'] = $this->authHandler->getAccessToken();
		$response = $request->execute();
		
		$member = new Member();
		$member->id = $response->getValue['id'];
		$member->firstname 	= $response->getValue['firstname'];
		$member->lastname 	= $response->getValue['lastname'];
		$member->address1 	= $response->getValue['address1'];
		$member->address2 	= $response->getValue['address2'];
		$member->postalcode = $response->getValue['postalcode'];
		$member->phone1 	= $response->getValue['phone1'];
		$member->phone2 	= $response->getValue['phone2'];
		$member->mobile 	= $response->getValue['mobile'];
		$member->email 		= $response->getValue['email'];
		
		return $member;
	}
	
	/**
	 * 
	 * @param Member $member
	 */
	public function updateMember(Member $member) {
		$request = new RestRequest(	$this->regwebBaseUrl . '/api/v1/members/' . $member->id,
									RestRequest::POST);
		$request->postParams['access_token'] = $this->authHandler->getAccessToken();
		
		$request->postParams = array(
			'firstname' 	=> $member->firstname,
			'lastname' 		=> $member->lastname,
			'address1' 		=> $member->address1,
			'address2' 		=> $member->address2,
			'postalcode' 	=> $member->postalcode,
			'phone1' 		=> $member->phone1,
			'phone2' 		=> $member->phone2,
			'mobile' 		=> $member->mobile,
			'email' 		=> $member->email);
		
		$response = $request->execute();
		
		switch ($response->getStatusCode()) {
			case 200:
				return new UpdateResult(true);
				break;
			case 400:
				return new UpdateResult(false, $response->body['errors']);
				break;
			default:
				throw new UnexpectedResponse();
		}
	}
}