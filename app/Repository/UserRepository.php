<?php

namespace App\Repository;

use App\Models\User;
use Auth0\Login\Contract\Auth0UserRepository;

class UserRepository implements Auth0UserRepository
{

    /* This class is used on api authN to fetch the user based on the jwt.*/
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
            // If not, create one
            $user = new User();
            $user->email = $profile->email; // you should ask for the email scope
            $user->auth0id = $profile->sub;
            $user->name = $profile->name; // you should ask for the name scope
            $user->password = md5(time());
            $user->save();
        }

        return $user;
    }

    public function getUserByIdentifier($identifier)
    {
        //Get the user info of the user logged in (probably in session)
        $user = app('auth0')->getUser();

        if ($user === null) return null;

        // build the user
        $user = $this->getUserByUserInfo($user);

        // it is not the same user as logged in, it is not valid
        if ($user && $user->auth0id == $identifier) {
            return $user;
        }
    }

}