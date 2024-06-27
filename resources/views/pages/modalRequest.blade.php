 <!-- Modal -->
 <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel"
     aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="attendanceModalLabel">Request form</h5>
                 <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form id="attendanceForm">
                     @csrf
                     <div class="form-group">
                         <label for="attendanceType">Type:</label>
                         <select class="form-control" id="attendanceType" name="type">
                             <option value="check in">Check In</option>
                             <option value="check out">Check Out</option>
                         </select>
                     </div>
                     <div class="form-group">
                         <label for="attendanceDate">Date:</label>
                         <div class="input-group date">
                             <input type="datetime-local" class="form-control" id="attendanceDate" name="date">
                         </div>
                         <div class="text-danger" id="errorDate"></div>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                 <button type="button" id="saveAttendanceBtn" class="btn btn-danger">Send</button>
             </div>
         </div>
     </div>
 </div>
