@extends('layouts.app')

@section('content')
    <div class="container-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="page-title"> Clientes </h3> <br>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <button class="btn btn-primary btn-icon-text" data-bs-toggle="modal"
                                data-bs-target="#addClientModal">
                                <i class="mdi mdi-plus-circle-outline btn-icon-prepend"></i> Novo Cliente
                            </button>
                            <form method="GET" action="{{ route('client.search') }}" class="d-flex">
                                <input type="text" name="query" class="form-control me-2"
                                    placeholder="Search clients...">
                                <button type="submit" class="btn btn-outline-secondary btn-icon-text">
                                    <i class="mdi mdi-magnify btn-icon-prepend"></i> Search
                                </button>
                            </form>
                        </div>

                        @if (isset($clients) && $clients->count() > 0)
                            <div class="table-responsive mt-4 hoverable-table">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clients as $client)
                                            <tr>
                                                <td>{{ $client->id }}</td>
                                                <td>{{ $client->name }}</td>
                                                <td>{{ $client->email }}</td>
                                                <td>{{ $client->phone }}</td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm btn-icon-text"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editClientModal-{{ $client->id }}">
                                                        <i class="mdi mdi-pencil btn-icon-prepend"></i> Edit
                                                    </button>
                                                    <form action="{{ route('client.destroy', $client->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm btn-icon-text"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="mdi mdi-delete btn-icon-prepend"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Edit Client Modal -->
                                            <div class="modal fade" id="editClientModal-{{ $client->id }}" tabindex="-1"
                                                aria-labelledby="editClientModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editClientModalLabel">Edit Client
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('client.update', $client->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Name</label>
                                                                    <input type="text" class="form-control"
                                                                        id="name" name="name"
                                                                        value="{{ $client->name }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="email" class="form-label">Email</label>
                                                                    <input type="email" class="form-control"
                                                                        id="email" name="email"
                                                                        value="{{ $client->email }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="phone" class="form-label">Phone</label>
                                                                    <input type="text" class="form-control"
                                                                        id="phone" name="phone"
                                                                        value="{{ $client->phone }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="address" class="form-label">Address</label>
                                                                    <input type="text" class="form-control"
                                                                        id="address" name="address"
                                                                        value="{{ $client->address }}">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                        changes</button>
                                                                </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning" role="alert">
                                No clients found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Add New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('client.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Client</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
