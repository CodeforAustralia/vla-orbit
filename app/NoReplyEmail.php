<?php
namespace App;

use App\Log as Logs;
use App\ServiceProvider;
use App\Mail\NoReplyEmailMailable;
use Auth;
use DateTime;
use Illuminate\Support\Facades\Mail;

/**
 * No Reply Email Model.
 * Model for the no reply email functionalities
 *
 * @author Christian Arevalo and Sebastian Currea
 * @version 1.2.1
 * @see  OrbitSoap
 */

class NoReplyEmail extends OrbitSoap
{
    const STORE_CLC_CLIENT_DATA = 'STORE_CLC_CLIENT_DATA';
    /**
     * Get All Email Templates
     *
     * @return array Array with error or success message
     */
    public function getAllTemplates()
    {
        try {
            $user = auth()->user();
            $response =  	$this
                            ->client
                            ->ws_no_reply_emails_init('GetAllTemplates')
                            ->GetAllTemplates()
                            ->GetAllTemplatesResult;
            return json_decode(json_encode($response->EmailTemplates), true);
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Get Email Template by ID
     *
     * @param integer $template_id Template ID
     * @return array Array with error or success message
     */
    public function getTemplateById($template_id)
    {
        try {
            $templates = self::getAllTemplates();

            foreach ($templates as $template) {
                if ($template['RefNo'] == $template_id) {
                    return $template;
                }
            }
            return false;
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Get All Email Templates by Section (Department)
     *
     * @return array Array with error or success message
     */
    public function getAllTemplatesBySection()
    {
        try {
            $data = [];
            if (auth()->user()->sp_id != 0) {
                $section = self::getSection();
                $info = [ 'Section' => $section ];
                $response = $this
                            ->client
                            ->ws_no_reply_emails_init('GetTemplatesBySection')
                            ->GetTemplatesBySection($info);
                $templates = json_decode(json_encode($response->GetTemplatesBySectionResult->EmailTemplates), true);

                foreach ($templates as $template) {
                    $user = User::find($template['CreatedBy']);
                    if ($template['RefNo'] > 0) {
                        if (isset($user)) {
                            $tempLog = ['UserSp' => $user->sp_id];
                            $template = array_merge($template, $tempLog);
                        }
                        $data[] = $template;
                    }
                }
            } else {
                $templates = self::getAllTemplates();
                array_shift($templates); // Remove first element of array as it is returning an empty element
                foreach ($templates as $template) {
                    unset($template['TemplateText']);
                    $data[] = $template;
                }
            }
            usort($data, function ($a, $b) {
                return strcasecmp($b["Section"], $a["Section"]);
            });
            return ['data' => $data ];
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Get All Mail Boxes
     *
     * @return array Array with error or success message
     */
    public function getAllMailBoxes()
    {
        try {
            $response = $this
                        ->client
                        ->ws_no_reply_emails_init('GetAllMailBoxesasJSON')
                        ->GetAllMailBoxesasJSON();
            return json_decode($response->GetAllMailBoxesasJSONResult, true);
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Send Email to Client
     *
     * @param arrya $email_data Array with email information such as attachments, message, subject, to...
     * @return array Array with error or success message
     */
    public function sendEmail($email_data)
    {
        try {
            // Current time
            $date_now  = date("Y-m-d");
            $time_now  = date("H:i:s");
            $date_time = $date_now . "T" . $time_now;
            $files = [];

            $attachment_index = 0;
            while (isset($email_data['attachment'.$attachment_index])) {
                $files[] = $email_data['attachment'.$attachment_index];
                $attachment_index++;
            }

            if (isset($email_data['mainAttachment'])) {
                $files[] = $email_data['mainAttachment'];
            }
            $attachments = self::attachFiles($files);
            $sp_name = '';
            $sp_contact = '';
            $suffix = '<br><hr>';

            if (auth()->user()->sp_id != 0) {
                $sp_obj = new ServiceProvider();
                $service_provider = $sp_obj->getServiceProviderByID(auth()->user()->sp_id);
                $service_provider = json_decode($sp_obj->getServiceProviderByID(auth()->user()->sp_id)['data'])[0];
                $sp_name = $service_provider->ServiceProviderName;
                $suffix .= '<em>If you wish to contact us, please do not reply to this message. Replies to this message will not be read or responded to.</em><br><br>';
                $sp_contact .= 'To contact us:<br><br>';
                $sp_contact .= $sp_name . '<br>';
                /* Temp disabled
                if ( $service_provider->ContactPhone != '#')
                {
                    $sp_contact .= $service_provider->ContactPhone . '<br>';
                }
                */
                if ($service_provider->ServiceProviderURL != '#') {
                    $sp_contact .= $service_provider->ServiceProviderURL . '<br>';
                }
                $suffix .= $sp_contact;
            }

            $prefix = '<em>This email was sent by ' . $sp_name . ' to ' . $email_data['to'] .  ' </em><br><em>Please do not reply to this email.</em><br><hr><br>';

            $suffix .= '<br><p classname = "orbitprefix" style="background: #f5f8fa; padding-top: 15px;box-sizing: border-box; color: #aeaeae; font-size: smaller; text-align: center; margin:0px">© 2019 '. ucfirst(config('app.name')) .'. All rights reserved.</p><p classname = "emailprefix" style=" background: #f5f8fa; padding: 15px;box-sizing: border-box; color: #74787e;line-height: 1.4; margin: 0px; font-size: small;">Disclaimer: The material in this email is a general guide only. It is not legal advice. The law changes all the time and the general information in this email may not always apply to your own situation. The information in this email has been carefully collected from reliable sources. The sender is not responsible for any mistakes or for any decisions you may make or action you may take based on the information in this email. Some links in this email may connect to websites maintained by third parties. The sender is not responsible for the accuracy or any other aspect of information contained in the third-party websites. This email is intended for the use of the person or organisation it is addressed to and must not be copied, forwarded or shared with anyone without the sender’s consent (agreement). If you are not the intended recipient (the person the email is addressed to), any use, sharing, forwarding or copying of this email and/or any attachments is strictly prohibited. If you received this e-mail by mistake, please let the sender know and please destroy the original email and its contents.</p><br><br>';

            $fromAddress = env('MAIL_FROM_ADDRESS', 'hello@example.com');

            if (Auth::user()->isClcUser()) {
                $fromAddress = env('MAIL_FROM_ADDRESS_CLC', 'hello@example.com');
            }

            // check the configuration parameters
            $configuration =  new  Configuration();
            $is_store_cls = $configuration->getConfigurationValueByKey(self::STORE_CLC_CLIENT_DATA);
            $is_cls = false;
            if (strtolower($is_store_cls) == 'true' && Auth::user()->isClcUser()) {
                $is_cls = true;
            }

            $email_data['message'] = $prefix . $email_data['message'] . $suffix;
            $info = [
                        'MessageObject' => [
                                                'Attachments' 	=> $attachments,
                                                'Body' 			=> $email_data['message'],
                                                'Deliverd' 		=> 1,
                                                'Error' 		=> 0,
                                                'FromAddress' 	=> $fromAddress,
                                                'PersonID' 		=> auth()->user()->id,
                                                'RefNo' 		=> 0,
                                                'Section' 		=> self::getSection(),
                                                'SentOn' 		=> $date_time,
                                                'Subject' 		=> filter_var($email_data['subject'], FILTER_SANITIZE_STRING),
                                                'ToAddress' 	=> filter_var($email_data['to'], FILTER_VALIDATE_EMAIL),
                                                'CreatedBy'		=> auth()->user()->name,
                                                'IsClc'			=> $is_cls,
                                            ],
                        'IsHTML'		=> true,
                    ];
            $email_data['attachments'] = $attachments;

            $response = $this
                        ->client
                        ->ws_no_reply_emails_init('SendEmailasJSON')
                        ->SendEmailasJSON($info);

            Mail::to(auth()->user()->email)->send(new NoReplyEmailMailable($email_data));

            return ['success' => 'success' , 'message' => 'The email was sent.'];
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Upload files to server and return BLOB and file name
     *
     * @param array $files Array of files to be added in Email
     * @return array Array of BLOBs and file names
     */
    public function attachFiles($files)
    {
        $attachments = [];

        foreach ($files as $file) {
            $handle = fopen($file->getPathName(), "rb");                  // Open the temp file

            $content = fread($handle, filesize($file->getPathName()));  // Read the temp file

            fclose($handle);

            $attachments[] = [ 'AttachmentBytes' => $content, 'FileName' => $file->getClientOriginalName() ];
        }
        return $attachments;
    }

    /**
     * Save Email template
     *
     * @param array $data Email Template information
     * @return array Array with error or success message
     */
    public function saveEmailTemplate($data)
    {
        try {
            // Current time
            $date_now  = date("Y-m-d");
            $time_now  = date("H:i:s");
            $date_time = $date_now . "T" . $time_now;

            $section = '';

            if (isset($data['Section']) && $data['Section'] != '' && !isset($data['all'])) {
                $section = $data['Section'];
            } elseif (isset($data['all']) && $data['all'] == 'on') {
                $section = 'All';
            } else {
                $section = self::getSection();
            }

            $template = [
                            'RefNo' 		=> $data['RefNo'],
                            'Created' 		=> $date_time,
                            'CreatedBy' 	=> auth()->user()->id,
                            'Name' 			=> $data['name'],
                            'Section' 		=> $section,
                            'Subject' 		=> $data['subject'],
                            'TemplateText' 	=> $data['template'],
                            'Updated' 		=> $date_time,
                            'UpdatedBy' 	=> auth()->user()->id,
                        ];

            $info = [ 'ObjectInstance' => $template ];
            $response = $this->client->ws_no_reply_emails_init('SaveEmailTemplate')->SaveEmailTemplate($info);
            // Save log
            $log = new Log();
            $log::record('SAVE', 'nre template', $data['RefNo'], $template);
            return ['success' => 'success' , 'message' => 'The template was saved.', 'data' => $response];
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Save From Address is a temporary function to test web services
     *
     * @return array Array with error or success message
     */
    public function saveFromAddress()
    {
        try {
            // Current time
            $date_now  = date("Y-m-d");
            $time_now  = date("H:i:s");
            $date_time = $date_now . "T" . $time_now;
            /*
            $address =  [
                                'RefNo' 		=> 0,
                                'Created' 		=> $date_time,
                                'CreatedBy' 	=> auth()->user()->name,
                                'Code' 			=> $data['code'],
                                'Value' 		=> $data['value'],
                                'Updated' 		=> $date_time,
                                'UpdatedBy' 	=> auth()->user()->name,
                           ];
            */
            $address =  [
                                'RefNo' 		=> 0,
                                'Created' 		=> $date_time,
                                'CreatedBy' 	=> auth()->user()->name,
                                'Code' 			=> 'test',
                                'Value' 		=> 'test@test.com',
                                'Updated' 		=> $date_time,
                                'UpdatedBy' 	=> auth()->user()->name,
                           ];

            $info = [ 'ObjectInstance' => $address ];
            $response = $this->client->ws_no_reply_emails_init('SaveFromAddress')->SaveFromAddress($info);
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Get Section (Department) of curre user
     * This function was modified to use Service provider instead of VLA Section
     * in order to make it CLC compatible
     *
     * @return string User section currently Service Provider
     */
    public function getSection()
    {
        $section = 'All';
        /*
           //Simple SAML user provides department attribute
           if ( session('login_vla_attributes') && isset( session('login_vla_attributes')['department'][0]) )
           {
               $section = session('login_vla_attributes')['department'][0];
           }
           else
           {		   	*/
        if (auth()->user()->sp_id != 0) {
            $sp_obj = new ServiceProvider();
            $service_provider = $sp_obj->getServiceProviderByID(auth()->user()->sp_id);

            if ($service_provider['data'] != '') {
                $sp_info = json_decode($service_provider['data']);
                $section =  substr($sp_info[0]->ServiceProviderName, 0, 50); //Shouldn't be longer than 50 Chars
            }
        }
        //}

        return $section;
    }

    /**
     * Get All Log Records for No Reply Emails
     * This is an specific log for NRE and not the same as ORBIT
     *
     * @return array Array with error or success message
     */
    public function getAllLogRecords()
    {
        try {
            $response = $this
                        ->client
                        ->ws_no_reply_emails_init('GetAllLogRecords')
                        ->GetAllLogRecords();
            $logs = json_decode(json_encode($response->GetAllLogRecordsResult->MailMessage), true);

            foreach ($logs as $key => $log) {
                $user = User::find($log['PersonID']);

                if (isset($user->name)) {
                    $tempLog = ['PersonName'=>$user->name];
                    $logs[$key] = array_merge($log, $tempLog);
                }
            }
            return ['data' => $logs ];
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Delete Email Template by ID
     *
     * @param integer $te_id Template ID
     * @return array Array with error or success message
     */
    public function deleteTemplate($te_id)
    {
        $info = [ 'RefNumber' => $te_id];
        $template_obj = self::getTemplateById($te_id);

        try {
            $response = $this->client->ws_no_reply_emails_init('DeleteTemplate')->DeleteTemplate($info);
            if ($response->DeleteTemplateResult) {
                // Save log
                $log = new Log();
                $log::record('DELETE', 'nre template', $te_id, $template_obj);
                return ['success' => 'success' , 'message' => 'NRE Template deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        } catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }
    /**
     * Sort the templates by section and group the templates.
     * @return array Templates in select2 format
     */
    public function getAllTemplatesFormatedBySection()
    {
        $templates = self::getAllTemplatesBySection()['data'];
        $clean_templates = [];
        foreach ($templates as $template) {
            array_push(
                $clean_templates,
                [
                            'id'   		=> $template['RefNo'],
                            'text' 		=> $template['Name'],
                            'section'	=> $template['Section'],
                        ]
                      );
        }

        $templates = [];
        foreach ($clean_templates as $key => $value) {
            $templates[ $value['section'] ][]	= [
                                                    'id' 	=> $value['id'],
                                                    'text' 	=> $value['text'],
                                                  ];
        }

        $output = [];
        $generalTemplates = [];

        foreach ($templates as $key => $value) {
            $text = (strtoupper($key) == 'ALL' ? 'General Templates':$key .' Templates');
            usort($value, function ($a, $b) {
                return strcasecmp(strtoupper($a["text"]), strtoupper($b["text"]));
            });

            if (strtoupper($key) != 'ALL') {
                $output[] = ['text' => $text, 'children' => $value];
            } else {
                $generalTemplates = ['text' => $text, 'children' => $value];
            }
        }
        // insert General Templates at the end of the output array
        $output[] = $generalTemplates;
        return $output;
    }

    /**
     * Get All Sent Emails by section
     * @return array send emails filtered by section
     */
    public function getAllLogRecordBySection()
    {
        try {
            $section = self::getSection();
            $response = $this
                        ->client
                        ->ws_no_reply_emails_init('GetAllLogRecords')
                        ->GetAllLogRecords();

            $logs = json_decode(json_encode($response->GetAllLogRecordsResult->MailMessage), true);
            $result = [];
            $is_admin = in_array(\App\Http\helpers::getRole(), ['Administrator']);

            foreach ($logs as $key => $log) {
                $user = User::find($log['PersonID']);

                if (isset($user->name)) {
                    $tempLog = ['PersonName'=>$user->name];
                    $logs[$key] = array_merge($log, $tempLog);
                }

                if ($is_admin) {
                    $result[] = $logs[$key];
                } elseif ($log['Section'] == $section) {
                    $result[] = $logs[$key];
                }
            }
            return ['data' => $result ];
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Get All Sent Emails by section using a pager
     * @return array send emails filtered by section
     */
    public function getAllLogRecordBySectionPager($request)
    {
        $column = '';
        $replace = ["/Date(", ")/"];
        $date = new DateTime();
        try {
            $column = self::mapTableColumn($request['column']);

            $args = [
                'PerPage' 		=> $request['per_page'],
                'Page' 			=> $request['page'] - 1,
                'SortColumn' 	=> $column,
                'SortOrder' 	=> $request['order'] ,
                'Search' 		=> $request['search'] ,
                'ColumnSearch' 	=> '' ,
            ];
            $section = self::getSection();
            $response = $this
                        ->client
                        ->ws_no_reply_emails_init('GetAllLogRecordsInBatchasJSON')
                        ->GetAllLogRecordsInBatchasJSON($args);
            $logs = json_decode($response->GetAllLogRecordsInBatchasJSONResult, true);
            $result = [];
            $data = $logs['data'];
            $is_admin = in_array(\App\Http\helpers::getRole(), ['Administrator']);
            foreach ($data as $key => $record) {
                $user = User::find($record['PersonID']);
                $record_date = str_replace($replace, "", $data[$key]['SentOn']);
                $date->setTimestamp(intval(substr($record_date, 0, 10)));
                $date_formatted_hour = $date->format('d/m/Y g:i A');
                $person_name ='';
                if ($is_admin || $record['Section'] == $section) {
                    if (isset($user->name)) {
                        $person_name = $user->name;
                    }
                    $result[] = [
                                    "id" 			     => $data[$key]['RefNo'],
                                    "sent_by" 			 => $person_name,
                                    "to" 				 => $data[$key]['ToAddress'],
                                    "subject" 			 => $data[$key]['Subject'],
                                    "sent_date_and_time" => $date_formatted_hour,
                                ];
                }
            }
            $logs['data'] = $result;
            return $logs;
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    public function updateName($page)
    {
        try {
            $start = microtime(true);
            $args_page = [
                'PerPage' 		=> 1000,
                'Page' 			=> $page,
                'SortColumn' 	=> 'VML_REF_NO',
                'SortOrder' 	=> 'ASC' ,
                'Search' 		=> '',
                'ColumnSearch' 	=> '',
            ];

            $response_search = $this
                    ->client
                    ->ws_no_reply_emails_init('GetAllLogRecordsInBatchasJSON')
                    ->GetAllLogRecordsInBatchasJSON($args_page);

            $data = json_decode($response_search->GetAllLogRecordsInBatchasJSONResult, true);

            $logs = $data['data'];
            foreach ($logs as $key => $log) {
                $id = $log['RefNo'];
                $user = User::find($log['PersonID']);
                if (isset($user->name)) {
                    $args = [
                        'Id' 		=> $log['RefNo'],
                        'Name' 		=> $user->name,
                    ];
                    $response = $this
                                ->client
                                ->ws_no_reply_emails_init('UpdateEmailsName')
                                ->UpdateEmailsName($args);
                }
            }
            $time  = microtime(true) - $start;
            Logs::info('Time with the round #' . $page .' of ' . $data['last_page'] . " " . $time);


            return "Finish";
        } catch (Exception $e) {
            return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
        }
    }

    /**
     * Map the column from the datatable with the columns of the database
     *
     * @param string $column
     * @return void
     */
    private function mapTableColumn($column)
    {
        $result = '';
        if ($column == 'id') {
            $result = 'VML_REF_NO';
        }
        if ($column == 'sent_by') {
            $result = 'VML_REF_NO';
        }
        if ($column == 'to') {
            $result = 'VML_TO_ADDRESS';
        }
        if ($column == 'subject') {
            $result = 'VML_SUBJECT';
        }
        if ($column == 'sent_date_and_time') {
            $result = 'VML_TIMESTAMP';
        }
        return $result;
    }
}
