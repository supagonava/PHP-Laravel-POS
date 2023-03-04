@extends('layouts.admin')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="align-self-center">Users</p>
                        <div class="d-flex align-self-center">
                            <a href="{{ route('users.create') }}" class="btn btn-success mr-2">Add User</a>
                            <form action="{{ route('users.index') }}" method="get" class="form-inline">
                                <div class="form-group mb-0 mr-2">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by name or email" value="{{ $search }}">
                                </div>
                                <div class="form-group mb-0 mr-2">
                                    <select name="role" class="form-control">
                                        <option value="">All Roles</option>
                                        <option value="admin" {{ $selectedRole == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="cashier" {{ $selectedRole == 'cashier' ? 'selected' : '' }}>Cashier
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>
                                        @if (Auth::user()->id != $user->id)
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="btn btn-sm btn-success">Edit</a>
                                            <form action="{{ route('users.destroy', $user) }}" method="post"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
