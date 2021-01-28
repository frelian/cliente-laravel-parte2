@extends('layouts.app')

@section('content')

    <br>
    <div class="container">
        <h3 class="text-center">Clientes asignados</h3>
        <br>
        <!-- Tab panes -->
        <div class="tab-content">
            <div id="home" class="container tab-pane active"><br>
                <div class="row">
                    <div class="col">

                        <table class="table table-hover">
                            <thead class="">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Identificación</th>
                                <th scope="col">Tipo ID</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Razón social</th>
                                <th scope="col">Dirección</th>
                                <th scope="col">Teléfono</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if( $clientsPaginate)
                                @foreach($clientsData as $client )
                                    <tr class="item-row{{ $client->id }} row-client" data-id="{{ $client->id }}">
                                        <th scope="row">{{ $client->id }}</th>
                                        <td>{{ $client->ide_cli }}</td>
                                        <td>
                                            @if ( $client->ide_type_cli == 'identification' ) Identificación @else Nit @endif
                                        </td>
                                        <td>{{ $client->first_name_cli }}</td>
                                        <td>{{ $client->sur_name_cli }}</td>
                                        <td>{{ $client->business_name_cli }}</td>
                                        <td>{{ $client->address_cli }}</td>
                                        <td>{{ $client->phone_cli }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {

            $(".row-client").click(function(){

                let id_client = $(this).data("id");

                if (id_client) {

                    location.href = "/client/sale/"+id_client;
                }
            });
        });
    </script>

@endsection
