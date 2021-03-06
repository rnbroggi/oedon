@extends('layouts/contentLayoutMaster')

@section('title', 'Veterinarias')

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
                        <h4 class="card-title">Editar veterinaria {{ $veterinaria->nombre }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form novalidate class="form" action="{{ route('veterinarias.update', $veterinaria->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" placeholder="Nombre" name="nombre"
                                                        value="{{ $veterinaria->nombre }}" required>
                                                    <label for="nombre">Nombre</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" placeholder="Direcci??n" name="direccion"
                                                        value="{{ $veterinaria->direccion }}">
                                                    <label for="direccion">Direcci??n</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email"
                                                        value="{{ $veterinaria->email }}" data-validation-email-message="Direcci??n de email inv??lida">
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" placeholder="Tel??fono" name="telefono"
                                                        value="{{ $veterinaria->telefono }}">
                                                    <label for="telefono">Tel??fono</label>
                                                </div>
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
