<?php

namespace App\Helpers;

class MyHelpers
{
    private $text;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param $text
     * @return MyHelpers|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public static function setText($text)
    {
        $myHelpers       = app(MyHelpers::class);
        $myHelpers->text = $text;

        return $myHelpers;
    }
}