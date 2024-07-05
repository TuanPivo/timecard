@extends('layout.index')
@section('content')
    <style>
        .fc .fc-toolbar.fc-header-toolbar {
            padding: 5px;
        }
    </style>

    @include('layout.message')

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h5>Show Leave Request of: 
                        @if (Auth::check())
                           {{ Auth::user()->name }}
                        @endif
                    </h5>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="#" class="btn btn-primary" id="createLeaveRequestButton">Create Leave Request</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card-footer">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal for Leave Request Form -->
    <div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveRequestModalLabel">Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveRequestForm" action="{{ route('leave_requests.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('customJs')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                height: 500,
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('leave_requests.getLeaveRequest') }}',
                    method: 'GET',
                    success: function(data) {
                        console.log('Events data:', data); // Log the events data here
                    },
                },
                eventContent: function(arg) {
                    var status = arg.event.extendedProps.status;
                    var containerEl = document.createElement('div');
                    containerEl.style.color = '#fff';

                    // Thiết lập màu sắc dựa trên status
                    switch (status) {
                        case 'approved':
                            containerEl.style.backgroundColor = '#2eb85c';
                            break;
                        case 'pending':
                            containerEl.style.backgroundColor = '#f9b115';
                            break;
                        case 'rejected':
                            containerEl.style.backgroundColor = '#e55353';
                            break;
                        default:
                            break;
                    }

                    var titleEl = document.createElement('div');
                    titleEl.textContent = arg.event.title;
                    containerEl.appendChild(titleEl);

                    var startEl = document.createElement('div');
                    startEl.textContent = "Start: " + new Date(arg.event.start).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    containerEl.appendChild(startEl);

                    var endEl = document.createElement('div');
                    endEl.textContent = "End: " + new Date(arg.event.end).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    containerEl.appendChild(endEl);

                    return { domNodes: [containerEl] };
                },
                dateClick: function(info) {
                    document.getElementById('start_date').value = info.dateStr;
                    var leaveRequestModal = new bootstrap.Modal(document.getElementById('leaveRequestModal'));
                    leaveRequestModal.show();
                },
                dayHeaderContent: function(arg) {
                    var date = arg.date;
                    var day = date.getUTCDay();
                    var text = arg.text;

                    if (day === 0 || day === 6) { // 0: Sunday, 6: Saturday
                        return { html: '<span style="color: red;">' + text + '</span>' };
                    } else {
                        return { html: '<span>' + text + '</span>' };
                    }
                },
                dayCellDidMount: function(info) {
                    var day = info.date.getDay();
                    if (day === 0 || day === 6) { // 0: Sunday, 6: Saturday
                        info.el.style.backgroundColor = 'rgba(216 216 216 / 20%)';
                    }
                },
            });

            // Xử lý gửi form và nạp lại lịch sau khi gửi thành công
            document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
                e.preventDefault();
                var form = this;
                var formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        form.reset();
                        var leaveRequestModal = bootstrap.Modal.getInstance(document.getElementById('leaveRequestModal'));
                        leaveRequestModal.hide();
                        calendar.refetchEvents();
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            calendar.render();
        });

        document.getElementById('createLeaveRequestButton').addEventListener('click', function() {
            var leaveRequestModal = new bootstrap.Modal(document.getElementById('leaveRequestModal'));
            leaveRequestModal.show();
        });
    </script>

@endsection
