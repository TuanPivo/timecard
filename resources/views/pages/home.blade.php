@extends('layout.index')
@section('content')
    <style>
        .btn-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            width: 50%;
        }

        /* .btn-container .btn {
                        flex: 1;
                        margin: 5px;
                    }
                    .fc-day-sat .fc-daygrid-day-frame {
                        background-color: rgb(171, 47, 47) !important;
                } */
        .fc .fc-toolbar.fc-header-toolbar {
            padding: 5px
        }
    </style>

    @include('layout.message')

    <div class="card" style="border-radius:0px">
        <div class="card-body">
            <div class="col-md-12 d-flex justify-content-center align-items-center">
                <!-- Attendance Form -->
                <form method="POST" id="attendanceForm" action="{{ route('attendance') }}" class="d-flex align-items-center me-3">
                    @csrf
                    <div class="card-body">
                        <p class="demo">
                            <button value="check in" onclick="handleButtonClick(this)" class="btn btn-info me-2">Check In</button>
                            <button value="check out" onclick="handleButtonClick(this)" class="btn btn-info me-2">Check Out</button>
                            <a href="#" class="btn btn-info" id="createLeaveRequestButton">Leave Request</a>
                        </p>
                    </div>
                </form>
                <!-- Month and Year Picker -->
                <div class="d-flex align-items-center justify-content-center">
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
                    <button id="goToMonthYear" class="btn btn-info"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="card-footer">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    @include('pages.modalCheckLogin')

    @include('pages.modalRequest')

    @include('pages.leaveRequestModal')

    <script>
        // Function to update the clock
        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();

            // Ensure two digits for hours, minutes, and seconds
            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            var time = hours + ':' + minutes + ':' + seconds;

            // Update the clock element
            document.getElementById('clock').textContent = time;
        }

        // Update the clock every second
        setInterval(updateClock, 1000);

        // Initial call to display the clock immediately
        updateClock();
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                height: 700,
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('attendanceData') }}',
                    method: 'GET',
                    success: function(data) {
                        console.log('Events data:', data); // Log the events data here
                    },
                },
                dayMaxEventRows: true, // giới hạn số dòng sự kiện
                dayMaxEvents: true,

                eventContent: function(arg) {
                    var status = arg.event.extendedProps.status;
                    var containerEl = document.createElement('div');
                    containerEl.style.color = '#fff';


                    // Thiết lập màu sắc dựa trên status
                    if (status) {
                        switch (status) {
                            case 'success':
                                containerEl.style.backgroundColor = '#2eb85c';
                                break;
                            case 'pending':
                                containerEl.style.backgroundColor = '#f9b115';
                                break;
                            case 'reject':
                                containerEl.style.backgroundColor = '#e55353';
                                break;
                            default:
                                break;
                        }
                    } else {
                        // Màu xanh cho ngày lễ
                        containerEl.style.backgroundColor = '#007bff';
                    }

                    // Thiết lập tiêu đề của sự kiện
                    var titleEl = document.createElement('div');
                    titleEl.textContent = arg.event
                        .title; // Tiêu đề của sự kiện (ví dụ: Check In, Check Out)
                    containerEl.appendChild(titleEl);

                    // nếu là ngày lễ thì không cần hiển thị giờ
                    if (!status) {
                        return {
                            domNodes: [containerEl]
                        };
                    }

                    // Lấy và thiết lập thời gian của sự kiện
                    var timeEl = document.createElement('div');
                    var startTime = arg.event.start;
                    if (startTime) {
                        var formattedStartTime = new Date(startTime).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        timeEl.textContent = formattedStartTime;
                        containerEl.appendChild(timeEl);
                    }

                    return {
                        domNodes: [containerEl]
                    };
                },
                dateClick: function(info) {
                    showModal(info.dateStr)
                },
                // đổi màu cho tiêu đề thứ 7 cn
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
                    var day = info.date.getDay(); // Changed from getUTCDay to getDay
                    if (day === 0 || day === 6) { // 0: Sunday, 6: Saturday
                        info.el.style.backgroundColor = 'rgba(216 216 216 / 20%)'; // Light red background
                    }
                },
            });
            calendar.render();

            // Populate the year dropdown
            var yearPicker = document.getElementById('yearPicker');
            var currentYear = new Date().getFullYear();
            for (var i = currentYear - 10; i <= currentYear + 10; i++) {
                var option = document.createElement('option');
                option.value = i;
                option.text = i;
                yearPicker.appendChild(option);
            }
            // Set the default selected month and year to the current month and year
            document.getElementById('monthPicker').value = new Date().getMonth();
            document.getElementById('yearPicker').value = currentYear;

            // Handle month and year selection
            document.getElementById('goToMonthYear').addEventListener('click', function() {
                var selectedMonth = document.getElementById('monthPicker').value;
                var selectedYear = document.getElementById('yearPicker').value;
                if (selectedMonth && selectedYear) {
                    var newDate = new Date(selectedYear, selectedMonth,
                        1); // Create a new Date object with the selected month and year
                    calendar.gotoDate(newDate); // Go to the selected month and year in the calendar
                }
            });

            function showModal(date) {
                $('#attendanceDate').val(date);
                $('#attendanceModal').modal('show'); // Hiển thị modal
            }

            // show modal leaveRequestModal
            document.getElementById('createLeaveRequestButton').addEventListener('click', function() {
                var leaveRequestModal = new bootstrap.Modal(document.getElementById('leaveRequestModal'));
                leaveRequestModal.show();
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
                        window.location.href = '{{ route("leave_requests.index") }}';
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            //xử lý gửi request
            $('#saveAttendanceBtn').click(function() {
                var type = $('#attendanceType').val();
                var date = $('#attendanceDate').val();

                $.ajax({
                    url: '{{ route('sendRequest') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: type,
                        date: date
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Đã lưu thành công!');
                            $('#attendanceModal').modal('hide');
                            calendar.refetchEvents(); // Làm mới sự kiện trong lịch
                        } else {
                            alert('Errors');
                        }
                    },
                    error: function(response) {
                        if (response.status == 401) {
                            $('#attendanceModal').modal('hide');
                            $('#loginModal').modal('show'); // Hiển thị modal đăng nhập
                        } else if (response.status == 422) {
                            var errors = response.responseJSON.errors;
                            if (errors.date) {
                                $('#errorDate').text(errors.date[0]);
                            } else {
                                $('#errorDate').text('');
                            }
                        }
                    }
                });
            });
        });

        // xử lý cho chấm công
        function handleButtonClick(button) {
            var confirmationMessage = '';
            var actionType = button.value;

            switch (actionType) {
                case 'check in':
                    confirmationMessage = 'You are confirm check in?';
                    break;
                case 'check out':
                    confirmationMessage = 'You are confirm check out?';
                    break;
                default:
                    break;
            }

            if (confirmationMessage) {
                if (confirm(confirmationMessage)) {
                    submitForm(actionType);
                } else {
                    // Ngăn chặn hành động mặc định khi nhấn "Cancel"
                    event.preventDefault();
                }
            }
        }

        function submitForm(type) {
            var form = document.getElementById('attendanceForm');
            var typeInput = document.createElement('input');
            typeInput.setAttribute('type', 'hidden');
            typeInput.setAttribute('name', 'type');
            typeInput.setAttribute('value', type);
            form.appendChild(typeInput);
            form.submit();
        }
    </script>
@endsection
