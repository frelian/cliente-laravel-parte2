@extends('layouts.app')

@section('content')

    <br>
    <div class="container">
        <h4 class="text-center">Registro de pedido</h4>
        <br>
        <div class="row">
            <div class="col-8">
                <div class="container">
                    <h4>Cliente:</h4>
                    <h2>{{ $client->first_name_cli . ' ' . $client->sur_name_cli . ' ' . $client->business_name_cli }}</h2>
                </div>
            </div>
        </div>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="home" class="container tab-pane active"><br>
                <div class="row">
                    <div class="col">
                        <div class="container">
                            <div class="row">
                                <div class="col-7">
                                    <select name="sel-products" id="sel-products" class="form-control">
                                        <option value="" disabled selected>Seleccione</option>

                                        @foreach($productsData as $product )
                                            <option value="{{ $product->id_product }}"
                                                    data-name="{{ $product->product_name }}"
                                                    data-price="{{ $product->sale_price }}"
                                                    data-stock="{{ $product->stock }}"
                                                    data-client_product_id="{{ $product->id_client_products }}"
                                                    >
                                                {{ $product->product_name . ' (Precio: '.
                                                    $product->sale_price .', stock: '.
                                                    $product->stock . ') '}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <a id="btn-product" class="btn btn-dark" data-toggle="modal"
                                       data-name="{{ $product->product_name }}"
                                       data-target="#product-modal">
                                            Seleccionar
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a id="btn-clear-order" class="btn btn-warning">
                                        Vaciar pedido
                                    </a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="area-products" id="area-products">
                            <h5>Productos a ordenar:</h5>
                            <ul class="list-group" id="ul-products">
                            </ul>
                            <br>

                            <div class="container">
                                <div class="row justify-content-md-center">
                                    <div class="col col-lg-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h4>Total:</h4>
                                                <input type="number"  id="txtTotal" hidden="hidden">
                                            </div>
                                            <div class="col-md-3">
                                                <h4 id="total" class="font-weight-bold"></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-lg-6">
                                        <div class="justify-content-center">

                                            <a id="btn-store-db" class="btn btn-success">
                                                Realizar pedido
                                            </a>
                                            <a href="{{ route('clients') }}" class="btn btn-link mr-2">
                                                Volver
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- small modal -->
    <div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="form-group row">
                            <div class="col-3">
                                <h5 class="font-weight-bold">Producto:</h5>
                            </div>
                            <div class="col-lg-9">
                                <h5 id="add-product-name"></h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <h5 class="font-weight-bold">Precio:</h5>
                            </div>
                            <div class="col-lg-3">
                                <h5 id="add-product-price"></h5>
                            </div>
                            <div class="col-2">
                                <h5 class="font-weight-bold">Stock:</h5>
                            </div>
                            <div class="col-lg-3">
                                <h5 id="add-product-stock"></h5>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-3">
                                <h5 class="font-weight-bold">Cantidad:</h5>
                            </div>

                            <div class="col-lg-3">
                                <input type="text" name="quantity" id="quantity" class="form-control"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/[^\d]+/g,'');" />
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row justify-content-center">

                                <a id="btn-add-cart" class="btn btn-success">
                                    Agregar a la orden
                                </a>

                                <a id="btn-close-modal" class="btn btn-default">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {

            // No ofresco la posibilidad de cargar los datos al presionar F5 dado el tiempo...
            $('#area-products').hide();
            $('#ul-products').empty();
            $('#area-products').hide();
            $('#total').text('');
            $('#txtTotal').val('');
            localStorage.removeItem('datos');

            $('#btn-close-modal').click(function (){
                $('#product-modal').modal('hide');
            });

            $('#btn-product').click(function (){

                $('#quantity').val('');
                var name = $('#sel-products option:selected').data('name');
                var price = $('#sel-products option:selected').data('price');
                var stock = $('#sel-products option:selected').data('stock');

                $("#add-product-name").text(name);
                $("#add-product-price").text(price);
                $("#add-product-stock").text(stock);

            });

            $('#btn-clear-order').click(function (){

                swal({
                    title: "Realmente desea eliminar la orden  ?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        localStorage.removeItem('datos');
                        $('#ul-products').empty();
                        $('#area-products').hide();
                        $('#total').text('');
                        $('#txtTotal').val('');
                        swal("Hecho", "La orden fue eliminada", "success");
                    }
                });
            });

            $("#btn-open-modal-add").click(function(){

                let name = $(this).data("name");

                if (name) {
                    $("#add-product-name").text(name);
                    console.log(name);
                }
            });

            $('#btn-add-cart').click(function (){

                var quantity = $('#quantity').val();

                if (quantity) {
                    var id = $('#sel-products').val();
                    var name = $('#sel-products option:selected').data('name');
                    var price = $('#sel-products option:selected').data('price');
                    var stock = $('#sel-products option:selected').data('stock');
                    var client_product_id = $('#sel-products option:selected').data('client_product_id');

                    $("#ul-products").append( "<li data-id="+id+" data-qt="+quantity+" " +
                        "class='list-group-item d-flex justify-content-between align-items-center'>"+name+
                        "<span class='badge badge-primary badge-pill'>"+quantity+"</span></li>" );

                    // Obtengo el string
                    var data = localStorage.getItem('datos');
                    var arrayProductos = JSON.parse(data);

                    if ( $('#txtTotal').val() != "" ) {
                        console.log("existe");
                        var total = parseFloat($('#txtTotal').val(), 10);

                        total = total + (price * quantity);
                        $('#total').text(total);
                        $('#txtTotal').val(total);
                    } else {

                        var total = price * quantity;
                        $('#total').text(total);
                        $('#txtTotal').val(total);
                    }

                    if ( arrayProductos) {

                        // Vuelvo a grabar
                        arrayProductos.push("item_idproduct_idclientproduct_qt", id, client_product_id, quantity);

                        // Guardo el objeto como un string
                        localStorage.setItem('datos', JSON.stringify(arrayProductos));

                    } else {

                        var productos = [
                            "item_idproduct_idclientproduct_qt",
                            id,
                            client_product_id,
                            quantity
                        ];

                        // Guardo el objeto como un string
                        localStorage.setItem('datos', JSON.stringify(productos));
                    }
                }
                $('#sel-products').val('');
                $('#product-modal').modal('hide');
                $('#area-products').show();
            });

            $('#btn-store-db').click(function (){

                swal({
                    title: "Confirmar el pedido ?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        var data = localStorage.getItem('datos');
                        var arrayProductos = JSON.parse(data);

                        console.log(arrayProductos);

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type:"POST",
                            url:"/client/store/products",
                            data:{
                                arrayProductos: arrayProductos,
                            },
                            dataType: "json",
                            error: function error(data) {
                                console.log(data);
                                swal("Error", "Error al procesar la solicitud de compra", "error");
                            },
                            success:function(data){
                                console.log(data);
                                swal("Hecho", "Se guardó la solicitud de compra correctamente...", "success");
                            },
                        })

                        localStorage.removeItem('datos');
                        $('#ul-products').empty();
                        $('#area-products').hide();
                    }
                });

            });

        });
    </script>

@endsection
