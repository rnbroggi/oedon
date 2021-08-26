@extends('layouts/contentLayoutMaster')

@section('title', 'Auditoría')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <p class="mb-0">
                {{ $message }}
            </p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
            </button>
        </div>
    @endif
    <!-- Zero configuration table -->
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Auditoría</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Acción</th>
                                            <th>Modelo</th>
                                            <th>ID de modelo</th>
                                            <th>Valores anteriores</th>
                                            <th>Valores nuevos</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($audits as $audit)
                                            <tr>
                                                <td>{{ $audit->user->id ?? null }}</td>
                                                <td>{{ $audit->event }}</td>
                                                <td>{{ $audit->auditable_type }}</td>
                                                <td>{{ $audit->auditable_id }}</td>
                                                <td>{{ $audit->old_values }}</td>
                                                <td>{{ $audit->new_values }}</td>
                                                <td>{{ date('d/m/Y',strtotime($audit->created_at)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <form id="deleteForm" method="POST" style="display: none">
        {{ csrf_field() }}
        {{ method_field('DELETE') }} 
    </form>
    <!--/ Zero configuration table -->
@endsection
@section('vendor-script')
    {{-- vendor files --}}
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
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/datatables/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/sweet-alerts.js')) }}"></script>

    <script>
        $( document ).ready(function() {
        
            $(document.body).on('click', '.delete-button', function () {
                var id = $(this).data('id');
                
                Swal.fire({
                  title: '¿Seguro de que deseas eliminar este usuario?',
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
                      url = `${url}/users/${id}`

                    $('#deleteForm').attr('action', url);
                    $('#deleteForm').submit();                      
                  }
                })
              });
        });
    </script>
@endsection
