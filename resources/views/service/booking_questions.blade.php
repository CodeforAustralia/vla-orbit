<div class="margin-top-10">
    <!-- Section title -->
    <!-- end Section title -->

    <!-- Section modal button -->
    <div>
        <label class="font-grey-silver tooltips" data-container="body" data-placement="right" data-original-title="If you have updated your booking questions please Save first">Add a condition for intake to the service</label>
        <br>
        <a class=" btn dark btn-outline sbold tooltips" data-toggle="modal" href="#bookingQuestions"  data-container="body" data-placement="right" data-original-title="If you have updated your booking questions please Save first"> Add Question </a>
    </div>
    <!-- end Section modal button -->

    <!-- modal container -->
    <div id="bookingQuestions" class="modal fade modal-scroll" tabindex="-1" data-replace="true">
        <!-- modal dialog -->
        <div class="modal-dialog modal-lg">
            <!-- modal content -->
            <div class="modal-content">
                <!-- modal header -->
                <div class="modal-header">
                    <h4 class="modal-title">Intake question</h4>
                </div>
                <!-- end modal header -->
                <!-- modal body -->
                <div class="modal-body" style="display: grid;">
                    <!-- Tab Content -->
                    <div class="tab-content">
                        <p>Only questions with answers will be displayed to users during the intake process</p>
                        <br>
                        <!-- Questions container -->
                        @include ('service.booking_questions_list')
                        <!-- end Questions container -->
                    </div>
                    <!-- End Tab Content -->
                </div>
                <!-- end modal body -->
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                </div>
            </div>
            <!-- end modal content -->
        </div>
        <!-- end modal dialog -->
    </div>
    <!-- end modal container -->
</div>