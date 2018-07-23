<?php
namespace App;

/**
 * Legal Matter Type Model.
 * Model for the legal matter type functionalities
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */

Class MatterType extends OrbitSoap
{
    /**
     * Get all Legal Matter Types
     *
     * @return array Array with Legal Matter Types
     */
	public function getAllMatterTypes()
	{
        $matter_types = json_decode(
                                        $this
                                        ->client
                                        ->ws_init('GetAllLegalMatterTypessasJSON')
                                        ->GetAllLegalMatterTypessasJSON()
                                        ->GetAllLegalMatterTypessasJSONResult
                                    );

        foreach ($matter_types as $matter_type) {
            $result[]  = [
                            'MatterTypeID' => $matter_type->MatterTypeID,
                            'MatterTypeName' => $matter_type->MatterTypeName,
                            'CreatedBy' => $matter_type->CreatedBy,
                            'UpdatedBy' => $matter_type ->UpdatedBy,
                            'CreatedOn' => $matter_type->CreatedOn,
                            'UpdatedOn' => $matter_type->UpdatedOn,
                        ];
        }

        return $result;
	}

    /**
     * Save Legal Matter Type
     *
     * @param array $matter_type Legal Matter Type information
     * @return array Array with error or success message
     */
    public function saveMatterType( $matter_type )
    {
        $info = [
                    'ObjectInstance' => [
                        'MatterTypeName' => $matter_type['title'],
                        'CreatedBy' => auth()->user()->name,
                        'CreatedOn' => '2017-05-11T16:00:00',
                        'UpdatedBy' => auth()->user()->name,
                        'UpdatedOn' => '2017-05-11T16:00:00'
                    ]
                ];
        try {
            $response = $this
                        ->client
                        ->ws_init('SaveMatterType')
                        ->SaveMatterType($info);

            if ($response->SaveMatterTypeResult) {
                return ['success' => 'success' , 'message' => 'Legal matter Type deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Delete Legal Matter Type
     *
     * @param array $matter_type Legal Matter Type information
     * @return array Array with error or success message
     */
    public function deleteMatter($mt_id)
    {
        $info = [ 'RefNumber' => $mt_id];

        try {
            $response = $this
                        ->client
                        ->ws_init('DeleteMatterType')
                        ->DeleteMatterType($info);

            if ($response->DeleteMatterTypeResult) {
                return ['success' => 'success' , 'message' => 'Legal matter Type deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }

    }

}

