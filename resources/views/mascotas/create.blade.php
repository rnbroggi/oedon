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
                            <form novalidate class="form" action="{{ route('mascotas.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12 mt-md-2">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" placeholder="Nombre" name="nombre"
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
                                                    name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" />
                                                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
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

                                        <div class="col-md-4 col-12 mt-2">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="foto" name="foto">
                                                <label class="custom-file-label" for="file"> Adjuntar una foto</label>
                                            </div>
                                        </div> 
                                        
                                        <div class="col-md-2 col-12">
                                            <img id="preview" src="#" alt="Vista previa" width="90px" height="80px"/>
                                        </div> 

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="veterinario_id">Veterinario</label>
                                                <select class="select2 form-control" name="veterinario_id">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                    @foreach ($veterinarios as $veterinario)
                                                        <option value="{{ $veterinario->id }}" @if (old('veterinario_id') != null)  @if ($veterinario->id==old('veterinario_id'))
                                                            selected @endif
                                                    @endif
                                                    @if(Auth::user()->id == $veterinario->id)
                                                    selected
                                                    @endif
                                                    >{{ ucfirst($veterinario->name) }}
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

                                        <h3 class="col-12">Datos del due??o</h3>

                                        <div class="col-lg-2 col-md-6 col-12 mt-md-2">
                                            <input type="checkbox" name="owner_exists" id="owner_exists"> Due??o ya registrado
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12 mt-sm-1 mt-md-0" id="cliente">
                                            <div class="form-group">
                                                <label for="cliente">Due??o</label>
                                                <select class="select2 form-control" name="cliente">
                                                    <option value="" selected disabled hidden>Seleccionar</option>
                                                    @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}" @if (old('cliente') != null)  @if ($cliente->id==old('cliente'))
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
                                                    <input type="text" class="form-control @error('nombre_cliente') is-invalid @enderror" placeholder="Nombre" name="nombre_cliente"
                                                        value="{{ old('nombre_cliente') }}">
                                                    <label for="nombre_cliente">Nombre</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-md-2" id="email_cliente">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="email" class="form-control @error('email_cliente') is-invalid @enderror" placeholder="Email" name="email_cliente"
                                                        value="{{ old('email_cliente') }}" data-validation-email-message="Direcci??n de email inv??lida">
                                                    <label for="email_cliente">Email</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-md-2" id="telefono_cliente">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="text" class="form-control @error('telefono_cliente') is-invalid @enderror" placeholder="Tel??fono" name="telefono_cliente"
                                                        value="{{ old('telefono_cliente') }}">
                                                    <label for="telefono_cliente">Tel??fono</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-md-2" id="password">
                                            <div class="form-group">
                                                <div class="form-label-group controls">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contrase??a" name="password"
                                                        value="{{ old('password') }}">
                                                    <label for="password">Contrase??a</label>
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
        foto = document.getElementById('foto');
        preview = document.getElementById('preview');

        foto.onchange = evt => {
            const [file] = foto.files
            if (file) {
                preview.src = URL.createObjectURL(file)
                $('#preview').show();
            }
        }
    </script>

    <script>
        function setRazas(animal_id){
            let razas = @json($razas);

            $('#raza_id').empty().append('<option value="" selected disabled hidden>Seleccionar</option>');

            if(animal_id != ''){
                $('#raza_id').prop("disabled", false);
                razas = razas.filter(raza => {return raza.animal_id == animal_id});
                for (const raza of razas) {
                    $('#raza_id').append($('<option>', {
                        value: raza.id,
                        text: raza.nombre,
                    }));
                }
            }
        }
    </script>

    <script>

        $(document).ready(function () {
            $('#preview').hide();

            let oldAnimal = "{{ old('animal_id') }}";
            let oldRaza = "{{ old('raza_id') }}";

            if(oldAnimal){
                $('#animal_id').val(oldAnimal);
                setRazas(oldAnimal);

                if(oldRaza){
                    $('#raza_id').val(oldRaza);
                }
            }

            $("#cliente").hide();

            $('#animal_id').change(function (e) { 
                setRazas(this.value);
            });

            $('#owner_exists').change(function (e) { 
                if(this.checked){
                    $('#cliente').prop("disabled", false);
                    $("#cliente").show();
                    
                    $('#nombre_cliente').hide();
                    $('#email_cliente').hide();
                    $('#telefono_cliente').hide();
                    $('#password').hide();
                }else{
                    $('#cliente').prop("disabled", true);
                    $("#cliente").hide();

                    $('#nombre_cliente').show();
                    $('#email_cliente').show();
                    $('#telefono_cliente').show();
                    $('#password').show();
                }                
            });
        });
    </script>
@endsection
