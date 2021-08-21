@extends('layouts/contentLayoutMaster')

@section('title', 'Perfil')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/validation/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/pages/app-user.css')) }}">

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
    <!-- users edit start -->
    <section class="users-edit">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div>
                        <!-- users edit media object start -->
                        <form action="{{ route('profile.update') }}" novalidate method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="media mb-2">
                            <a class="mr-2 my-25" href="#">
                                <img src="{{ auth()->user()->getFirstMedia('avatar') ? auth()->user()->getFirstMedia('avatar')->getUrl() : asset('images/portrait/small/avatar-s-0.jpg') }}" 
                                    alt="users avatar" class="users-avatar-shadow rounded" height="64" width="64" id="avatar-picture">
                            </a>
                            <div class="media-body mt-50">
                                <h4 class="media-heading">{{ ucfirst(auth()->user()->name) }}</h4>
                                <div class="col-12 d-flex mt-1 px-0">
                                    <button type="button" class="btn btn-primary d-none d-sm-block mr-75" id="change-avatar">Cambiar</button>
                                    <input type="file" id="avatar-input"  name="avatar" style="display:none" />
                                    <a href="#" class="btn btn-primary d-block d-sm-none mr-75"><i
                                            class="feather icon-edit-1"></i></a>
                                    <button type="button" class="btn btn-outline-danger d-none d-sm-block" id="remove-avatar">Quitar</button>
                                    <a href="#" class="btn btn-outline-danger d-block d-sm-none"><i
                                            class="feather icon-trash-2"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- users edit media object ends -->
                        <!-- users edit account form start -->
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label>E-mail</label>
                                            <input type="email" class="form-control" placeholder="Email" name="email"
                                                value="{{ auth()->user()->email }}" data-validation-email-message="Dirección de email inválida" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3>Cambio de contraseña</h3>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label>Nueva Contraseña</label>
                                            <input type="password" class="form-control" placeholder="Nueva Contraseña" name="password"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label>Confirmar contraseña</label>
                                            <input type="password" class="form-control" placeholder="Confirmar contraseña" name="confirm_password"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Guardar
                                    cambios</button>
                                <button type="reset" class="btn btn-outline-warning">Reiniciar</button>
                            </div>
                    </div>
                    <input type="hidden" name="remove_avatar_input" id="remove_avatar_input" value="0">
                    </form>
                    <!-- users edit account form ends -->
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- users edit ends -->
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jqBootstrapValidation.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/app-user.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/navs/navs.js')) }}"></script>
    <script>
        $('#change-avatar').click(function(){
            $("#avatar-input").trigger('click');
        });
        
        $('#remove-avatar').click(function(){
            var src = @json(asset('images/portrait/small/avatar-s-0.jpg'));
            $('#avatar-picture').attr('src', src);
            $('#remove_avatar_input').val(1);
            $("#avatar-input").val(null);
        });

        $("#avatar-input").on('change', function(){
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
            {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#remove_avatar_input').val(0);
                    $('#avatar-picture').attr('src', e.target.result);
                }
            reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
@endsection
