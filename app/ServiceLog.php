<?php
namespace App;

use Auth;

use App\Log;
use App\ServiceProvider;
use App\EReferral;
use App\Question;

/**
 * ServiceLog Model.
 *
 * @author Christian Arevalo
 * @version 1.0.1
 * @see Model
 */
class ServiceLog extends Model
{
    const DATE_FORMAT = 'd/m/Y H:i';

    public function getGeneralSettings($sv_id)
    {
        $log = new Log();

        $general_settings = $log->getLogByDataCondition('service', $sv_id, ['data->general_settings', '!=', '']);
        $catchment_info = $log->getLogByDataCondition('service', $sv_id, ['data->catchment_info', '!=', '']);
        // $eligibility_questions  = $log->getLogByDataCondition('service', $sv_id, ['data->eligibility_questions', '!=', '']);
        // $legal_matters = $log->getLogByDataCondition('service', $sv_id, ['data->legal_matters', '!=', '']);
        // $actions_refer = $log->getLogByDataCondition('service', $sv_id, ['data->actions_refer', '!=', '']);
        // $actions_e_refer = $log->getLogByDataCondition('service', $sv_id, ['data->actions_e_refer', '!=', '']);
        // $e_referral_forms = $log->getLogByDataCondition('service', $sv_id, ['data->e_referral_forms', '!=', '']);
        // $actions_book = $log->getLogByDataCondition('service', $sv_id, ['data->actions_book', '!=', '']);

        // dd(self::compareLM(array_reverse($eligibility_questions)));

        return [
            'general_settings' => self::formatGeneralSettings(self::compareElements($general_settings)),
            'catchment_info' => self::formatCatchments(self::compareElements($catchment_info)),
            // 'eligibility_questions' => self::formatAll(self::compareElements($eligibility_questions), 'eligibility_questions'),
            // 'legal_matters' => self::formatAll(self::compareElements($legal_matters), 'legal_matters'),
            // 'actions_refer' => self::formatAll(self::compareElements($actions_refer), 'actions_refer'),
            // 'actions_e_refer' => self::formatAll(self::compareElements($actions_e_refer), 'actions_e_refer'),
            // 'e_referral_forms' => self::formatAll(self::compareElements($e_referral_forms), 'e_referral_forms'),
            // 'actions_book' => self::formatAll(self::compareElements($actions_book), 'actions_book')
        ];
    }

    public function formatGeneralSettings($general_settings)
    {
        $user = new User();

        $history = [];
        foreach ($general_settings as $gs) {
            $date_log = date_format(date_create($gs['created_at']), self::DATE_FORMAT);
            $data = $gs['data']['general_settings'];
            foreach ($data as $key => $value) {
                if ($key !== 'CreatedOn' && $key !== 'UpdatedOn' && $key !== 'ServiceId') {
                    $history[$date_log][$key] = $value;
                }
            }
            if (isset($history[$date_log]) && !empty($history[$date_log]) && isset($gs['user_id'])) {
                $user_info = $user->find($gs['user_id']);
                $history[$date_log]['Made By'] = $user_info->name;
            }
        }
        return $history;
    }

    public function formatCatchments($catchment_info)
    {
        $user = new User();

        $history = [];
        foreach ($catchment_info as $gs) {
            $date_log = date_format(date_create($gs['created_at']), self::DATE_FORMAT);
            $data = (isset($gs['data']) ? $gs['data']['catchment_info'] : []);
            foreach ($data as $key => $value) {
                if ($key !== 'CreatedOn' && $key !== 'UpdatedOn') {
                    $history[$date_log][$key] = $value;
                }
            }
            if (isset($history[$date_log]) && !empty($history[$date_log]) && isset($gs['user_id'])) {
                $user_info = $user->find($gs['user_id']);
                $history[$date_log]['Made By'] = $user_info->name;
            }
        }
        return $history;
    }

    public function formatAll($all_req, $type)
    {
        $user = new User();
        $sp = new ServiceProvider();
        $all_sps = $sp->getAllServiceProviders();
        $er = new EReferral();
        $all_erf = $er->getAllEReferralFormsFormated();
        $qu = new Question();
        $all_qs = $qu->getAllQuestions();


        $history = [];
        foreach ($all_req as $gs) {
            $date_log = date_format(date_create($gs['created_at']), self::DATE_FORMAT);
            $data = (isset($gs['data']) ? $gs['data'][$type] : []);
            foreach ($data as $key => $value) {
                if ($key !== 'CreatedOn' && $key !== 'UpdatedOn') {
                    if ($type === 'actions_refer'
                        || $type === 'actions_e_refer'
                        || $type === 'actions_book') {
                        $history[$date_log][$key] = self::getName($all_sps, $value, 'ServiceProviderId', 'ServiceProviderName');
                    } elseif ($type === 'e_referral_forms') {
                        $history[$date_log][$key] = self::getName($all_erf, $value, 'id', 'text');
                    } elseif ($type === 'eligibility_questions') {
                        $history[$date_log][$key] = self::getName($all_qs, $value, 'QuestionId', 'QuestionLabel');
                    } elseif ($type === 'legal_matters') {
                        if (isset($value['text'])) {
                            $history[$date_log][$key] = $value['text'];
                        }
                    } else {
                        $history[$date_log][$key] = $value;
                    }
                }
            }
            if (isset($history[$date_log]) && !empty($history[$date_log]) && isset($gs['user_id'])) {
                $user_info = $user->find($gs['user_id']);
                $history[$date_log]['Made By'] = $user_info->name;
            }
        }
        return $history;
    }

    public function compareElements($array_elements)
    {
        $previous = [];
        $differences = [];
        foreach ($array_elements as $key => $value) {
            if (!empty($previous)) {
                $differences[$key] = self::array_diff_assoc_recursive($previous, $value);
                $differences[$key]['user_id'] = $previous['user_id'];
            }
            $previous = $value;
        }
        return $differences;
    }

    public function array_diff_assoc_recursive($array1, $array2)
    {
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key])) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = self::array_diff_assoc_recursive($value, $array2[$key]);
                    if ($new_diff != false) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!isset($array2[$key]) || $array2[$key] != $value) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? 0 : $difference;
    }


    private function getName($elements, $id, $id_name, $prop_name)
    {
        foreach ($elements as $element) {
            if ($element[$id_name] === $id) {
                return $element[$prop_name];
            }
        }
    }

    private function compareLM($array_elements)
    {
        $previous = [];
        $differences = [];
        foreach ($array_elements as $key => $value) {
            $data = $value['data']['eligibility_questions'];
            if (!empty($previous)) {
                $differences[$key] = self::compareArrays($previous, $data);
                $differences[$key]['user_id'] = $value['user_id'];
                $differences[$key]['created_at'] = $value['created_at'];
            }
            $previous = $data;
        }
        return $differences;
    }

    private function compareArrays($array1, $array2)
    {
        return [
            'removed' => array_diff($array1, $array2),
            'added' => array_diff($array2, $array1)
        ];
    }
}
