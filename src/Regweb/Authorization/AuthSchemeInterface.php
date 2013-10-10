<?php
namespace Regweb\Authorization;

interface AuthSchemeInterface {
	public function getAccessToken();
}