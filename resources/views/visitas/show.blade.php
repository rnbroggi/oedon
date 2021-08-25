@extends('layouts/contentLayoutMaster')

@section('title', 'Visitas')

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

<style>
</style>
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
    <section id="basic-datatable">
        <div class="card">
            <div class="card-header" style="display: block">
                <div class="card-title">
                    <div class="row">
                        <div class="col-md-7 col-12">
                            Visita N° {{ $visita->id }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <section class="page-users-view">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <table>
                                    <tr>
                                        <td class="font-weight-bold">Mascota</td>
                                        @isset($visita->mascota->nombre)
                                            <td>
                                                <a href="{{ route('mascotas.show', $visita->mascota->id) }}" target="_blank">
                                                    {{ $visita->mascota->nombre }}
                                                </a>
                                            </td>
                                        @endisset
                                    </tr>

                                    <tr>
                                        <td class="font-weight-bold">Fecha</td>
                                        <td>{{ $visita->fecha ? $visita->fecha->format('d/m/Y H:i:s') : null }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="font-weight-bold">Peso</td>
                                        <td>{{ $visita->peso }} kg</td>
                                    </tr>

                                    <tr>
                                        <td class="font-weight-bold">Atendido por</td>
                                        <td>{{ $visita->veterinario->name ?? null }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6 col-12">
                                <strong>Adjuntos</strong>
                                <table class="mt-2">
                                    @forelse ($visita->getMedia('archivo') as $archivo)
                                        <tr style="border-bottom: solid 0.5px #d6d6d6">
                                            <td style="padding-bottom: 0px">
                                                <a href="{{ route('visitas.singleFileDownload', $archivo) }}"
                                                    target="_blank">
                                                    <i class="feather icon-file"></i>
                                                    {{ $archivo->name }}
                                                </a>
                                            </td>
                                            <td style="padding-bottom: 0px">
                                                @hasanyrole('superadmin|administrativo|veterinario')
                                                <button data-id="{{ $archivo->id }}" type="button"
                                                    class="delete-file-button btn btn-icon rounded-circle btn-flat-danger ml-2">
                                                    <i class="feather icon-trash-2"></i>
                                                </button>
                                                @endhasanyrole
                                            </td>
                                        </tr>
                                    @empty
                                        <td class="font-weight-bold"><i>No hay archivos adjuntos</i></td>
                                        <td></td>
                                    @endforelse
                                </table>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <table>
                                    <tr>
                                        <td valign="top" class="font-weight-bold">Observaciones</td>
                                        <td>{{ $visita->observaciones }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 mt-2 text-right">
                            <a href="{{ route('visitas.multipleFileDownload', $visita) }}" target="_blank" class="btn btn-success mr-1">
                                <i class="feather icon-download"></i>
                                Descargar archivos
                            </a>
                        @hasanyrole('superadmin|administrativo|veterinario')
                            <a href="{{ route('visitas.edit', $visita->id) }}" class="btn btn-primary mr-1"><i
                                    class="feather icon-edit-1"></i>
                                Editar
                            </a>
                            <button id="delete-button" data-id="{{ $visita->id }}" class="btn btn-outline-danger"><i
                                    class="feather icon-trash-2"></i>
                                Eliminar
                            </button>
                            @endhasanyrole
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>

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

    @hasanyrole('superadmin|administrativo|veterinario')
    <script>
        $(document).ready(function() {
            $(document.body).on('click', '.delete-file-button', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Seguro que desea eliminar este archivo?',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function(result) {
                    if (result.value) {
                        window.location.href = "{{ route('visitas.deleteSingleFile', [':id']) }}"
                            .replace(':id', id);
                    }
                })
            });
        });
    </script>
    @endhasanyrole

@endsection
