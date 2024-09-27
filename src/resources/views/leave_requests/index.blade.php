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
                    @if (Auth::check())
                        <h5>Leave Requests Of: {{ Auth::user()->name }}</h5>
                    @endif
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('leave_requests.list') }}" class="btn btn-primary">My Requests</a>
                    <a href="#" class="btn btn-primary" id="createLeaveRequestButton">Create Request</a>
                </div>
            </div>
        </div>
        <div class="card-body">
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
                                    <label for="title" class="form-label">Title</label>
                                    <select class="form-select" id="title" name="title" required>
                                        <option value="Take off">Take off</option>
                                        <option value="Come late">Come late</option>
                                        <option value="Go home early">Go home early</option>
                                        <option value="WFH">WFH</option>
                                        <option value="Go out">Go out</option>
                                    </select>
                                </div>
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
        </div>
        <div class="card-footer">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        // check input start_date and end_date
        document.addEventListener('DOMContentLoaded', function() {
            var startDateInput = document.getElementById('start_date');
            var endDateInput = document.getElementById('end_date');

            function validateDates() {
                var startDate = new Date(startDateInput.value);
                var endDate = new Date(endDateInput.value);

                if (startDate >= endDate) {
                    endDateInput.setCustomValidity('End date must be later than start date.');
                } else {
                    endDateInput.setCustomValidity('');
                }
            }

            startDateInput.addEventListener('input', validateDates);
            endDateInput.addEventListener('input', validateDates);
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                height: 500,
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('leave_requests.getLeaveRequest') }}',
                    method: 'GET',
                    success: function(data) {
                        console.log('Events data:', data);
                    }
                },
                eventContent: function(arg) {
                    var {status, title, reason} = arg.event.extendedProps;
                    var containerEl = document.createElement('div');
                    containerEl.style.color = '#fff';
                    containerEl.style.backgroundColor = getBackgroundColor(status);

                    var titleEl = document.createElement('div');
                    titleEl.textContent = status ? "Request: " + arg.event.title : arg.event.title;
                    containerEl.appendChild(titleEl);

                    if (status) {
                        var startEl = document.createElement('div');
                        startEl.textContent = "Start Time: " + formatDate(arg.event.start);
                        containerEl.appendChild(startEl);

                        var endEl = document.createElement('div');
                        endEl.textContent = "End Time: " + formatDate(arg.event.end);
                        containerEl.appendChild(endEl);

                        var reasonEl = document.createElement('div');
                        reasonEl.textContent = "Reason: " + (reason ? reason : "undefined");
                        containerEl.appendChild(reasonEl);
                    }

                    return {
                        domNodes: [containerEl]
                    };
                },
                dateClick: function(info) {
                    document.getElementById('start_date').value = info.dateStr;
                    var leaveRequestModal = new bootstrap.Modal(document.getElementById(
                        'leaveRequestModal'));
                    leaveRequestModal.show();
                },
                dayHeaderContent: function(arg) {
                    var {date, text} = arg;
                    var day = date.getUTCDay();
                    var color = (day === 0 || day === 6) ? 'red' : 'black';
                    return {
                        html: `<span style="color: ${color};">${text}</span>`
                    };
                },
                dayCellDidMount: function(info) {
                    var day = info.date.getDay();
                    if (day === 0 || day === 6) {
                        info.el.style.backgroundColor = 'rgba(245, 245, 245, 1)';
                    }
                }
            });

            function getBackgroundColor(status) {
                switch (status) {
                    case 'approved':
                        return '#2eb85c';
                    case 'pending':
                        return '#f9b115';
                    case 'rejected':
                        return '#e55353';
                    default:
                        // return '#007bff';
                        return '#FF0000';
                }
            }

            function formatDate(date) {
                return new Date(date).toLocaleString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Xử lý gửi form và nạp lại lịch sau khi gửi thành công
            document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
                e.preventDefault();
                var form = this;
                var formData = new FormData(form);

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

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
                            window.location.href = '{{ route('leave_requests.index') }}';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            calendar.render();

            // search month or year
            var toolbarChunks = calendar.el.querySelectorAll('.fc-toolbar-chunk');
            if (toolbarChunks.length > 0) {
                var middleChunk = toolbarChunks[Math.floor(toolbarChunks.length / 2)];
                if (middleChunk) {
                    var centerElement = document.createElement('div');
                    centerElement.classList.add('d-flex', 'align-items-center', 'justify-content-center');
                    centerElement.innerHTML = `
                        <label for="monthPicker" class="form-label me-2">Select Month:</label>
                        <select id="monthPicker" class="form-select w-auto me-2">
                            <option value="0">January</option>
                            <option value="1">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                            <option value="5">June</option>
                            <option value="6">July</option>
                            <option value="7">August</option>
                            <option value="8">September</option>
                            <option value="9">October</option>
                            <option value="10">November</option>
                            <option value="11">December</option>
                        </select>
                        <label for="yearPicker" class="form-label me-2">Select Year:</label>
                        <select id="yearPicker" class="form-select w-auto me-2"></select>
                    `;
                    middleChunk.appendChild(centerElement);
                }
            }

            var yearPicker = document.getElementById('yearPicker');
            var currentYear = new Date().getFullYear();
            for (var i = currentYear - 10; i <= currentYear + 10; i++) {
                var option = document.createElement('option');
                option.value = i;
                option.text = i;
                yearPicker.appendChild(option);
            }
            document.getElementById('monthPicker').value = new Date().getMonth();
            document.getElementById('yearPicker').value = currentYear;

            document.getElementById('monthPicker').addEventListener('change', function() {
                search();
            });

            document.getElementById('yearPicker').addEventListener('change', function() {
                search();
            });

            function search() {
                var selectedMonth = document.getElementById('monthPicker').value;
                var selectedYear = document.getElementById('yearPicker').value;

                if (selectedMonth && selectedYear) {
                    var newDate = new Date(selectedYear, selectedMonth, 1);
                    calendar.gotoDate(newDate);
                }
            }
        });

        document.getElementById('createLeaveRequestButton').addEventListener('click', function() {
            var leaveRequestModal = new bootstrap.Modal(document.getElementById('leaveRequestModal'));
            leaveRequestModal.show();
        });
    </script>
@endsection
