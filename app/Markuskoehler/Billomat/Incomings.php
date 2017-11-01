<?php
namespace App\Markuskoehler\Billomat;

class Incomings {
    public function get()
    {
        //return app(Request::class)->get('incomings', null, ['to' => '2017-10-30', 'from' => '2014-01-01'], ['status','supplier']);
        //return app(Request::class)->get('incomings', null, ['to' => '2017-10-30', 'from' => '2014-01-01']);
        return app(Request::class)->get('incomings', 156025, [], [], ['pdf']);
        //return app(Request::class)->get('incomings', 156025, ['format' => 'pdf'], [], ['pdf']);
    }
}