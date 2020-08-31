<?php

namespace App\SocialNetwork;

/**
 * Class SocialNetworkService
 * @package App\SocialNetwork
 * @method login()
 */
class SocialNetworkService
{
    /**
     * @var SocialNetworkInterface
     */
    private $socialNetwork;

    /**
     * SocialNetworkService constructor.
     * @param  SocialNetworkInterface  $socialNetwork
     */
    public function __construct(SocialNetworkInterface $socialNetwork)
    {
        $this->socialNetwork = $socialNetwork;
    }
}