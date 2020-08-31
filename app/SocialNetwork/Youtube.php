<?php

namespace App\SocialNetwork;

class Youtube implements SocialNetworkInterface
{
    public function login()
    {
        return 'Youtube Login';
    }

    public function create()
    {
        return 'Youtube create';
    }

    public function read()
    {
        return 'Youtube read';
    }

    public function update()
    {
        return 'Youtube update';
    }

    public function delete()
    {
        return 'Youtube delete';
    }
}