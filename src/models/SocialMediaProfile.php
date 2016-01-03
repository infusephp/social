<?php

namespace app\social\models;

use Infuse\Model;
use Infuse\Utility as U;

abstract class SocialMediaProfile extends Model
{
    public static $scaffoldApi;
    public static $autoTimestamps;

    protected function hasPermission($permission, Model $requester)
    {
        if ($permission == 'create') {
            return true;
        }

        $idProp = $this->userPropertyForProfileId();
        if (in_array($permission, ['view', 'edit']) && $this->id() == $requester->$idProp) {
            return true;
        }

        return $requester->isAdmin();
    }

    public function preCreateHook(&$data)
    {
        // check if updating profile properties from API
        $apiPropertyKeys = array_keys($this->apiPropertyMapping());
        foreach ($data as $property => $value) {
            if (in_array($property, $apiPropertyKeys)) {
                $data['last_refreshed'] = time();
                break;
            }
        }

        return true;
    }

    public function preSetHook(&$data)
    {
        // check if updating profile properties from API
        $apiPropertyKeys = array_keys($this->apiPropertyMapping());
        foreach ($data as $property => $value) {
            if (in_array($property, $apiPropertyKeys)) {
                $data['last_refreshed'] = time();
                break;
            }
        }

        return true;
    }

    /**
     * Gets the property name that matches this profile's ID off the User object
     * i.e. twitter_id, facebook_id, instagram_id.
     *
     * @return string property name
     */
    abstract public function userPropertyForProfileId();

    /**
     * Map of API properties that correspond with profile properties
     * [ 'model_property' => 'api_property' ].
     *
     * @return array
     */
    abstract public function apiPropertyMapping();

    /**
     * Gets the number of days until the profile is considered stale and
     * must be refreshed from the API.
     *
     * @return int days
     */
    abstract public function daysUntilStale();

    /**
     * Gets the number of profiles to refresh at once in order to preven bumping
     * into rate limiting or running forever.
     *
     * @return int
     */
    abstract public function numProfilesToRefresh();

    /**
     * Gets the URL of the profile.
     *
     * @return bool
     */
    abstract public function url();

    /**
     * Generates the URL for the profile's picture.
     *
     * @param int $size size of the picture (it is square, usually)
     *
     * @return string url
     */
    abstract public function profilePicture($size = 80);

    /**
     * Checks if InspireVive is authenticated for the profile.
     *
     * @return bool
     */
    abstract public function isLoggedIn();

    /**
     * Fetches the profile from the API.
     *
     * @return array|false
     */
    abstract public function getProfileFromApi();

    /**
     * Refreshes this profile using the API of the social network.
     *
     * @param array $userProfile optional user profile
     *
     * @return bool
     */
    public function refreshProfile(array $userProfile = [])
    {
        if (count($userProfile) == 0) {
            $userProfile = $this->getProfileFromApi();
        }

        $success = false;

        if (is_array($userProfile)) {
            $info = $this->mapPropertiesFromApi($userProfile);

            $this->grantAllPermissions();
            $success = $this->set($info);
            $this->enforcePermissions();
        }

        return $success;
    }

    /**
     * Refreshes stale twitter profiles. One call currently only refreshes
     * 180 profiles to minimize bumping into twitter's rate limiting.
     *
     * @return bool
     */
    public static function refreshProfiles()
    {
        $profile = new static();

        $staleDate = time() - ($profile->daysUntilStale() * 86400);

        $profiles = static::findAll([
            'where' => [
                'access_token <> ""',
                'last_refreshed < '.$staleDate, ],
            'limit' => $profile->numProfilesToRefresh(),
            'sortBy' => 'last_refreshed DESC', ]);

        foreach ($profiles as $profile) {
            $profile->refreshProfile();
        }

        return true;
    }

    /**
     * Maps the properties of the user profile from the API
     * to the properties in our model.
     *
     * @param array $user_profile user profile from API
     *
     * @return array
     */
    protected function mapPropertiesFromApi(array $user_profile)
    {
        $info = [];
        foreach ($this->apiPropertyMapping() as $modelProperty => $apiProperty) {
            $info[ $modelProperty ] = U::array_value($user_profile, $apiProperty);
        }

        return $info;
    }
}
