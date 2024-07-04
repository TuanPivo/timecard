{{-- @extends('layout.index')

@section('content')
    @include('layout.message')

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h5 class="fw-bold mb-3">Lists Leave Request</h5>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('leave_requests.create') }}" class="btn btn-primary">Add Leave Request</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="basic-datatables" class="table table-head-bg-info text-center">
                <thead>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($leaveRequests as $request)
                        <tr>
                            <td>{{ $request->start_date }}</td>
                            <td>{{ $request->end_date }}</td>
                            <td>{{ $request->reason }}</td>
                            <td>{{ $request->status }}</td>
                            <td>
                                <a href="#">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <a href="#" type="button" class="text-danger w-4 h-4 mr-1">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
			$('#basic-datatables').DataTable({});

			$('#multi-filter-select').DataTable( {
				"pageLength": 5,
				initComplete: function () {
					this.api().columns().every( function () {
						var column = this;
						var select = $('<select class="form-select"><option value=""></option></select>')
						.appendTo( $(column.footer()).empty() )
						.on( 'change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
								);

							column
							.search( val ? '^'+val+'$' : '', true, false )
							.draw();
						} );

						column.data().unique().sort().each( function ( d, j ) {
							select.append( '<option value="'+d+'">'+d+'</option>' )
						} );
					} );
				}
			});
		});
    </script>
@endsection --}}

@extends('layout.index')

@section('content')
    <style>
        .fc .fc-toolbar.fc-header-toolbar {
            padding: 5px;
        }
    </style>

    @include('layout.message')

    <div class="card" style="border-radius:0px">
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
                            <label for="leave_date" class="form-label">Date</label>
                            <input type="datetime-local" class="form-control" id="leave_date" name="leave_date">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                height: 700,
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('leave_requests.index') }}',
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

                    return { domNodes: [containerEl] };
                },
                dateClick: function(info) {
                    document.getElementById('leave_date').value = info.dateStr;
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
            calendar.render();
        });
    </script>
@endsection



