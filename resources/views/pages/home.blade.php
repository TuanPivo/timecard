@extends('layout.index')
@section('content')
    <style>
        .btn-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
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

    <div class="container mt-5">
        <form method="POST" id="attendanceForm" action="{{ route('attendance') }}">
            @csrf
            <div class="btn-container">
                <button type="button" value="check in" onclick="handleButtonClick(this)" class="btn btn-primary">check
                    in</button>
                <button type="button" value="check out" onclick="handleButtonClick(this)" class="btn btn-primary">check
                    out</button>
                <button type="button" value="WFH" onclick="handleButtonClick(this)" class="btn btn-primary">WFH</button>
            </div>
        </form>
    </div>
    <div id="calendar" class="pt-5"></div>
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
                        <input type="hidden" id="modalDate" name="date">
                        <div class="form-group">
                            <label for="attendanceType">Loại:</label>
                            <select class="form-control" id="attendanceType" name="type">
                                <option value="check in">Check In</option>
                                <option value="check out">Check Out</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="attendanceDate">Ngày:</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="date" class="form-control" id="attendanceDate" name="date">
                            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('attendanceData') }}',
                    method: 'GET',
                    failure: function() {
                        alert('Có lỗi xảy ra khi tải dữ liệu!');
                    }
                },
                eventContent: function(arg) {
                    var status = arg.event.extendedProps.status;
                    var containerEl = document.createElement('div');

                    // Thiết lập màu sắc dựa trên status
                    switch (status) {
                        case 'pending':
                            containerEl.style.backgroundColor = '#ffc107'; // Màu vàng cho status pending
                            break;
                        case 'reject':
                            containerEl.style.backgroundColor = '#dc3545'; // Màu đỏ cho status reject
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

            function showModal(dateStr) {
                $('#modalDate').val(dateStr); // Đặt giá trị cho input hidden trong modal
                $('#attendanceModal').modal('show'); // Hiển thị modal
            }
            $('#saveAttendanceBtn').click(function() {
                var type = $('#attendanceType').val();
                var date = $('#modalDate').val();

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

