@extends('layout.index')
@section('content')
    <div class="card-header">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Attendance History Of: <strong>{{ $user->name }}</strong></h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('account.index') }}" class="btn btn-danger btn-round">Back</a>
                <a href="{{ route('account.exportMonthly', $user->id) }}" class="btn btn-success btn-round">Export Excel</a>
            </div>
        </div>
        <div class="col-md-12 d-flex justify-content-center align-items-center bg-info">
            <div class="clock" id="clock" style="font-size: 3rem;"></div>
        </div>
    </div>
    <div class="card">
        <div id="calendar" class="pt-5"></div>
    </div>
@endsection
    
@section('customJs')    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('account.attendanceData', $user->id) }}',
                    method: 'GET',
                    success: function(data) {
                        console.log('Events data:', data);
                    },
                },
                eventContent: function(arg) {
                    var status = arg.event.extendedProps.status;
                    var containerEl = document.createElement('div');

                    switch (status) {
                        case 'success':
                            containerEl.style.backgroundColor = '#2eb85c';
                            containerEl.style.color = '#fff';
                            break;
                        case 'pending':
                            containerEl.style.backgroundColor =
                            '#f9b115';
                            containerEl.style.color = '#fff';
                            break;
                        case 'reject':
                            containerEl.style.backgroundColor = '#e55353';
                            containerEl.style.color = '#fff';
                            break;
                        default:
                            break;
                    }

                    var titleEl = document.createElement('div');
                    titleEl.textContent = arg.event
                    .title;
                    containerEl.appendChild(titleEl);

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
        });

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
@endsection
