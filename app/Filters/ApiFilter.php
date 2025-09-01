<?php
namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter {
    protected $safeParms = [];
    protected $opreatorMap = [];

    public function transform(Request $request){
        $eloQuery = [];

        foreach( $this->safeParms as $parm => $opreators){
            $query = $request->query($parm);
            if(!isset($query)){
                continue;
            }
            
            foreach($opreators as $opreator){
                if(isset($query[$opreator])){
                    $eloQuery[] = [$parm, $this->opreatorMap[$opreator], $query[$opreator]]; 
                }
            }
        }

        return $eloQuery;
    }
}