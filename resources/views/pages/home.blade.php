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
        <form method="POST" id="attendanceForm"  action="{{ route('attendance') }}">
            @csrf
            <div class="btn-container">
                <button type="button" value="check in" onclick="handleButtonClick(this)" class="btn btn-primary">check in</button>
                <button type="button" value="check out" onclick="handleButtonClick(this)" class="btn btn-primary">check out</button>
                <button type="button" value="WFH" onclick="handleButtonClick(this)" class="btn btn-primary">WFH</button>
            </div>
        </form>
    </div>
    <div id="calendar"></div>
    <script>
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
@section('scripts')
<script>
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
                case 'Request':
                    confirmationMessage = 'Bạn có chắc chắn muốn gửi yêu cầu?';
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
