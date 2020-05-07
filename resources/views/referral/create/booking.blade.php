
  <!-- Modal Start -->
  <div class="modal fade" id="SelectBooking" tabindex="-1" role="dialog" aria-labelledby="SelectBookingLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="SelectBookingLabel">Bookings and e-referrals</h4>
        </div>
        <!-- Modal Body -->
        <div class="modal-body">
          <div class="container-fluid">
                @include("booking.form_vue")
          </div> <!-- Modal Container -->

        </div> <!-- Modal Body Close-->
      </div><!-- Modal Content Close-->
    </div><!-- Modal Dialogue Close-->
  </div><!-- Modal Fade Close-->