@extends('layout.index')
@section('content')
    <style>
        .btn-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            width: 50%;
        }

        .btn-container .btn {
            flex: 1;
            margin: 5px;
        }
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

    <div class="container mt-5">
        <form method="POST" id="attendanceForm" action="{{ route('attendance') }}">
            @csrf
            <div class="btn-container">
                <button type="button" value="check in" onclick="handleButtonClick(this)" class="btn btn-danger">check
                    in</button>
                <button type="button" value="check out" onclick="handleButtonClick(this)" class="btn btn-danger">check
                    out</button>
                {{-- <button type="button" value="WFH" onclick="handleButtonClick(this)" class="btn btn-danger">WFH</button> --}}
            </div>
        </form>
    </div>
    <div id="calendar" class="pt-5"></div>
    @include('pages.modalCheckLogin')
    @include('pages.modalRequest')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kiểm tra nếu người dùng chưa đăng nhập
        @guest
        $('#loginModal').modal('show'); // Hiển thị modal khi người dùng chưa đăng nhập
        @endguest
    });
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
                            containerEl.style.backgroundColor ='#f9b115'; // Màu vàng cho status pending
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
                dateClick: function(info) {
                    showModal(info.dateStr)
                }
            });
            calendar.render();

            function showModal(date) {
                $('#attendanceDate').val(date); 
                $('#attendanceModal').modal('show'); // Hiển thị modal
            }
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
                            alert('Có lỗi xảy ra!');
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra!');
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
