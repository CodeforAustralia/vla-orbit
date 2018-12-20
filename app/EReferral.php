<?php
namespace App;

use Auth;

/**
 * EReferral Model.
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see Controller
 */
Class EReferral extends OrbitSoap
{
    /**
     * Get all eReferral forms
     *
     * @return object EReferral object with all ereferral forms
     */
    public function getAllEReferralForms()
    {

        $e_referral_forms = $this
                            ->client
                            ->ws_init('GetAllReferralForms')
                            ->GetAllReferralForms()
                            ->GetAllReferralFormsResult;
        return $e_referral_forms->ReferralForm;
    }

    /**
     * Get all eReferral forms from web services
     *
     * @return void eReferral forms in format id, text (catchmend id, suburb, postcode) to be used in a select2 field
     */
    public function getAllEReferralFormsFormated()
    {

        $e_referral_forms = $this
                            ->client
                            ->ws_init('GetAllReferralForms')
                            ->GetAllReferralForms()
                            ->GetAllReferralFormsResult;
        $forms = $e_referral_forms->ReferralForm;
        $output = [];

        foreach ($forms as $form) {

            $output[] = ['id' => $form->RefNo, 'text' => $form->Name ];
        }

        return $output;
    }

    private function getFields($e_referral_form)
    {
        $fields = [];
        if( array_key_exists ( 'dob', $e_referral_form ) ){
            $fields[] = 'dob';
        }
        if( array_key_exists ( 'suburb', $e_referral_form ) ){
            $fields[] = 'suburb';
        }
        if( array_key_exists ( 'postal_address', $e_referral_form ) ){
            $fields[] = 'postal_address';
        }
        if( array_key_exists ( 'email', $e_referral_form ) ){
            $fields[] = 'email';
        }
        return implode(',', $fields);
    }

    /**
     * Stores a new eReferral form
     *
     * @param array $e_referral_form Array with eReferral form information
     * @return array Array with error or success message
     */
    public function saveEReferralForm( $e_referral_form )
    {
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $body = '';
        if( isset($e_referral_form['Body']) ) {
            $body = $e_referral_form['Body'];
        }

        $fields = $this->getFields($e_referral_form);

        $info = [ 'ObjectInstance' =>
                    [
                        'RefNo'		  => filter_var( $e_referral_form['RefNo'], FILTER_SANITIZE_STRING ),
                        'Name'		  => filter_var( $e_referral_form['Name'], FILTER_SANITIZE_STRING ),
                        'Description' => filter_var( $e_referral_form['Description'], FILTER_SANITIZE_STRING ),
                        'Body'        =>  $body,
                        'Fields'      =>  $fields,
                        'CreatedBy' => auth()->user()->id,
                        'Created'   => $date_time,
                        'UpdatedBy' => auth()->user()->id,
                        'Updated'   => $date_time
                    ]
                ];

        try {
            $response = $this
                        ->client
                        ->ws_init('SaveReferralForm')
                        ->SaveReferralForm($info);

            if ($response->SaveReferralFormResult) {
                return ['success' => 'success' , 'message' => 'E-Referral Form created.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Get eReferral form information by ID
     *
     * @param integer $erf_id eReferral form ID
     * @return array Array with error or success message
     */
    public function getEReferralFormByID( $erf_id )
    {
        $info = [ 'FormId' => $erf_id];

        try {
            $response = $this
                        ->client
                        ->ws_init('GetReferralFormsById')
                        ->GetReferralFormsById($info);
            if ($response->GetReferralFormsByIdResult) {
                return ['success' => 'success' , 'message' => 'E-Referral Form.', 'data' => $response->GetReferralFormsByIdResult];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }

    }

    /**
     * Delete eReferral form by ID
     *
     * @param integer $erf_id eReferral form ID
     * @return array Array with error or success message
     */
    public function deleteEReferralForm( $erf_id )
    {
        $info = [ 'FormId' => $erf_id];

        try {
            $response = $this
                        ->client
                        ->ws_init('DeleteReferralForm')
                        ->DeleteReferralForm($info);

            if ($response->DeleteReferralFormResult) {
                return ['success' => 'success' , 'message' => 'E-Referral Form deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }
    /**
     * Delete all eReferral form reletaed to a service ID
     *
     * @param integer $sv_id Service ID
     * @return array Array with error or success message
     */
    public function deleteAllEReferralByServiceId( $sv_id )
    {
        $info = [ 'ServiceId' => $sv_id];

        try {
            $response = $this
                        ->client
                        ->ws_init('DeleteReferralFormServiceByServiceId')
                        ->DeleteReferralFormServiceByServiceId($info);

            if ($response->DeleteReferralFormServiceByServiceIdResult) {
                return ['success' => 'success' , 'message' => 'E-Referrals deleted from service.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Save eReferral forms available in a specific service
     *
     * @param integer $sv_id Service ID
     * @param array $form_ids Array of eReferral form IDs
     * @return array Array with error or success message
     */
    public function saveAllFormsInService( $sv_id, $form_ids )
    {
        $info = [];
        try {
            foreach ($form_ids as $form_id) {

                $info[] = [
                            'RefNo'=> 0,
                            'ReferralFormID'=> $form_id,
                            'ServiceId'=> $sv_id,
                          ];
            }

            $response = $this
                        ->client
                        ->ws_init('SaveAllReferralFormService')
                        ->SaveAllReferralFormService( ['ObjectInstance'  => $info ] );

            if ($response->SaveAllReferralFormServiceResult) {
                return ['success' => 'success' , 'message' => 'E-Referral Associated to service.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }
}