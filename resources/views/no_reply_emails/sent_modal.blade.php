<div class="row" id="result" >
  <div class="col-xs-12 text-center">
    <p style="font-size: 126px;"><i class="fa fa-check-circle" style="color: #5cb85c;background-color: #fff;"></i></p>
    <h3><strong>No-reply email sent to client</strong></h3>
    <!--<h3><strong>ID: #</strong><span id="no_reply_emails_id"></span></h3><br>!-->
    <button type="button" class="btn blue btn-lg" id="send_another">Send another email</button>
    @if ( Request::is('referral/create/result') ? 'active' : null )
    <button type="button" class="btn green-jungle btn-lg" id="return">Return to matches</button><br><br><br><br>
    @else
    <button type="button" class="btn green-jungle btn-lg" id="start_over">Start Over</button><br><br><br><br>
    @endif
  </div>
</div>