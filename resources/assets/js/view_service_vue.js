import Vue from 'vue';
import axios from 'axios';
import moment from 'moment';
import Multiselect from 'vue-multiselect';

Vue.component('multiselect', Multiselect);

new Vue({
    el: '#viewService',
    data: {
        service: [],
        legal_matters:'',
        eligibility_criteria : '',
        catchment_area:'',
        updated_by_date:'',
        send_to_client: false,
        email_checked:false,
        phone_checked: false,
        legal_matter_selected: {},
        matter_options: [],
        email: '',
        phone:'',
        other:'',
        outSP:'',
        user_id:'',
        title: '',
        logo_src:''
    },
    methods: {
        getServiceById : function(sv_id) {
            $("#contentLoading").modal("show");
            let self = this;
            let url = "/service/list_service_by_id/" + sv_id
            axios['get'](url, {})
                .then(response => {
                    self.service = response.data;
                    self.setServiceInformation();
                    $('#viewService').modal('show')
                    $("#contentLoading").modal("hide");
                })
                .catch(error => {
                    console.log("Error ", error);
                    $("#contentLoading").modal("hide");
                    alert('Something went wrong, please refresh the page.');
                });
        },
        setServiceInformation : function()
        {
            let self = this;
            self.matter_options = self.service.ServiceMatters;
            self.matter_options.push({MatterID: 0, MatterName:"Other"});
            self.title = self.service.ServiceName;
            for( var i = 0 ; i < self.service.ServiceMatters.length ; i++ )
            {
                self.legal_matters += '<span class="badge badge-info badge-roundless">' + self.service.ServiceMatters[i].MatterName + '</span> ';
            }
            for( var i = 0 ; i < self.service.vulnerabilities.length ; i++ )
            {
                self.eligibility_criteria = '<span class="badge badge-success badge-roundless">' + self.service.vulnerabilities[i] + '</span> ';
            }
            for( var i = 0 ; i < self.service.catchments.length ; i++ )
            {
                self.catchment_area = '<span class="badge badge-primary badge-roundless">' + self.service.catchments[i] + '</span> ';
            }

            $('#status').prop('disabled',false);
            $('#status').prop('checked',(self.service.Status!=0) ? true:false).change();
            $('#status').prop('disabled',true);
            let last_update = moment(self.service.UpdatedOn).toDate();
            last_update = moment(last_update).format('DD/MM/YYYY');
            self.updated_by_date ='Last update ' + last_update + ' by ' + self.service.UpdatedBy;
        },
        onClickView : function()
        {
            let self = this;
            $(document).on("click", ".service_data_table .view-btn", function () {
                let view = $(this).attr('id').split('-');
                let sv_id = view[1];
                self.getServiceById(sv_id);
            });
        },
        clearFields : function ()
        {
            let self = this;
            self.send_to_client = false;
            self.email_checked=false;
            self.phone_checked= false;
            self.legal_matter_selected = {};
            self.email= '';
            self.phone='';
            self.other='';
            self.title = self.service.ServiceName;
            self.logo_sp = '';
        },
        sendToClient: function()
        {
            let self = this;
            let url = '/referral';
            if(!self.email_checked && !self.phone_checked ) {
                swal("Alert", "Please provide an email and/or phone", "warning");
            } else if (Object.entries(self.legal_matter_selected).length === 0 && self.legal_matter_selected.constructor === Object) {
                swal("Alert", "Please select a legal matter", "warning");
            } else if((self.email_checked && self.email.length === 0) || (self.email && !self.isEmail(self.email))) {
                swal("Alert", "Please provide a valid email", "warning");
            } else if (self.email.length != 0 && !self.email_checked){
                swal("Alert", "Please select if is safe send the email to the email address provided", "warning");
            }else if(self.phone_checked && self.phone.length === 0) {
                swal("Alert", "Please enter a mobile number", "warning");
            } else if(self.phone.length != 0 && !self.phone_checked ) {
                swal("Alert", "Please select if is safe send the sms to the number provided", "warning");
            }else if(self.legal_matter_selected.MatterName === "Other" && self.other.length === 0) {
                swal("Alert", "Please specify the legal matter", "warning");
            } else {
                $("#contentLoading").modal("show");
                self.phone = self.phone.replace(/[^\d]/g, '');
                let data = {
                    Mobile: self.phone,
                    Email: self.email,
                    SafeMobile: self.phone_checked ? "1" : "0",
                    SafeEmail: self.email_checked ? "1" : "0",
                    ServiceId: self.service.ServiceId.toString(),
                    CatchmentId: "0",
                    MatterId: self.legal_matter_selected.MatterID.toString(),
                    UserID: self.user_id.toString(),
                    OutboundServiceProviderId: self.outSP.toString(),
                    Notes : self.other,
                    Nearest: null
                }
                axios['post'](url, data)
                .then(response => {
                    if(response.data.success == 'success') {
                        self.clearFields();
                        swal("Success", "Referral sent to client, ID# " + response.data.data, "success");
                        $("#contentLoading").modal("hide");
                    }
                    else {
                        swal("Error", "Error: " + response.data.message, "warning");
                    }
                })
                .catch(error => {
                    console.log("Error ", error);
                    $("#contentLoading").modal("hide");
                    alert('Something went wrong, please refresh the page.');
                });
            }

        },
        setReferral: function (user_id, out_sp)
        {
            let self = this;
            self.user_id = user_id;
            self.outSP = out_sp;
            self.send_to_client = true;
            self.title = 'Send Referral';
            let url = '/service_provider/getById/'+ self.service.ServiceProviderId;
            $("#contentLoading").modal("show");
            axios['get'](url, {})
            .then(response => {
                self.logo_src = response.data.sp.ServiceProviderLogo;
                $("#contentLoading").modal("hide");
            })
            .catch(error => {
                console.log("Error ", error);
                $("#contentLoading").modal("hide");
                alert('Something went wrong, please refresh the page.');
            });
        },
        isEmail:function (email)
        {
            let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }

    },
    mounted() {
        this.onClickView();
    },
});