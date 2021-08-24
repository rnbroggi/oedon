@extends('layouts/contentLayoutMaster')

@section('title', 'Visitas')

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
                        <h4 class="card-title">Registrar visita</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form novalidate class="form" action="{{ route('visitas.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="mascota_id">Mascota</label>
                                                <select class="select2 form-control" name="mascota_id">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                    @foreach ($mascotas as $mascota)
                                                        <option value="{{ $mascota->id }}" @if (old('mascota_id') != null)  @if ($mascota->id==old('mascota_id'))
                                                            selected @endif
                                                    @endif>{{ ucfirst($mascota->nombre) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-12 mt-md-2">
                                            <div class="form-label-group">
                                                <input type="datetime-local" class="form-control" placeholder="Fecha"
                                                    name="fecha" value="{{ old('fecha') }}" />
                                                <label for="fecha">Fecha</label>
                                            </div>
                                        </div>  
                                        <div class="col-md-6 col-12 mt-md-2">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="number" class="form-control" placeholder="Peso actual" name="peso"
                                                        value="{{ old('peso') }}" required>
                                                    <label for="peso">Peso actual</label>
                                                </div>
                                            </div>
                                        </div>  

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="veterinario_id">Veterinario</label>
                                                <select class="select2 form-control" name="veterinario_id">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                    @foreach ($veterinarios as $veterinario)
                                                        <option value="{{ $veterinario->id }}" @if (old('veterinario_id') != null)  @if ($veterinario->id==old('veterinario_id'))
                                                            selected @endif
                                                    @endif>{{ ucfirst($veterinario->name) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 

                                        <div class="col-12">
                                            <label for="observaciones">Observaciones</label>
                                            <fieldset class="form-label-group">
                                                <textarea class="form-control" name="observaciones" rows="3"
                                                    placeholder="Observaciones">{{ old('observaciones') }}</textarea>
                                            </fieldset>
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
