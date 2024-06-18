@extends('layout.index')
@section('content')

    <div id="calendar" class="pt-5"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('admin.attendanceData', $user->id) }}',
                    method: 'GET',
                    success: function(data) {
                        console.log('Events data:', data); // Log the events data here
                    },
                },   
                eventContent: function(arg) {
                    var status = arg.event.extendedProps.status;
                    var containerEl = document.createElement('div');

                    // Thiết lập màu sắc dựa trên status
                    switch (status) {
                        case 'success':
                            containerEl.style.backgroundColor = '#2eb85c';
                            containerEl.style.color = '#fff';
                            break;
                        case 'pending':
                            containerEl.style.backgroundColor = '#f9b115'; // Màu vàng cho status pending
                            containerEl.style.color = '#fff';
                            break;
                        case 'reject':
                            containerEl.style.backgroundColor = '#e55353'; // Màu đỏ cho status reject
                            containerEl.style.color = '#fff';
                            break;
                        default:
                            break;
                    }

                    // Thiết lập tiêu đề của sự kiện
                    var titleEl = document.createElement('div');
                    titleEl.textContent = arg.event.title; // Tiêu đề của sự kiện (ví dụ: Check In, Check Out)
                    containerEl.appendChild(titleEl);

                    // Lấy và thiết lập thời gian của sự kiện
                    var timeEl = document.createElement('div');
                    var startTime = arg.event.start;
                    if (startTime) {
                        var formattedStartTime = new Date(startTime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        timeEl.textContent = formattedStartTime;
                        containerEl.appendChild(timeEl);
                    }

                    return { domNodes: [containerEl] };
                },
                dateClick: function(info) {
                    showModal(info.dateStr)
                }
            });
            calendar.render();

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
        }

    
    </script>
@endsection

