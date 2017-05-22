<?php

namespace App\Http;
class Helpers {

   public function VlaSimpleSAML(){
        $as = new SimpleSAML_Auth_Simple(env('SIMPLESML_SP'));
        $as->requireAuth();
        $attributes = $as->getAttributes();
        return $attributes;
    }
}