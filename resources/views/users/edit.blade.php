@extends('layouts/contentLayoutMaster')

@section('title', 'Usuarios')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/validation/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <p class="mb-0">
                {{ $message }}
            </p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
            </button>
        </div>
    @endif

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Ocurrieron errores!</strong> Por favor, revise los siguientes campos:<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- // Basic Floating Label Form section start -->
    <section class="simple-validation">
        <div class="row match-height">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Editar usuario de {{ $user->name }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form novalidate class="form" action="{{ route('users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls has-icon-left">
                                                    <input type="text" class="form-control" placeholder="Nombre" name="name"
                                                        value="{{ $user->name }}" required>
                                                    <div class="form-control-position">
                                                        <i class="feather icon-user"></i>
                                                    </div>
                                                    <label for="name">Nombre</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls has-icon-left">
                                                    <input type="email" class="form-control" placeholder="Email"
                                                        name="email" value="{{ $user->email }}" required>
                                                    <div class="form-control-position">
                                                        <i class="feather icon-mail"></i>
                                                    </div>
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls has-icon-left">
                                                    <input type="password" class="form-control" placeholder="Contraseña"
                                                        name="password" value="">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-lock"></i>
                                                    </div>
                                                    <label for="password">Contraseña</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls has-icon-left">
                                                    <input type="password" class="form-control"
                                                        placeholder="Confirmar Contraseña" name="password_confirmation"
                                                        value="">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-lock"></i>
                                                    </div>
                                                    <label for="password_confirmation">Confirmar Contraseña</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 multiple">
                                            <div class="form-group">
                                                <label for="roles">Roles</label>
                                                <select class="select2 form-control" name="roles[]" multiple="multiple">
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}" @if($user->hasRole($role->id)) selected @endif>
                                                    {{ ucfirst($role->name) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 multiple">
                                            <div class="form-group">
                                                <label for="roles">Permisos</label>
                                                <select class="select2 form-control" name="permissions[]"
                                                    multiple="multiple">
                                                    @foreach ($permissions as $permission)
                                                        <option value="{{ $permission->id }}" @if($user->hasPermissionTo($permission->id)) selected @endif>
                                                    {{ ucfirst($permission->name) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary mr-1 mb-1">Guardar</button>
                                            <button type="reset"
                                                class="btn btn-outline-warning mr-1 mb-1">Reiniciar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic Floating Label Form section end -->
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jqBootstrapValidation.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/validation/form-validation.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/select/form-select2.js')) }}"></script>
@endsection
