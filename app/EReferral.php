<?php
namespace App;

use Auth;

Class EReferral
{
    public function getAllEReferralForms()
    {
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        $e_referral_forms = $client->GetAllReferralForms()->GetAllReferralFormsResult;         
        return $e_referral_forms->ReferralForm;
    }

    public function getAllEReferralFormsFormated()
    {
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        $e_referral_forms = $client->GetAllReferralForms()->GetAllReferralFormsResult;      
        $forms = $e_referral_forms->ReferralForm;
        $output = [];
        foreach ($forms as $form) 
        {
            $output[] = ['id' => $form->RefNo, 'text' => $form->Name ];
        }   
        return $output;
    }

    public function saveEReferralForm( $e_referral_form )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
                
        // Create call request        
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;
        //filter_var($email_data['subject'], FILTER_SANITIZE_STRING),
        $info = [ 'ObjectInstance' => [                        
                        'RefNo'		  => filter_var( $e_referral_form['RefNo'], FILTER_SANITIZE_STRING ),
                        'Name'		  => filter_var( $e_referral_form['Name'], FILTER_SANITIZE_STRING ),
                        'Description' => filter_var( $e_referral_form['Description'], FILTER_SANITIZE_STRING ),

                        'CreatedBy' => auth()->user()->name,
                        'Created' => $date_time,
                        'UpdatedBy' => auth()->user()->name,
                        'Updated' => $date_time
                        ]                    
                ];
                //dd( $info );
        try {
            $response = $client->SaveReferralForm($info);
            if($response->SaveReferralFormResult){
                return array( 'success' => 'success' , 'message' => 'E-Referral Form created.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );           
        }
    }

    public function getEReferralFormByID( $erf_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'FormId' => $erf_id];

        try 
        {
            $response = $client->GetReferralFormsById($info);
            if($response->GetReferralFormsByIdResult)
            {
                return array( 'success' => 'success' , 'message' => 'E-Referral Form.', 'data' => $response->GetReferralFormsByIdResult );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    
    }

    public function deleteEReferralForm( $erf_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'FormId' => $erf_id];

        try 
        {
            $response = $client->DeleteReferralForm($info);
            if($response->DeleteReferralFormResult)
            {
                return array( 'success' => 'success' , 'message' => 'E-Referral Form deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }

    public function deleteAllEReferralByServiceId( $sv_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'ServiceId' => $sv_id];

        try 
        {
            $response = $client->DeleteReferralFormServiceByServiceId($info);
            if($response->DeleteReferralFormServiceByServiceIdResult)
            {
                return array( 'success' => 'success' , 'message' => 'E-Referrals deleted from service.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }

    public function saveAllFormsInService( $sv_id, $form_ids )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        $info = [];
        try 
        {
            foreach ($form_ids as $form_id) 
            {
                $info[] = [
                            'RefNo'=> 0,
                            'ReferralFormID'=> $form_id,
                            'ServiceId'=> $sv_id,
                          ];
                
            }  
            $response = $client->SaveAllReferralFormService( ['ObjectInstance'  => $info ] );
            if($response->SaveAllReferralFormServiceResult)
            {
                return array( 'success' => 'success' , 'message' => 'E-Referral Associated to service.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
}