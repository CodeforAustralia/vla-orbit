<?php
namespace App;

/**
 * Report model for the report functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  OrbitSoap
 */
class Report extends OrbitSoap
{
    /**
     * Get ORBIT stats
     * @param  date $financial_year year for retrieve stats
     * @return [type]                 [description]
     */
    public function getDashboadStats($financial_year)
    {
        $period = [ 'DateObject' => $financial_year ]; //Financial year

        $stats = json_decode($this
                            ->client
                            ->ws_init_local('GetAllStatsasJSON')
                            ->GetAllStatsasJSON($period)
                            ->GetAllStatsasJSONResult);

        return $stats;
    }
}
