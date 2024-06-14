@extends('layout.index')

@section('content')
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> List Users</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('account.create') }}" class="btn btn-primary">New User</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">

            @include('layout.message')

            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 100%;">
                                <input type="text" value="{{ Request::get('keyword') }}" name="keyword" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    <button type="button" onclick="window.location.href='{{ route('account.index') }}'" class="btn btn-default"> <i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body table-responsive p-0">								
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->isNotEmpty())
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a href="{{ route('account.edit', $user->id) }}">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="" onclick="deleteUser({{ $user->id }})" class="text-danger w-4 h-4 mr-1">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Records Not Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>										
                </div>

                <div class="card-footer clearfix">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        function deleteUser(id) {
            var url = '{{ route('account.delete', 'ID') }}';
            var newUrl = url.replace('ID', id)
            if (confirm('Are you sure you want to delete this account?')) {
                $.ajax({
                    url: newUrl,
                    type: "delete",
                    data: {},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response["status"]) {
                            window.location.href = "{{ route('account.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection