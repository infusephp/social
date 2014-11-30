<?php

use \app\social\models\SocialMediaProfile;

class TestProfile extends SocialMediaProfile
{
    public function userPropertyForProfileId()
    {
        return 'test_profile_id';
    }

    public function apiPropertyMapping()
    {
        // TODO
        return [];
    }

    public function daysUntilStale()
    {
        return 30;
    }

    public function numProfilesToRefresh()
    {
        return 180;
    }

    public function url()
    {
        // not implemented
        return false;
    }

    public function profilePicture($size = 80)
    {
        // not implemented
        return false;
    }

    public function isLoggedIn()
    {
        // not implemented
        return false;
    }

    public function getProfileFromApi()
    {
        // TODO
        return [];
    }
}
