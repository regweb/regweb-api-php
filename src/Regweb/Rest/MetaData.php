<?php

namespace Regweb\Rest;

class MetaData {
	
	protected $title;
	protected $description;
	
	protected $urls;
	
	public function __construct() {
		$this->urls = array(
			'GET' => array(),
			'POST' => array()
		);
		
		$this->addRequestEntry(new RequestMetaData(
			'Authentication and refreshing of access token',
			'Authentication, used to authenticate by username and password, as well as refresh access token.<br><br>'.
			'If used with grant_type = &quot;password&quot;, provide username and password. On successful authentication, '.
			'this call will provide an access_token which represents a session in the regweb system. Pass this on subsequent '.
			'requests to identify the session.<br><br>'.
			'This url is also used to refresh access token when it is expired. This is done by using grant_type = &quot;refresh&quot; '.
			'and passing the refresh_token recieved on authentication.',
			'oauth2/token',
			RequestMetaData::POST,
			array(),
			array(
				'grant_type' => 'Use &quot;password&quot; for credentials login, &quot;refresh&quot; for refreshing access_token.',
				'client_id' => 'Client id',
				'client_secret' => 'Client secret',
				'refresh_token' => 'Refresh token to extend session and recieve a new access_token. Refresh token is recieved on authentication.',
				'username' => 'For credentials login, member id or email for the user that is logging in.',
				'password' => 'For credentials login, password for the user that is logging in.'
			),
			array(
				'200' => array(	'description' => 'Success',
					'data' => array(
						'access_token' => 'Represents a session in the Regweb system. To be sent with other requests.',
						'refresh_token' => 'Used when access token expires to recieve a new access token and extend the session.',
						'expires_in' => 'Seconds until session expires.',
						'token_type' => 'Token type, see oauth for more information',
						'scope' => 'Scope, currently unused, see oauth for more information'
					)
				),
				'400' => array(	'description' => 'Missing parameters, possibly username or password',
					'data' => array(
						'error' => 'Key to identify the error',
						'error_description' => 'Readable error description.'
				)),
				'401' => array(	'description' => 'Authentication failed',
					'data' => array(
						'error' => 'Key to identify the error',
						'error_description' => 'Readable error description.',
						'member_active_check_failed' => 'Flag to indicate whether authentication failed because the user is not active.',
						'unique_email_check_failed' => 	'Flag to indicate whether authentication failed because the provided email '.
														'wasn\'t unique in the system.'
				))
				
			)
		));
		
		$optionalSelectData = array(
			'label' => 'Label for the optional select',
			'values' => array(
				'description' => 'Data about options for this field.',
				'data' => array(
					'id' => 'Id for this entry',
					'label' => 'Label of this id'
				)
			)
		);
		
		$memberTypeData = array(
			'id' => 'ID of membertype',
			'name' => 'Membertype label',
			'contingent' => 'Amount for contingent'
		);
		
		$memberData = array(
			'id' => 'Member id',
			'active' => 'Whether the member is active',
			'firstname' => 'First name',
			'lastname' => 'Last name',
			'address1' => 'Address 1',
			'address2' => 'Address 2',
			'postalcode' => 'Postal code',
			'phone1' => 'Phone 1',
			'phone2' => 'Phone 2',
			'mobile' => 'Mobile phone number',
			'email' => 'Email',
	
			'optional_textfield1' => 'Optional textfield 1',
			'optional_textfield2' => 'Optional textfield 2',
			'optional_textfield3' => 'Optional textfield 3',
			'optional_textfield4' => 'Optional textfield 4',
			'optional_textfield5' => 'Optional textfield 5',
			'optional_textfield6' => 'Optional textfield 6',
	
			'optional_select1' => 'Optional selectfield 1',
			'optional_select1_label' => 'Label for optional selectfield 1',
			'optional_select2' => 'Optional selectfield 2',
			'optional_select2_label' => 'Label for optional selectfield 2',
			'optional_select3' => 'Optional selectfield 3',
			'optional_select3_label' => 'Label for optional selectfield 3',
			'optional_select4' => 'Optional selectfield 4',
			'optional_select4_label' => 'Label for optional selectfield 4',
	
			'optional_date1' => 'Optional datefield 1',
			'optional_date2' => 'Optional datefield 2',
	
			'optional_checkbox1' => 'Optional checkbox 1',
			'optional_checkbox2' => 'Optional checkbox 2',
			'optional_checkbox3' => 'Optional checkbox 3',
			'optional_checkbox4' => 'Optional checkbox 4',
			
			'membertype' => array(
				'description' => 'Membertype data if expanded',
				'expand' => 'membertype',
				'data' => array('id' => 'ID of membertype', 'name' => 'Membertype label'),
				'expanded_data' => $memberTypeData)
		);
		
		$this->addRequestEntry(new RequestMetaData(
			'Get data about optional select',
			'Returns meta data about optional select fields. Returns label for the option field itself, '.
			'as well as labels and id for each option.',
			'optionalselectvalues/:id',
			RequestMetaData::GET,
			array(
				'access_token' => 'Regweb session identifier. This is recieved on authentication.'
			),
			array(),
			array(
				'200' => array(
					'description' => 'Success',
					'data' => $optionalSelectData
				)
			)
		));
		
		$this->addRequestEntry(new RequestMetaData(
			'Get membertype data',
			'Returns some data about a specified membertype.',
			'membertypes/:id',
			RequestMetaData::GET,
			array(
				'access_token' => 'Regweb session identifier. This is recieved on authentication.'
			),
			array(),
			array(
				'200' => array(
					'description' => 'Success',
					'data' => array(
						'id' => 'Membertype id',
						'name' => 'Label for the membertype',
						'contingent' => 'Contingent amount'
					)
				)
			)
		));
		
		$this->addRequestEntry(new RequestMetaData(
			'Get user data',
			'Returns data about the currently logged in user. You can use the expand parameter &quot;member&quot; to '.
			'also include member data if this user is a normal member.',
			'user',
			RequestMetaData::GET,
			array(
				'access_token' => 'Regweb session identifier. This is recieved on authentication.',
				'expand' => 'Used to load additional related data, specify "member" to load member data.'),
			array(),
			array(
				'200' => array(
					'description' => 'Success',
					'data' => array(
						'username' => 'Username',
						'firstname' => 'First name',
						'lastname' => 'Last name',
						'is_member' => 'Whether the user is a normal member',
						'email' => 'Email',
						'member' => array(
							'description' => 'Member data if expanded',
							'expand' => 'member',
							'data' => array('id' => 'Member id'),
							'expanded_data' => $memberData)
				))
			)
		));
		
		$this->addRequestEntry(new RequestMetaData(
			'Get member data',
			'Returns data for a specified member.',
			'members/:id',
			RequestMetaData::GET,
			array('access_token' => 'Regweb session identifier. This is recieved on authentication.'),
			array(),
			array(
				'200' => array(
					'description' => 'Success',
					'data' => $memberData),
				'403' => array(
					'description' => 'Logged in user is not authorized to access this member',
					'data' => array(
						'error' => 'Key to identify the error',
						'error_description' => 'Readable error description.')),
				'404' => array(
					'description' => 'Member not found',
					'data' => array(
						'error' => 'Key to identify the error',
						'error_description' => 'Readable error description.'))
			)
		));
		
		$this->addRequestEntry(new RequestMetaData(
			'Update member data',
			'Updates member data for a specified member. You only need to pass the fields that need to be updated, '.
			'other fields will be ignored.<br><br>'.
			'On success, this call will return with status code 200<br>On failed validation it will return with status code 400, '.
			'as well as a data structure with keys for each field validation failed on. These keys will contain an array '.
			'with each error message for the field.',
			'members/:id',
			RequestMetaData::POST,
			array(),
			array(
				'access_token' => 'Regweb session identifier. This is recieved on authentication.',
				
				'firstname' => 'First name',
				'lastname' => 'Last name',
				'address1' => 'Address 1',
				'address2' => 'Address 2',
				'postalcode' => 'Postal code',
				'phone1' => 'Phone 1',
				'phone2' => 'Phone 2',
				'mobile' => 'Mobile phone number',
				'email' => 'Email',
				'password' => 'Password',
				
				'optional_textfield1' => 'Optional textfield 1',
				'optional_textfield2' => 'Optional textfield 2',
				'optional_textfield3' => 'Optional textfield 3',
				'optional_textfield4' => 'Optional textfield 4',
				'optional_textfield5' => 'Optional textfield 5',
				'optional_textfield6' => 'Optional textfield 6',
				
				'optional_select1' => 'Optional selectfield 1',
				'optional_select2' => 'Optional selectfield 2',
				'optional_select3' => 'Optional selectfield 3',
				'optional_select4' => 'Optional selectfield 4',
				
				'optional_date1' => 'Optional datefield 1',
				'optional_date2' => 'Optional datefield 2',
				
				'optional_checkbox1' => 'Optional checkbox 1',
				'optional_checkbox2' => 'Optional checkbox 2',
				'optional_checkbox3' => 'Optional checkbox 3',
				'optional_checkbox4' => 'Optional checkbox 4'
			),
			array(
				'200' => array(
					'description' => 'Success',
					'data' => array('success' => 'Whether data was updated successfully')),
				'400' => array(
					'description' => 'Validation failed. See general description for information about data returned.',
					'data' => array())
			)
		));
		
		$this->addRequestEntry(new RequestMetaData(
			'Lost password',
			'Requests a new password to be sent to the registered email of this user.',
			'lostpassword',
			RequestMetaData::POST,
			array(),
			array(
				'identification' => 'Member number or email to identify the user.'
			),
			array(
				'200' => array(
					'description' => 'Success',
					'data' => array('success' => 'True if request was successful and email was sent.')),
				'400' => array(
					'description' => 'Invalid parameters',
					'data' => array()),
				'404' => array(
					'description' => 'User not found',
					'data' => array())
				)
			)
		);
	}
	
	public static function getInstance() {
		static $instance;
		if ($instance == null) {
			$instance = new self();
		}
		return $instance;
	}
	
	/**
	 * Adds doc entry. Will key it on the url in an array structure representing
	 * parts of the url, so it will be easier to lookup based on the url.
	 * 
	 * @param RequestMetaData $entry
	 */
	public function addRequestEntry(RequestMetaData $entry) {
		$this->urls[$entry->method][$entry->url] = $entry;
	}
	
	/**
	 * Returns metadataentry based on the url called.
	 * 
	 * @param string $url
	 * @return RequestMetaData
	 */
	public function getEntryByUrl($url, $method) {
		return $this->urls[$method][$url];
	}
}