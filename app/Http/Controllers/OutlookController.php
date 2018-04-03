<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Microsoft\Graph\Graph;

use Microsoft\Graph\Model;



class OutlookController extends Controller
{
  public function mail() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  
    $tokenCache = new \App\TokenStore\TokenCache;
  
    $graph = new Graph();
    $graph->setAccessToken($tokenCache->getAccessToken());
  
    $user = $graph->createRequest('GET', '/me')
                  ->setReturnType(Model\User::class)
                  ->execute();
  
    //echo 'User: '.$user->getDisplayName().' - '.$user->getMail().'<br/>';
  
    $messageQueryParams = array (
      // Only return Subject, ReceivedDateTime, and From fields
      "\$select" => "subject,receivedDateTime,from",
      // Sort by ReceivedDateTime, newest first
      "\$orderby" => "receivedDateTime DESC",
      // Return at most 10 results
      "\$top" => "10"
    );
  
    $getMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
    $messages = $graph->createRequest('GET', $getMessagesUrl)
                      ->addHeaders(array ('X-AnchorMailbox' => $user->getMail()))
                      ->setReturnType(Model\Message::class)
                      ->execute();
    /*
    foreach($messages as $msg) {
      echo 'Message: '.$msg->getSubject().'<br/>';
    }
    */
  }
}