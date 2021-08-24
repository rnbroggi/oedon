@extends('layouts/contentLayoutMaster')

@section('title', 'Detalle de la Mascota')

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
    <section id="basic-datatable">
        <div class="card">
            <div class="card-header" style="display: block">
                <div class="card-title">
                    <div class="row">
                        <div class="col-md-7 col-12">
                            Mascota N° {{ $mascota->id }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <section class="page-users-view">
                        <div class="row">

                            <div class="col-md-4 col-12 text-center">
                                <img src="@if($mascota->getFirstMedia('foto')){{$mascota->getFirstMedia('foto')->getFullUrl()}} @else{{ asset('images/pages/dog-cat.png') }}@endif"
                                    width="350px" 
                                    height="300px" 
                                    alt="Foto de perfil" 
                                    style="border: 2px solid black;">
                            </div>

                            <div class="col-md-8 col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <table>
                                            @hasrole('superadmin')
                                                <tr>
                                                    <td class="font-weight-bold">Veterinaria</td>
                                                    <td>{{ $mascota->cliente->veterinaria->nombre ?? null }}</td>
                                                </tr>
                                            @endhasrole

                                            <tr>
                                                <td class="font-weight-bold">Nombre</td>
                                                <td>{{ $mascota->nombre }}</td>
                                            </tr>
        
                                            <tr>
                                                @if ($mascota->activo)
                                                    <td class="font-weight-bold">Edad</td>
                                                    <td>{{ $mascota->edad }}</td>
                                                @else
                                                    <td class="font-weight-bold">Fecha de <br> nacimiento</td>
                                                    <td>{{ $mascota->fecha_nacimiento ? $mascota->fecha_nacimiento->format('d/m/Y') : null }}
                                                    </td>
                                                @endif
                                            </tr>
        
                                            <tr>
                                                <td class="font-weight-bold">Animal</td>
                                                <td>{{ $mascota->raza->animal->nombre ?? null }}
                                                    ({{ $mascota->sexo->nombre ?? null }})</td>
                                            </tr>
        
                                            <tr>
                                                <td class="font-weight-bold">Raza</td>
                                                <td>{{ $mascota->raza->nombre ?? null }}</td>
                                            </tr>
        
                                            @isset($visitas[0])
                                                <tr>
                                                    <td class="font-weight-bold">Última visita</td>
                                                    <td>
                                                        {{ $visitas[0]->fecha->format('d/m/Y') ?? null }}
                                                    </td>
                                                </tr>
                                            @endisset
                                        </table>
                                    </div>
        
                                    <div class="col-md-6 col-12">
                                        <table>
                                            <tr>
                                                <td class="font-weight-bold">Estado</td>
                                                <td>{{ $mascota->activo ? 'Activo' : 'Inactivo' }}</td>
                                            </tr>
        
                                            <tr>
                                                <td></td>
                                                <td></td>
                                            </tr>
        
                                            <tr>
                                                <td class="font-weight-bold">Dueño</td>
                                                <td>{{ $mascota->cliente->name ?? null }}</td>
                                            </tr>
        
                                            <tr>
                                                <td class="font-weight-bold">Email</td>
                                                <td>{{ $mascota->cliente->email ?? null }}</td>
                                            </tr>
        
                                            <tr>
                                                <td class="font-weight-bold">Teléfono</td>
                                                <td>{{ $mascota->cliente->telefono ?? null }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if($mascota->observaciones )
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <table>    
                                            <tr>
                                                <td valign="top" class="font-weight-bold">Observaciones</td>
                                                <td>{{ $mascota->observaciones }}</td>
                                                
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>

    <div class="card">
    <div class="card-header">
        <h4 class="card-title">Historial de visitas</h4>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table zero-configuration">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Peso</th>
                            <th>Atendido por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visitas as $visita)
                            <tr>
                                <td>{{ $visita->id }}</td>
                                <td>{{ $visita->fecha->format('d/m/Y h:i:s') }}</td>
                                <td>{{ $visita->peso }} kg</td>
                                <td>{{ $visita->veterinario->name ?? null }}</td>
                                <td>
                                    <a href="{{ route('visitas.show', $visita->id) }}">
                                        <i class="feather icon-eye"></i>
                                    </a>
                                </td>
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
@endsection
