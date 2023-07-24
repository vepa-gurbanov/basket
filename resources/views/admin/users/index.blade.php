@extends('admin.layouts.app')
@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <div class="cola">
                <h6 class="m-0 font-weight-bold text-primary">
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                    </a>
                </h6>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-sm btn-primary bi-plus-lg"
                        data-bs-toggle="modal" data-bs-target="#UserCreateModal"></button>

                <div class="modal fade" id="UserCreateModal" tabindex="-1" role="dialog" aria-labelledby="UserCreateModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="UserCreateModalLabel">
                                    User create
                                </h5>
                                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.users.store') }}" method="POST" id="UserCreateForm" class="has-validation" novalidate>
                                    @method('post')
                                    @csrf
                                    @honeypot

                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" for="email">{{ __('Name') }} <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name') }}"
                                               placeholder="Your name..."
                                               required
                                               autofocus />
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Email Address -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" for="email">{{ __('Email address') }} <span class="text-danger">*</span></label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               placeholder="Your email..."
                                               required />
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               placeholder="Your password..."
                                               required />
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                <a class="btn btn-primary" href="javascript:void(0)" onclick="event.preventDefault(); $('#UserCreateForm').submit();">{{ __('Save') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End User Create Modal -->

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                    <thead class="border-bottom">
                    <tr>
                        <th>ID</th>
                        <th>
                            <div>Name</div>
                            <div>Email</div>
                        </th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>
                            <div>Updated</div>
                            <div>Joined</div>
                        </th>
                        @can('admin')
                            <th>Delete</th>
                        @endcan
                    </tr>
                    </thead>
                    <tfoot class="border-top">
                    <tr>
                        <th>ID</th>
                        <th>
                            <div>Name</div>
                            <div>Email</div>
                        </th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>
                            <div>Updated</div>
                            <div>Joined</div>
                        </th>
                        @can('admin')
                            <th>Delete</th>
                        @endcan
                    </tr>
                    </tfoot>
                    <tbody>
                    @forelse($users as $user)
                        <tr class="{{ ! $loop->last ?? 'border-bottom' }}">
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class=" fw-bolder">{{ $user->name }}</div>
                                <div class=" fw-bolder">{{ $user->email }}</div>
                            </td>
                            <td>
                                @forelse($user->roles as $userRole)
                                    <span class="badge bg-primary">{{ $userRole->ability }}</span>
                                @empty
                                @endforelse
                                @can('admin', 'role_manager')
                                    <a class="badge badge-primary cursor-pointer" data-bs-toggle="modal" data-bs-target="#{{ $user->id }}_UserRoleModal"><i class="bi-plus"></i></a>

                                    <div class="modal fade" id="{{ $user->id }}_UserRoleModal" tabindex="-1" role="dialog" aria-labelledby="{{ $user->id }}_UserRoleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="{{ $user->id }}_UserRoleModalLabel">
                                                        User: <b>{{ $user->name }}</b>'s role{{ $user->roles->count() > 1 ? 's' : '' }}
                                                    </h5>
                                                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.users.role.update') }}" method="POST" id="{{ $user->id }}_UserRoleForm">
                                                        @method('put')
                                                        @csrf
                                                        @honeypot
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                                                        <div class="row justify-content-between">
                                                            @foreach($roles as $role)
                                                                <div class="col-6">
                                                                    <div class="border rounded bg-light-subtle mb-3">
                                                                        <div class="form-check text-start mx-2">
                                                                            <input class="form-check-input"
                                                                                   type="checkbox"
                                                                                   id="{{ $user->id }}_{{ $role->ability }}"
                                                                                   name="role[]"
                                                                                   value="{{ $role->id, $user->id }}"
                                                                                {{ in_array($role->id, $user->roles()->pluck('id')->toArray()) ? 'checked' : '' }}
                                                                            />
                                                                            <label class="form-check-label" for="{{ $user->id }}_{{ $role->ability }}">{{ $role->name }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                                    <a class="btn btn-primary" href="javascript:void(0)" onclick="event.preventDefault(); $('#{{ $user->id }}_UserRoleForm').submit();">{{ __('Save changes') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge text-bg-success">Verified</span>
                                @else
                                    <span class="badge text-bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-bolder">{{ date_format($user->updated_at, 'Y-m-d H:i:s') }}</div>
                                <div class="small fw-bolder">{{ date_format($user->created_at, 'Y-m-d H:i:s') }}</div>
                            </td>
                            <td>
                                @can('admin')
                                    <a class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#{{ $user->id }}_UserDeleteModal">Delete</a>

                                    <div class="modal fade" id="{{ $user->id }}_UserDeleteModal" tabindex="-1" role="dialog" aria-labelledby="{{ $user->id }}_UserDeleteModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="{{ $user->id }}_UserDeleteModalLabel">
                                                        User delete
                                                    </h5>
                                                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="h6">
                                                        Are you sure delete <b>{{ $user->name }}</b>?
                                                    </div>
                                                    <form action="{{ route('admin.users.destroy') }}" method="POST" id="{{ $user->id }}_UserDeleteForm">
                                                        @method('DELETE')
                                                        @csrf
                                                        @honeypot
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                                    <a class="btn btn-danger user-delete" href="javascript:void(0)" onclick="$('#' + '{{ $user->id }}_UserDeleteForm').submit();">{{ __('Delete') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="5">Empty</th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
