@extends('layout.index')
@section('content')
    <div class="header">
        <div class="col-6 text-left">
            <p>Timecard of: <strong>{{ $user->name }}</strong></p>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('account.index') }}" class="btn-sm btn-primary">Back</a>
        </div>
    </div>
    <div id="calendar" class="pt-5"></div>
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
    </script>
@endsection
