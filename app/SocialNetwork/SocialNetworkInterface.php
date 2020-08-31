<?php

namespace App\SocialNetwork;

interface SocialNetworkInterface
{
    public function login();

    public function create();

    public function read();

    public function update();

    public function delete();
}