<?php
namespace Regweb\Rest\ResourceType;

class User {
	public $username;
	public $firstname;
	public $lastname;
	public $isMember;
	public $email;
	/**
	 * Member data
	 * @var Member
	 */
	public $member;
}