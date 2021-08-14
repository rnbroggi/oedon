@extends('layouts/contentLayoutMaster')

@section('title', 'Roles')

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
                        <h4 class="card-title">Crear rol</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form novalidate class="form" action="{{ route('roles.store') }}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control" placeholder="Nombre" name="name"
                                                        value="{{ old('name') }}" required>
                                                    <label for="name">Nombre</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 multiple">
                                            <div class="form-group">
                                                <label for="users">Usuarios</label>
                                                <select class="select2 form-control" name="users[]" multiple="multiple">
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" @if (old('users') != null) 
                                                            @if (in_array($user->id, old('users')))
                                                            selected @endif
                                                    @endif>
                                                    {{ ucfirst($user->name) }}
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
                                                        <option value="{{ $permission->id }}" @if (old('permissions') != null)  @if (in_array($permission->id, old('permissions')))
                                                            selected @endif
                                                    @endif>
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
