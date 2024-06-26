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
    </style>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    <div class="row">
        <div class="col-md-4">
            <div class="card justify-content-center align-items-center ">
                <div class="card-header">
                    <h4 class="card-title">Attendance</h4>
                </div>
                <form method="POST" id="attendanceForm" action="{{ route('attendance') }}">
                    @csrf
                    <div class="card-body">
                        <p class="demo">
                            <button value="check in" onclick="handleButtonClick(this)"
                                class="btn btn-danger">checkin</button>
                            <button value="check out" onclick="handleButtonClick(this)" class="btn btn-danger">check
                                out</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4 d-flex justify-content-center align-items-center">
            <div class="card">
                <div class="clock" id="clock" style="font-size: 3rem;"></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="my-4">
            <div class="d-flex align-items-center justify-content-center" style="margin-left: 10px">
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
                <button id="goToMonthYear" class="btn btn-primary">Go</button>
            </div>
        </div>
        <div id="calendar" class="pt-5">
        </div>
    </div>
    @include('pages.modalCheckLogin')
    @include('pages.modalRequest')

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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('attendanceData') }}',
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
                        case 'success':
                            containerEl.style.backgroundColor = '#2eb85c';
                            break;
                        case 'pending':
                            containerEl.style.backgroundColor =
                                '#f9b115'; // Màu vàng cho status pending
                            break;
                        case 'reject':
                            containerEl.style.backgroundColor = '#e55353'; // Màu đỏ cho status reject
                            break;
                        default:
                            break;
                    }

                    // Thiết lập tiêu đề của sự kiện
                    var titleEl = document.createElement('div');
                    titleEl.textContent = arg.event
                        .title; // Tiêu đề của sự kiện (ví dụ: Check In, Check Out)
                    containerEl.appendChild(titleEl);

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
                // dayCellDidMount: function(info) {
                //     var day = info.date.getDay();
                //     if (day === 6) { // Saturday
                //         info.el.classList.add('.fc-day-sat');
                //     } else if (day === 0) { // Sunday
                //         info.el.classList.add('fc-day-sat');
                //     }
                // },
                dateClick: function(info) {
                    showModal(info.dateStr)
                }
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

            // Handle month and year selection
            document.getElementById('goToMonthYear').addEventListener('click', function() {
                var selectedMonth = document.getElementById('monthPicker').value;
                var selectedYear = document.getElementById('yearPicker').value;
                if (selectedMonth && selectedYear) {
                    var newDate = new Date(selectedYear, selectedMonth, 1); // Create a new Date object with the selected month and year
                    calendar.gotoDate(newDate); // Go to the selected month and year in the calendar
                }
            });

            function showModal(date) {
                $('#attendanceDate').val(date);
                $('#attendanceModal').modal('show'); // Hiển thị modal
            }

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
                        } else {
                             alert('Errors!');
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
                    confirmationMessage = 'Bạn có chắc chắn muốn check in?';
                    break;
                case 'check out':
                    confirmationMessage = 'Bạn có chắc chắn muốn check out?';
                    break;
                case 'WFH':
                    confirmationMessage = 'Bạn có chắc chắn muốn WFH?';
                    break;
                default:
                    break;
            }

            if (confirmationMessage) {
                if (confirm(confirmationMessage)) {
                    disableButton(button);
                    submitForm(actionType);
                }
            }
        }

        function disableButton(button) {
            button.disabled = true;
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
