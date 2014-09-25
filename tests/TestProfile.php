<?php

namespace app\social\tests;

use app\social\models\SocialMediaProfile;

class TestProfile extends SocialMediaProfile
{
	function userPropertyForProfileId()
	{
		return 'test_profile_id';
	}

	function apiPropertyMapping()
	{
		// TODO
		return [];
	}

	function daysUntilStale()
	{
		return 30;
	}

	function numProfilesToRefresh()
	{
		return 180;
	}

	function url()
	{
		// not implemented
		return false;
	}

	function profilePicture($size = 80)
	{
		// not implemented
		return false;
	}

	function isLoggedIn()
	{
		// not implemented
		return false;
	}

	function getProfileFromApi()
	{
		// TODO
		return [];
	}
}