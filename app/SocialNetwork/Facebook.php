<?php

namespace App\SocialNetwork;

class Facebook implements SocialNetworkInterface
{
    public function login()
    {
        return 'Facebook Login';
    }

    public function create()
    {
        return 'Facebook create';
    }

    public function read()
    {
        return 'Facebook read';
    }

    public function update()
    {
        return 'Facebook update';
    }

    public function delete()
    {
        return 'Facebook delete';
    }
}