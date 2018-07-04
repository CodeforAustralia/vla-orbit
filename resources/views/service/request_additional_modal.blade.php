<div id="request_section">
    <div id="request-matter" class="modal fade modal-scroll" tabindex="-1" data-replace="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Request Legal Matter</h4>
                </div>

                <div class="modal-body" style="display: grid;">

                    <span>Use the form below the request a new legal matter to be added to ORBIT.</span><br>
                    <span>For a full list of existing matters <a target="_blank" href="https://docs.google.com/spreadsheets/d/1L-pg_KxX9WP29CsE--3OvgyFE8BjL7CmgPBFHBw4-KM/view">click here</a>.</span>

                    <div class="form-group  margin-top-10">
                        <div class="col-sm-12">
                            <label for="matter_name"><small>Specific legal matter:</small></label>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="matter_name" name="matter_name" v-model="matter_form.matter_name" placeholder="Name of legal matter">
                            <span class="help-block small font-red-thunderbird" v-if="matter_form.errors.has('matter_name')" v-text="matter_form.errors.get('matter_name')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 margin-top-10">
                            <label for="matter_name"><small>Area of law:</small></label>
                        </div>
                        <div class="col-sm-12">
                                <select class="form-control" id="parent_matter" name="parent_matter" v-model="matter_form.parent_matter" aria-placeholder="Choose from list">
                                    <option v-for="parent_matter in parent_matters" v-bind:value="parent_matter.text" v-html="parent_matter.text">
                                    </option>
                                </select>
                                <span class="help-block small font-red-thunderbird" v-if="matter_form.errors.has('parent_matter')" v-text="matter_form.errors.get('parent_matter')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 margin-top-10">
                            <label for="matter_name"><small>Reason:</small></label>
                        </div>
                        <div class="col-sm-12">
                            <textarea class="form-control" rows="5" id="reason" name="reason" v-model="matter_form.reason" placeholder="Brief the reason of why the matter is needed"></textarea>
                            <span class="help-block small font-red-thunderbird" v-if="matter_form.errors.has('reason')" v-text="matter_form.errors.get('reason')"></span>
                        </div>
                    </div>

                    <div class="form-group margin-top-10">
                        <div class="col-sm-12 margin-top-10">
                            <button class="btn green pull-right" id="request-matter" v-on:click="request_matter">Submit</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div id="request-vulnerability" class="modal fade modal-scroll" tabindex="-1" data-replace="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Request Eligibility Criteria</h4>
                </div>

                <div class="modal-body" style="display: grid;">

                    <span>Use the form below the request a new eligibility criteria to be added to ORBIT.</span><br>

                    <div class="form-group  margin-top-10">
                        <div class="col-sm-12">
                            <label for="criteria_name"><small>Specific Criteria:</small></label>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="criteria_name" name="criteria_name" v-model="vulnerability_form.criteria_name" placeholder="Name of criteria">
                            <span class="help-block small font-red-thunderbird" v-if="vulnerability_form.errors.has('criteria_name')" v-text="vulnerability_form.errors.get('criteria_name')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 margin-top-10">
                            <label for="criteria_group"><small>Criteria group:</small></label>
                        </div>
                        <div class="col-sm-12">
                            <select class="form-control" id="criteria_group" name="criteria_group" v-model="vulnerability_form.criteria_group" aria-placeholder="Choose from list">
                                <option v-for="question_group in question_groups" v-bind:value="question_group.GroupName" v-html="question_group.GroupName">
                                </option>
                                <option value="other">Other</option>
                            </select>
                            <span class="help-block small font-red-thunderbird" v-if="vulnerability_form.errors.has('criteria_group')" v-text="vulnerability_form.errors.get('criteria_group')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 margin-top-10">
                            <label for="reason"><small>Reason:</small></label>
                        </div>
                        <div class="col-sm-12">
                            <textarea class="form-control" rows="5" id="reason" name="reason" v-model="vulnerability_form.reason" placeholder="Brief description of why the criteria is needed"></textarea>
                            <span class="help-block small font-red-thunderbird" v-if="vulnerability_form.errors.has('reason')" v-text="vulnerability_form.errors.get('reason')"></span>
                        </div>
                    </div>

                    <div class="form-group margin-top-10">
                        <div class="col-sm-12 margin-top-10">
                            <button class="btn green pull-right" id="request-matter" v-on:click="request_vulnerability">Submit</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
