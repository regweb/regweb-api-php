<?php
namespace Regweb\Rest\ResourceType;

class User {
	public $username;
	public $firstname;
	public $lastname;
	public $isMember;
	/**
	 * Member data
	 * @var Member
	 */
	public $member;
}