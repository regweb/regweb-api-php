<?php
namespace Regweb;

use Regweb\Authorization\AuthSchemeInterface;

use Regweb\Rest\RestRequest;

use Regweb\Rest\ResourceType\Member as MemberResource,
    Regweb\Rest\ResourceType\User as UserResource;
use Regweb\Rest\Exceptions\UnexpectedResponse;
use Regweb\Rest\UpdateResult;
use Regweb\Rest\ResourceType\OptionalSelectValues as OptionalSelectValuesResource;
use Regweb\Rest\ResourceType\OptionalSelectValue as OptionalSelectValueResource;
use Regweb\Logger\Logger;
use Regweb\Logger\RequestLogger;
use Regweb\Logger\ResponseLogger;
use Regweb\Rest\Exceptions\Unauthorized;
use Regweb\Rest\MetaData;

class RegwebApi {
	
	protected $meta;
	protected $regwebBaseUrl;
	protected $authHandler;
	protected $logger;
	
	public function __construct($regwebBaseUrl, AuthSchemeInterface $authorizationHandler, MetaData $meta, Logger $logger) {
		$regwebBaseUrl = rtrim($regwebBaseUrl, '/');
		
		$this->meta = $meta;
		$this->regwebBaseUrl = $regwebBaseUrl.'/api/v1/';
		$this->authHandler = $authorizationHandler;
		$this->logger = $logger;
	}
	
	public function isLoggedIn() {
		try {
			$this->authHandler->getAccessToken();
		} catch (Unauthorized $e) {
			return false;
		}
		return true;
	}
	
	public function createRequest($apiUrl, $urlParams = null, $method = RestRequest::GET) {
		return new RestRequest($this->regwebBaseUrl, $apiUrl, $urlParams, $method, $this->meta, $this->logger);
	}
	
	/**
	 * Returns data about the currently logged in user
	 * 
	 * @return \Regweb\ResourceType\User
	 */
	public function getUser() {
		$request = $this->createRequest('user');
		
		$request->getParams['access_token'] = $this->authHandler->getAccessToken();
		$request->getParams['expand'] = 'member';
		
		$response = $request->execute();
		
		$user = new UserResource();
		$user->username = $response->body['username'];
		$user->firstname = $response->body['firstname'];
		$user->lastname = $response->body['lastname'];
		$user->isMember = $response->body['is_member'];
		$user->email = $response->body['email'];
		
		if ($user->isMember) {
			// Assume expanded data
			$memberData = $response->body['member'];
			$member = new MemberResource();
			$member->id 		= $memberData['id'];
			$member->firstname 	= $memberData['firstname'];
			$member->lastname 	= $memberData['lastname'];
			$member->address1 	= $memberData['address1'];
			$member->address2 	= $memberData['address2'];
			$member->postalcode = $memberData['postalcode'];
			$member->phone1 	= $memberData['phone1'];
			$member->phone2 	= $memberData['phone2'];
			$member->mobile 	= $memberData['mobile'];
			$member->email 		= $memberData['email'];
			
			$member->optionalTextfield1 = $memberData['optional_textfield1'];
			$member->optionalTextfield2 = $memberData['optional_textfield2'];
			$member->optionalTextfield3 = $memberData['optional_textfield3'];
			$member->optionalTextfield4 = $memberData['optional_textfield4'];
			$member->optionalTextfield5 = $memberData['optional_textfield5'];
			$member->optionalTextfield6 = $memberData['optional_textfield6'];
			
			$member->optionalSelect1 = $memberData['optional_select1'];
			$member->optionalSelect2 = $memberData['optional_select2'];
			$member->optionalSelect3 = $memberData['optional_select3'];
			$member->optionalSelect4 = $memberData['optional_select4'];
			
			$member->optionalSelect1Label = $memberData['optional_select1_label'];
			$member->optionalSelect2Label = $memberData['optional_select2_label'];
			$member->optionalSelect3Label = $memberData['optional_select3_label'];
			$member->optionalSelect4Label = $memberData['optional_select4_label'];
			
			$member->optionalDate1 = $memberData['optional_date1'];
			$member->optionalDate2 = $memberData['optional_date2'];
			
			$member->optionalCheckbox1 = $memberData['optional_checkbox1'];
			$member->optionalCheckbox2 = $memberData['optional_checkbox2'];
			$member->optionalCheckbox3 = $memberData['optional_checkbox3'];
			$member->optionalCheckbox4 = $memberData['optional_checkbox4'];
			
			$user->member = $member;
		}
		return $user;
	}
	
	
	/**
	 * Returns data about a member identified by id
	 * 
	 * @param int $id
	 * @return \Regweb\ResourceType\Member
	 */
	public function getMember($id) {
		$request = $this->createRequest('members/:id', array('id' => $id), RestRequest::GET);
		
		$request->getParams['access_token'] = $this->authHandler->getAccessToken();
		$response = $request->execute();
		$memberData = $response->body;
		
		$member = new MemberResource();
		$member->id 		= $memberData['id'];
		$member->firstname 	= $memberData['firstname'];
		$member->lastname 	= $memberData['lastname'];
		$member->address1 	= $memberData['address1'];
		$member->address2 	= $memberData['address2'];
		$member->postalcode = $memberData['postalcode'];
		$member->phone1 	= $memberData['phone1'];
		$member->phone2 	= $memberData['phone2'];
		$member->mobile 	= $memberData['mobile'];
		$member->email 		= $memberData['email'];
		
		$member->optionalTextfield1 = $memberData['optional_textfield1'];
		$member->optionalTextfield2 = $memberData['optional_textfield2'];
		$member->optionalTextfield3 = $memberData['optional_textfield3'];
		$member->optionalTextfield4 = $memberData['optional_textfield4'];
		$member->optionalTextfield5 = $memberData['optional_textfield5'];
		$member->optionalTextfield6 = $memberData['optional_textfield6'];
		
		$member->optionalSelect1 = $memberData['optional_select1'];
		$member->optionalSelect2 = $memberData['optional_select2'];
		$member->optionalSelect3 = $memberData['optional_select3'];
		$member->optionalSelect4 = $memberData['optional_select4'];
		
		$member->optionalSelect1Label = $memberData['optional_select1_label'];
		$member->optionalSelect2Label = $memberData['optional_select2_label'];
		$member->optionalSelect3Label = $memberData['optional_select3_label'];
		$member->optionalSelect4Label = $memberData['optional_select4_label'];
		
		$member->optionalDate1 = $memberData['optional_date1'];
		$member->optionalDate2 = $memberData['optional_date2'];
		
		$member->optionalCheckbox1 = $memberData['optional_checkbox1'];
		$member->optionalCheckbox2 = $memberData['optional_checkbox2'];
		$member->optionalCheckbox3 = $memberData['optional_checkbox3'];
		$member->optionalCheckbox4 = $memberData['optional_checkbox4'];
		
		return $member;
	}
	
	/**
	 * 
	 * @param Member $member
	 */
	public function updateMember(MemberResource $member) {
		$request = $this->createRequest('members/:id', array('id' => $member->id), RestRequest::POST);
		
		$request->postParams['access_token'] = $this->authHandler->getAccessToken();
		
		if (isset($member->firstname)) { $request->postParams['firstname'] = $member->firstname; }
		if (isset($member->lastname)) { $request->postParams['lastname'] = $member->lastname; }
		if (isset($member->address1)) { $request->postParams['address1'] = $member->address1; }
		if (isset($member->address2)) { $request->postParams['address2'] = $member->address2; }
		if (isset($member->postalcode)) { $request->postParams['postalcode'] = $member->postalcode; }
		if (isset($member->phone1)) { $request->postParams['phone1'] = $member->phone1; }
		if (isset($member->phone2)) { $request->postParams['phone2'] = $member->phone2; }
		if (isset($member->mobile)) { $request->postParams['mobile'] = $member->mobile; }
		if (isset($member->email)) { $request->postParams['email'] = $member->email; }
		// Password
		if (isset($member->password)) { $request->postParams['password'] = $member->password; }
		
		// Optional fields
		if (isset($member->optionalTextfield1)) { $request->postParams['optional_textfield1'] = $member->optionalTextfield1; }
		if (isset($member->optionalTextfield2)) { $request->postParams['optional_textfield2'] = $member->optionalTextfield2; }
		if (isset($member->optionalTextfield3)) { $request->postParams['optional_textfield3'] = $member->optionalTextfield3; }
		if (isset($member->optionalTextfield4)) { $request->postParams['optional_textfield4'] = $member->optionalTextfield4; }
		if (isset($member->optionalTextfield5)) { $request->postParams['optional_textfield5'] = $member->optionalTextfield5; }
		if (isset($member->optionalTextfield6)) { $request->postParams['optional_textfield6'] = $member->optionalTextfield6; }
		if (isset($member->optionalSelect1)) { $request->postParams['optional_select1'] = $member->optionalSelect1; }
		if (isset($member->optionalSelect2)) { $request->postParams['optional_select2'] = $member->optionalSelect2; }
		if (isset($member->optionalSelect3)) { $request->postParams['optional_select3'] = $member->optionalSelect3; }
		if (isset($member->optionalSelect4)) { $request->postParams['optional_select4'] = $member->optionalSelect4; }
		if (isset($member->optionalDate1)) { $request->postParams['optional_date1'] = $member->optionalDate1; }
		if (isset($member->optionalDate2)) { $request->postParams['optional_date2'] = $member->optionalDate2; }
		if (isset($member->optionalCheckbox1)) { $request->postParams['optional_checkbox1'] = $member->optionalCheckbox1; }
		if (isset($member->optionalCheckbox2)) { $request->postParams['optional_checkbox2'] = $member->optionalCheckbox2; }
		if (isset($member->optionalCheckbox3)) { $request->postParams['optional_checkbox3'] = $member->optionalCheckbox3; }
		if (isset($member->optionalCheckbox4)) { $request->postParams['optional_checkbox4'] = $member->optionalCheckbox4; }
		
		$response = $request->execute();
		
		switch ($response->statusCode) {
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
	
	/**
	 * Fetches possible values for optional select field
	 * 
	 * @param int $id
	 * @return \Regweb\Rest\ResourceType\OptionalSelectValues
	 */
	public function getOptionalSelectValues($id) {
		$request = $this->createRequest('optionalselectvalues/:id', array('id' => $id));
		
		$request->getParams['access_token'] = $this->authHandler->getAccessToken();
		
		$response = $request->execute();
		
		$values = new OptionalSelectValuesResource();
		
		$values->id = $response->body['id'];
		$values->label = $response->body['label'];
		$values->values = array();
		foreach ($response->body['values'] as $value) {
			$valueObj = new OptionalSelectValueResource();
			$valueObj->id = $value['id'];
			$valueObj->label = $value['label'];
			$values->values[] = $valueObj;
		}
		
		return $values;
	}
	
	public function lostPassword($identification) {
		$request = $this->createRequest('lostpassword', null, RestRequest::POST);
		
		$request->postParams['identification'] = $identification;
		$response = $request->execute();
		return $response->body;
	}
}
