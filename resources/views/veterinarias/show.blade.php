@extends('layouts/contentLayoutMaster')

@section('title', 'Detalle de la Veterinaria')

@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/pages/app-user.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/pages/app-user.css')) }}">
@endsection

@section('content')
<section id="basic-datatable">
    <div class="card">
        <div class="card-header" style="display: block">
            <div class="card-title">
                <div class="row">
                    <div class="col-md-7 col-12">
                        Veterinaria N° {{ $veterinaria->id }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
                <section class="page-users-view">
                    <div class="row">
                        <div class="col-md-4 col-12 ">
                            <table>
                                <tr>
                                    <td class="font-weight-bold">Nombre</td>
                                    <td>{{ $veterinaria->nombre }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Dirección</td>
                                    <td>{{ $veterinaria->direccion }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 col-12 ">
                            <table>
                                <tr>
                                    <td class="font-weight-bold">Email</td>
                                    <td>{{ $veterinaria->email }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Teléfono</td>
                                    <td>{{ $veterinaria->telefono }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 mt-2 text-right">
                        <a href="{{ route('veterinarias.edit', $veterinaria->id) }}" class="btn btn-primary mr-1"><i
                                class="feather icon-edit-1"></i>
                            Editar
                        </a>
                        <span class="btn btn-danger mr-1 delete-button" data-id="{{ $veterinaria->id }}">
                            <i class="feather icon-trash"></i>
                            Eliminar
                        </span>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

<form id="deleteForm" method="POST" style="display: none">
    {{ csrf_field() }}
    {{ method_field('DELETE') }} 
</form>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Usuarios</h4>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table zero-configuration">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($veterinaria->users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles->implode('name', ', ') }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@endsection

@section('page-script')
<script src="{{ asset(mix('js/scripts/pages/app-user.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/navs/navs.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/pages/app-user.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/extensions/sweet-alerts.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/datatables/datatable.js')) }}"></script>

<script>
    $( document ).ready(function() {
    
        $(document.body).on('click', '.delete-button', function () {
            var id = $(this).data('id');
            
            Swal.fire({
              title: '¿Seguro de que deseas eliminar esta veterinaria?',
              type: 'warning',
              showCancelButton: true,
              cancelButtonText: 'Cancelar',
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirmar',
              confirmButtonClass: 'btn btn-primary',
              cancelButtonClass: 'btn btn-danger ml-1',
              buttonsStyling: false,
            }).then(function (result) {
              if (result.value) {
                  var url = window.location.origin;
                  url = `${url}/veterinarias/${id}`

                $('#deleteForm').attr('action', url);
                $('#deleteForm').submit();                      
              }
            })
          });
    });
</script>
@endsection
