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
                            <form novalidate class="form" action="{{ route('mascotas.store') }}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12 mt-md-2">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control" placeholder="Nombre" name="nombre"
                                                        value="{{ old('nombre') }}" required>
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
                                                        <option value="{{ $animal->id }}" @if (old('animal_id') != null)  @if ($animal->id==old('animal_id'))
                                                            selected @endif
                                                    @endif>{{ ucfirst($animal->nombre) }}
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
                                                    name="fecha_nacimiento" value="{{ old('fecha_nacimiento') ?? date('Y-m-d') }}" />
                                                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                            </div>
                                        </div>  
                                        <div class="col-md-6 col-12 mt-md-2">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="number" class="form-control" placeholder="Peso actual" name="peso_actual"
                                                        value="{{ old('peso_actual') }}" required>
                                                    <label for="peso_actual">Peso actual</label>
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
                                                                <input type="radio" value="1" name="activo"
                                                                    checked>
                                                                Activo
                                                            </label>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <label>
                                                                <input type="radio" value="0" name="activo">
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
                                                        <option value="{{ $sexo->id }}" @if (old('sexo_id') != null)  @if ($sexo->id==old('sexo_id'))
                                                            selected @endif
                                                    @endif>{{ ucfirst($sexo->nombre) }}
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
                                                    placeholder="Observaciones">{{ old('observaciones') }}</textarea>
                                                <label for="observaciones">Observaciones</label>
                                            </fieldset>
                                        </div>

                                        <h3 class="col-12">Datos del dueño</h3>

                                        <div class="col-lg-2 col-md-6 col-12 mt-md-2">
                                            <input type="checkbox" name="owner_exists" id="owner_exists"> Dueño ya registrado
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12 mt-sm-1 mt-md-0" id="cliente_id">
                                            <div class="form-group">
                                                <label for="cliente_id">Dueño</label>
                                                <select class="select2 form-control" name="cliente_id">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                    @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}" @if (old('cliente_id') != null)  @if ($cliente->id==old('cliente_id'))
                                                            selected @endif
                                                    @endif>{{ ucfirst($cliente->name) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12"></div>
                                        
                                        <div class="col-md-6 col-12 mt-md-2" id="nombre_cliente">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control" placeholder="Nombre" name="nombre_cliente"
                                                        value="{{ old('nombre_cliente') }}" required>
                                                    <label for="nombre_cliente">Nombre</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-md-2" id="email_cliente">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="email" class="form-control" placeholder="Email" name="email_cliente"
                                                        value="{{ old('email_cliente') }}" required>
                                                    <label for="email_cliente">Email</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-md-2" id="telefono_cliente">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono_cliente"
                                                        value="{{ old('telefono_cliente') }}" required>
                                                    <label for="telefono_cliente">Teléfono</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-md-2" id="password">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="password" class="form-control" placeholder="Contraseña" name="password"
                                                        value="{{ old('password') }}" required>
                                                    <label for="password">Contraseña</label>
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

    <script>
        $(document).ready(function () {
            $("#cliente_id").hide();

            $('#animal_id').change(function (e) { 
                let animal_id = this.value;
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
                
            });

            $('#owner_exists').change(function (e) { 
                if(this.checked){
                    $('#cliente_id').prop("disabled", false);
                    $("#cliente_id").show();
                    
                    $('#nombre_cliente').hide();
                    $('#email_cliente').hide();
                    $('#telefono_cliente').hide();
                    $('#password').hide();
                }else{
                    $('#cliente_id').prop("disabled", true);
                    $("#cliente_id").hide();

                    $('#nombre_cliente').show();
                    $('#email_cliente').show();
                    $('#telefono_cliente').show();
                    $('#password').show();
                }                
            });
        });
    </script>
@endsection
