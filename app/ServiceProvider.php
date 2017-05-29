<?php
namespace App;

Class ServiceProvider
{
	public function getAllServiceProviders()
	{
		// Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl, array('cache_wsdl' => WSDL_CACHE_NONE) );        
        $service_providers = json_decode($client->GetAllOrbitServiceProviderssasJSON()->GetAllOrbitServiceProviderssasJSONResult);

        foreach ($service_providers as $service_provider) {
            $result[]  = [ 
                            'ServiceProviderId'     => $service_provider->ServiceProviderId,
                            'ServiceProviderName'   => $service_provider->ServiceProviderName,
                            'ContactEmail'          => $service_provider->ContactEmail,
                            'ContactName'           => $service_provider->ContactName,
                            'ContactPhone'          => $service_provider->ContactPhone,
                            'ServiceProviderAbout'  => $service_provider->ServiceProviderAbout,
                            'ServiceProviderLogo'   => $service_provider->ServiceProviderLogo,
                            'ServiceProviderURL'    => $service_provider->ServiceProviderURL,

                            'CreatedBy'     => $service_provider->CreatedBy,
                            'UpdatedBy'     => $service_provider ->UpdatedBy,
                            'CreatedOn'     => $service_provider->CreatedOn,
                            'UpdatedOn'     => $service_provider->UpdatedOn,
                        ];
        }

        return $result;
	}
}

