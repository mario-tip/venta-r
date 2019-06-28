<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <link crossorigin="anonymous" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" rel="stylesheet"/>
        <!-- Optional theme -->
        <link crossorigin="anonymous" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" rel="stylesheet"/>
        <!-- Latest compiled and minified JavaScript -->
        <script crossorigin="anonymous" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
        </script>
</head>

<style>

body, h4 {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 6pt;
}

h4 {
  margin: 0;
  line-height: 1;
}

h5 {
  font-size: 6pt;
  line-height: 1;
  margin: 2px 0;
  font-family: Arial, Helvetica, sans-serif;
}

footer {
  position: fixed; 
  bottom: -30px; 
  left: 0px; 
  right: 0px;
  height: 50px;
  line-height: 0.1;
 }

.factura-label {
  /* width: 100%; */
  color: rgb(121, 121, 121);
  border-width: 1px;
  border-style: solid;
  border-image: initial;
  border-color: transparent transparent rgb(121, 121, 121);
}

.portlet {
    margin-top: 15px;
    margin-bottom: 0px;
    padding: 0px;
    /* -webkit-border-radius: 4px; */
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}

.portlet > .portlet-body {
    clear: both;
    -webkit-border-radius: 0 0 4px 4px;
    -moz-border-radius: 0 0 4px 4px;
    -ms-border-radius: 0 0 4px 4px;
    -o-border-radius: 0 0 4px 4px;
    border-radius: 0 0 4px 4px;
}

.table-responsive {
    min-height: .01%;
    overflow-x: auto;
}

.table-striped > tbody > tr:nth-child(odd) {
    background-color: #ffffff;
}

.table > tbody > tr > td {
    padding: 1px;
    vertical-align: middle;
    text-align: center;
    border-top: 1px solid #ddd;
    font-size: 6pt;
    font-weight: 100;
}

th {
  text-align: center;
}

</style>

<body>

  <div class="container-fluid">

      <div style="margin-bottom:15px;">
        <img src="../public/logo.png" alt="Logo" style="height: 60px;"/>
      </div>


        <div class="row">
        <div class="col-xs-4">
          <h4><strong>Nombre:</strong></h4>
          <h4 class="factura-label">
              {{ $client->social_reason }}

          </h4>
        </div>

        <div class="col-xs-1">
          <h4><strong>Teléfono:</strong></h4>
          <h4 class="factura-label">
              {{ $client->phone }}
          </h4>
        </div>

        <div class="col-xs-1">
          <h4><strong>Estado:</strong></h4>
          <h4 class="factura-label">
              {{ $client->state }}
          </h4>
        </div>

        <div class="col-xs-3">
          <h4><strong>Municipio:</strong></h4>
          <h4 class="factura-label">
              {{ $client->city }}
          </h4>
        </div>

      </div>

      <div class="row">
          <div class="col-xs-4">
            <h4><strong>Dirección:</strong></h4>
            <h4 class="factura-label">
                {{ $client->street }}
            </h4>
          </div>

          <div class="col-xs-1">
            <h4><strong>C.P.:</strong></h4>
            <h4 class="factura-label">
                {{ $client->cp }}
            </h4>
          </div>

          <div class="col-xs-1">
            <h4><strong># Exterior:</strong></h4>
            <h4 class="factura-label">
                {{ $client->external_number }}
            </h4>
          </div>

          <div class="col-xs-1">
            <h4><strong># Interior:</strong></h4>
            <h4 class="factura-label">
                {{ $client->internal_number }}
            </h4>
          </div>

          <div class="col-xs-2">
              <h4><strong>Fecha:</strong></h4>
              <h4 class="factura-label">
                {{ $data_order->created_at}}
              </h4>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-2">
              <h4><strong>RFC:</strong></h4>
              <h4 class="factura-label">
                  {{ $client->rfc }}
              </h4>
            </div>

            <div class="col-xs-3">
              <h4><strong>Email:</strong></h4>
              <h4 class="factura-label">
                  {{ $client->email }}
              </h4>
            </div>

            <div class="col-xs-2">
              <h4><strong># Pedido:</strong></h4>
              <h4 class="factura-label">
                {{ $data_order->order_number}}
              </h4>
            </div>



          </div>



    <div class="portlet">
      <div class="portlet-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <tr>
              <th>
                Código
              </th>

              <th>
                Producto
              </th>

              <th>
                Tipo de acabado
              </th>

              <th>
                Cantidad
              </th>

              <th>
                Precio unitario
              </th>

              <th>
                Total
              </th>
            </tr>
            @foreach ($data_sales as $sale)
                <tr>
                  <td>
                    {{ $sale->product->barCode }}
                  </td>


                  <td style="text-align:left">
                    {{ $sale->product->name }}
                  </td>

                  <td>
                      {{ $sale->type_finish }}
                  </td>

                  <td>
                      {{ $sale->quantity }}
                  </td>

                  <td>
                      {{ $sale->price }}
                    </td>

                  <td>
                      {{ $sale->total }}
                  </td>
                </tr>
                @endforeach
              </table>

        </div>
      </div>
    </div>
    <div>
      <div style="text-align:right;">
        <h5><strong>Subtotal:</strong></h5><h5 style="font-weight:100; color: rgb(121, 121, 121);"><span>$ </span>{{ $data_order->subtotal}}</h5>
      </div>

      <div style="text-align:right;">
        <h5><strong>IVA:</strong></h5><h5 style="font-weight:100; color: rgb(121, 121, 121);"><span>$ </span>{{ $data_order->iva}}</h5>
      </div>

      <div style="text-align:right;">
        <h5><strong>Total:</strong></h5><h5 style="font-weight:100; color: rgb(121, 121, 121);"><span>$ </span>{{ $data_order->totales}}</h5>
      </div>
    </div>

    <footer>
      <div style="text-align:center; text-transform: uppercase;">
        <p><b>www.bazarsantasofia.com</b></p>
        <p>Calle Zaporte 2074, Col. Hogares de Nuevo México, Zapopan, Jal. C.P. 45130</p>
        <p>Tel: 31656127 - 13703774 - 38335091 - 3338335091 - 3331656127 - 3333656254 - 3313703774 - 3313703773 - 3322627045</p>
        <p>ventasantasofia@hotmail.com</p>
      </div>
    </footer>
  
  </div>

</body>

</html>
