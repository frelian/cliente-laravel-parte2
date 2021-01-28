@extends('layouts.app')

@section('content')
    <br>
    <div class="container">
        <h3 class="text-center">Bienvenido</h3>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="home" class="container tab-pane active"><br>

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4">
                        <h2>Modulo </h2>
                        <p>Área de inicio del vendedor</p>
                        <p>Por favor seleccione opción:</p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <a href="{{ route('clients') }}" class="btn-show-clients" data-id="2" >
                            <span style="font-size: 3rem;">
                                <i class="fas fa-user-friends"></i>
                            </span>
                            <h5>Clientes</h5>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <a class="btn-store-sale" data-id="2" >
                            <span style="font-size: 3rem;">
                               <i class="fas fa-shopping-cart"></i>
                            </span>
                            <h5>Pedidos</h5>
                        </a>

                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <a href="{{ route('logout') }}" class="btn-store-sale" data-id="2" >
                            <span style="font-size: 3rem;">
                               <i class="fas fa-shopping-cart"></i>
                            </span>
                            <h5>Cerrar sesión</h5>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
