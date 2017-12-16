<?php
namespace App\Markuskoehler\Billomat;

class Creditors {
    protected $suppliers = null;

    public function get($id = null)
    {
        //return app(Request::class)->get('incomings', null, ['to' => '2017-10-30', 'from' => '2014-01-01'], ['status','supplier']);
        //return app(Request::class)->get('incomings', null, ['to' => '2017-10-30', 'from' => '2014-01-01']);
        //return app(Request::class)->get('incomings', 156025, [], [], ['pdf']);

        if(!is_null($id)) {
            //return app(Request::class)->get('suppliers', $id);
            return $this->getIdRef($id);
        }

        // else return all suppliers
        if(is_null($this->suppliers)) $this->suppliers = app(Request::class)->get('suppliers')->suppliers->supplier;
        return $this->suppliers;
        //return app(Request::class)->get('incomings', 156025, ['format' => 'pdf'], [], ['pdf']);
    }

    /**
     * get Creditors reordered to be able to select one by key = id
     */
    protected function getIdRef($id = null) {
        if(is_null($this->suppliers)) $this->suppliers = app(Request::class)->get('suppliers')->suppliers->supplier;
        $supplierRef = [];
        foreach($this->suppliers as $supplier) {
            $supplierRef[$supplier->id] = $supplier;
        }
        if(!is_null($id)) return $supplierRef[$id];
        return $supplierRef;
    }
}