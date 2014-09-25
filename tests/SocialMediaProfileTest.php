<?php

use app\users\models\User;

class SocialMediaProfileTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        include 'TestProfile.php';
    }

    public function testPermissions()
    {
        $profile = new TestProfile(101);
        $user = new User;

        $this->assertTrue($profile->can('create', $user));
        $this->assertFalse($profile->can('view', $user));
        $this->assertFalse($profile->can('edit', $user));

        $profile = new TestProfile(100);
        $user->test_profile_id = 100;
        $this->assertTrue($profile->can('view', $user));
        $this->assertTrue($profile->can('edit', $user));
    }

	function testRefreshProfile()
	{
		$this->markTestIncomplete();
	}

	function testRefreshProfiles()
	{
		$this->markTestIncomplete();
	}
}