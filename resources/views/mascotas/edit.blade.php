@extends('layouts/contentLayoutMaster')

@section('title', 'Mascotas')

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
                        <h4 class="card-title">Agregar mascota</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form novalidate class="form" action="{{ route('mascotas.update', $mascota->id) }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12 mt-md-2">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" placeholder="Nombre" name="nombre"
                                                        value="{{ $mascota->nombre }}" required>
                                                    <label for="nombre">Nombre</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="animal_id">Animal</label>
                                                <select class="select2 form-control" name="animal_id" id="animal_id">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                    @foreach ($animales as $animal)
                                                        <option value="{{ $animal->id }}" @if (isset($mascota->raza->animal_id)) @if($mascota->raza->animal_id == $animal->id) selected @endif @endif>
                                                            {{ ucfirst($animal->nombre) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="raza_id">Raza</label>
                                                <select class="select2 form-control" disabled name="raza_id" id="raza_id">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-12 mt-md-2">
                                            <div class="form-label-group">
                                                <input type="date" class="form-control" placeholder="Fecha de Nacimiento"
                                                    name="fecha_nacimiento" value="{{ $mascota->fecha_nacimiento }}" />
                                                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                            </div>
                                        </div>  
                                        <div class="col-md-6 col-12 mt-md-2">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="number" class="form-control" placeholder="Peso actual" name="peso"
                                                        value="{{ $mascota->peso }}" required>
                                                    <label for="peso">Peso actual</label>
                                                </div>
                                            </div>
                                        </div>  
                                        
                                        <div class="col-md-6 col-12 mt-2">
                                            <label>Estado:</label>
                                            <div>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <label>
                                                                <input type="radio" value="1" name="activo" @if($mascota->activo) checked @endif>
                                                                Activo
                                                            </label>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <label>
                                                                <input type="radio" value="0" name="activo" @if(!$mascota->activo) checked @endif>
                                                                Inactivo
                                                            </label>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="sexo_id">Sexo</label>
                                                <select class="select2 form-control" name="sexo_id">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                    @foreach ($sexos as $sexo)
                                                        <option value="{{ $sexo->id }}" @if($sexo->id == $mascota->sexo_id) selected @endif>
                                                            {{ ucfirst($sexo->nombre) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-12 mt-md-1">
                                            <div class="form-group">
                                                <label for="foto">Foto</label>
                                                <input type="file" class="form-control-file" id="foto" name="foto">
                                            </div>
                                        </div> 

                                        <div class="col-12">
                                            <label for="observaciones">Observaciones</label>
                                            <fieldset class="form-label-group">
                                                <textarea class="form-control" name="observaciones" rows="3"
                                                    placeholder="Observaciones">{{ $mascota->observaciones }}
                                                </textarea>
                                            </fieldset>
                                        </div>

                                        <h3 class="col-12">Datos del dueño</h3>
                                        
                                        <div class="col-lg-4 col-md-6 col-12 mt-sm-1 mt-md-0" id="cliente">
                                            <div class="form-group">
                                                <label for="cliente">Dueño</label>
                                                <select class="select2 form-control" name="cliente">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                    @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}" @if($mascota->user_id == $cliente->id) selected @endif>
                                                            {{ ucfirst($cliente->name) }}
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

    <script>
        function setRazas(animal_id){
            let razas = @json($razas);

            $('#raza_id').empty().append('<option value="" selected disabled hidden>Seleccionar</option>');

            if(animal_id != ''){
                $('#raza_id').prop("disabled", false);
                for (const raza of razas) {
                    if(raza.animal_id == animal_id){
                        $('#raza_id').append($('<option>', {
                            value: raza.id,
                            text: raza.nombre,
                        }));
                    }
                }
            }
        }
    </script>

    <script>

        $(document).ready(function () {
            let oldAnimal = "{{ $mascota->raza->animal_id ?? null }}";
            let oldRaza = "{{ $mascota->raza_id }}";

            if(oldAnimal){
                $('#animal_id').val(oldAnimal);
                setRazas(oldAnimal);

                if(oldRaza){
                    $('#raza_id').val(oldRaza);
                }
            }

            $('#animal_id').change(function (e) { 
                setRazas(this.value);
            });
        });
    </script>
@endsection
