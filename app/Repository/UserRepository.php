<?php

namespace App\Repository;

use App\Models\User;
use Auth0\Login\Contract\Auth0UserRepository;

class UserRepository implements Auth0UserRepository
{

    /* This class is used on api auth0 to fetch the user based on the jwt. */
    public function getUserByDecodedJWT($jwt)
    {
        $jwt = (object) $jwt;
        $jwt->user_id = $jwt->sub;
        return $this->upsertUser($jwt);
    }

    public function getUserByUserInfo($userInfo)
    {
        $userInfo = (object) $userInfo;
        return $this->upsertUser($userInfo->profile);
    }

    protected function upsertUser($profile)
    {
        $profile = (object) $profile;
        $user = User::where("auth0id", $profile->sub)->first();

        if ($user === null) {
            // If not, create one // name & email scopes needed
            $user = new User();
            $user->email = $profile->email;
            $user->auth0id = $profile->sub;
            $user->name = $profile->name;
            $user->password = md5(time());
            $user->save();
        }

        return $user;
    }

    public function getUserByIdentifier($identifier)
    {
        // Get the user info of the user logged in (probably in session)
        $user = app('auth0')->getUser();

        if ($user === null) return null;

        // build the user
        $user = $this->getUserByUserInfo($user);

        // if it is not the same user as logged in, it is not valid
        if ($user && $user->auth0id == $identifier) {
            return $user;
        }
    }

}