<div class="row" v-show="!send_to_client">
	<input type="hidden" id="user_service_provicer" value="{{Auth::user()->sp_id}}">

	<div class="col-sm-12 alert alert-warning" v-if="!can_refer">
			<p align="justify" > You do not have permission to refer to this service.</p>
	</div>

	<div class="col-sm-6 col-xs-8">
		<h4 class="col-sm-5 bold">Contact Details</h4>
	</div>

	<div class="col-sm-6 col-xs-4">
		<button v-if="can_refer" type="button" class="btn btn-primary pull-right btn-sm" id="send_client_btn" @click="setReferral({{ Auth::user()->id }})">
			Send to Client
		</button>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Phone number </label>
    	<span id="phoneNumber" v-html="service.Phone"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Email </label>
    	<span id="email" v-html="service.Email"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Location </label>
    	<span id="location" v-html="service.Location"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Website </label>
    	<span id="url" v-html="service.URL"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Opening Hours </label>
    	<span id="opening_hours" v-html="service.OpenningHrs"></span>
	</div>

	<div class="col-sm-12">
		<h4 class="col-sm-5 bold">Service Details</h4>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Service Provider </label>
    	<span id="service_provider" v-html="service.ServiceProviderName"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Service Level </label>
    	<span id="service_level" v-html="service.ServiceLevelName"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Service Type </label>
    	<span id="service_type" v-html="service.ServiceTypeName"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Wait Time  </label>
    	<span id="wait_time" v-html="service.Wait"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Description </label>
    	<span class="col-sm-6 padding-0" id="description" v-html="service.Description"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Legal Matters </label>
    	<span class="col-sm-6 padding-0" id="legal_matters" v-html="legal_matters"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Eligibility Criteria </label>
    	<span class="col-sm-6 padding-0" id="eligibility_criteria" v-html="eligibility_criteria"></span>
	</div>

	<div class="col-sm-12">
		<label class="col-sm-5 bold">Catchment Area </label>
    	<span class="col-sm-6 padding-0" id="catchment_area" v-html="catchment_area"></span>
	</div>

	<div class="col-sm-12 modal-footer padding-top-10 padding-bottom-0">
		<h6 class="col-sm-9" id="updated-by-date"  v-html="updated_by_date"></h6>
	</div>

</div>
<div class="row" v-show="send_to_client">
	<!-- TODO Logo -->
	<div class="col-xs-6 col-sm-4">
		<img :src="logo_src" id="logo_sp" class="img-responsive img-thumbnail center-block">
	</div>
	<!-- Service & SP -->
	<div class="col-xs-6 col-sm-8">
		<h3 class="service-name" v-html="service.ServiceName"></h3>
		<h4 class="service-provider-name" v-html="service.ServiceProviderName"></h4>
		<p>Send referral details to the client by Email, SMS or both with the form below.</p>
	</div>
</div>
<div class="row" v-show="send_to_client" >
	<div class="col-sm-12 margin-top-10">
		<label>
			<input type="checkbox" v-model="email_checked" id="safeEmail"> It is safe to send an email to the client
			<i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="Please be mindful of any family violence risks; eg if this email account is accessible by others."></i>
		</label>
		<label class="sr-only" for="Email">Email Address</label>
		<input type="email" class="form-control" autocomplete="off" v-model='email' id="Email" placeholder="Client email address">
		<div class="col-xs-11 col-xs-offset-1">
			<div class="form-group">
				<div class="checkbox">
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<label>
			<input type="checkbox" v-model="phone_checked" id="safePhone"> It is safe to send an SMS to the client
			<i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="Please be mindful of any family violence risks; eg if the SMS or an SMS notification can be viewed by others."></i>
		</label>
		<label class="sr-only" for="Phone">Phone Number</label >
		<input type="tel" class="form-control"  id="Phone" v-model='phone' autocomplete="off" placeholder="Client mobile number">

		<div class="col-xs-11 col-xs-offset-1">
			<div class="form-group">
				<div class="checkbox">
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12 padding-0">
		<label class="col-sm-12">Legal Matter: </label>
		<div class="col-sm-5">
			<multiselect
			v-model="legal_matter_selected"
			id="service_matters"
			placeholder="Select"
			open-direction="bottom"
			label="MatterName"
			:options="matter_options"
			:multiple="false"
			:searchable="true"
			:close-on-select="true"
			:show-no-results="false"
			:show-labels="false"
			:allow-empty="false"
			>
			</multiselect>
		</div>
	</div>
	<div class="col-sm-12" v-show="legal_matter_selected.MatterName === 'Other'">
		<br>
		<input type="text" class="col-sm-12 form-control" v-model='other' v-show="legal_matter_selected.MatterName === 'Other'" id="other_lm" autocomplete="off" placeholder="Please type the Legal Matter">
	</div>

	<div class="col-sm-12 margin-top-15" v-if='service.Notes != ""'>
		<label for="Anotations">Read this before referring a client</label>
		<ul class="feeds referral_feed">
		<li>
			<div class="col1">
				<div class="cont">
					<div class="cont-col2">
						<div class="desc" v-html="service.Notes">
						</div>
					</div>
				</div>
			</div>
			<div class="col2">
				<div class="date">  </div>
			</div>
		</li>
		</ul>
	</div>

	<div class="col-sm-12 form-group">
		<br>
		<button type="button" @click="sendToClient" class="btn green-jungle btn-sm" id="send-client">Send to Client</button>
		<button type="button"  @click="clearFields" class="btn btn-secondary btn-sm" id="cancel_send">Cancel</button>
	</div>

</div>