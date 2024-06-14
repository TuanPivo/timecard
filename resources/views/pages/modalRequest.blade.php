 <!-- Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Request form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="attendanceForm">
                        @csrf
                        <div class="form-group">
                            <label for="attendanceType">Loại:</label>
                            <select class="form-control" id="attendanceType" name="type">
                                <option value="check in">Check In</option>
                                <option value="check out">Check Out</option>
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="attendanceDate">Ngày:</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="date" class="form-control" id="attendanceDate" name="date">
                            </div>
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" id="saveAttendanceBtn" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>