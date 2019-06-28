<!DOCTYPE html >
<html lang="en">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
        <title>
            Tren
        </title>
        <!-- Latest compiled and minified CSS -->
        <link crossorigin="anonymous" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" rel="stylesheet"/>
        <!-- Optional theme -->
        <link crossorigin="anonymous" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" rel="stylesheet"/>
        <!-- Latest compiled and minified JavaScript -->
        <script crossorigin="anonymous" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
        </script>
        <link href="css/invoice.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-4">
                    <h3>
                        <strong>
                            Fecha:
                        </strong>
                        {{$fecha->toDateString()}}
                    </h3>
                </div>
                <div class="col-xs-4">
                    <h3 >
                        <strong>
                            @if($margen_dos < 21)
                           Verificar margen
                            @endif
                        </strong>

                    </h3>
                </div>
                <div class="col-xs-4">

                    <h3 class=" ">
                        <strong>
                            Folio:
                        </strong>
                    {{$folio}}
                    </h3>

                </div>
            </div>
            <header class="" id="header">
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <img alt="" height="80" src="img/logosAocEeo.png" width="250">
                        </img>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <h4>
                            <strong>
                                Cliente:
                            </strong>
                        </h4>
                        <h4>
                           {{$cliente}}
                        </h4>
                        <h4>
                            <strong>
                                Asesor:
                            </strong>
                        </h4>
                        <h4>
                            {{$asesor}}
                        </h4>
                    </div>
                </div>
            </header>
            <!-- /header -->
            <section>
                <div class="row">
                    <div class="col-xs-3 ">
                        <h4>
                            <strong>
                                Tipo de cambio:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($tipo_cambio,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-2">
                        <h4>
                            <strong>
                                Suaje:
                            </strong>
                        </h4>
                        <h4>
                            {{$suaje['numero']}}
                        </h4>
                    </div>
                    <div class="col-xs-1">
                        <h4>
                            <strong>
                                Ancho:
                            </strong>
                        </h4>
                        <h4>
                            {{$suaje['ancho']}}
                        </h4>
                    </div>
                    <div class="col-xs-2">
                        <h4>
                            <strong>
                                Largo:
                            </strong>
                        </h4>
                        <h4>
                            {{$suaje['largo']}}

                        </h4>
                    </div>
                    <div class="col-xs-3">
                        <h4>
                            <strong>
                                Rep. al paso:
                            </strong>
                        </h4>
                        <h4>
                             {{$suaje['repeticion_paso']}}
                        </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <h3>
                            <strong>
                                Sistemas de impresión
                            </strong>
                        </h3>
                         <h4>
                            Flexografia
                        </h4>
                        @foreach($sistema_impresion as $impresion)

                        <h4>
                           {{$impresion['metodo']}}
                        </h4>
                        @endforeach

                    </div>
                    <div class="col-xs-7">
                        <h3>
                            <strong>
                                Materiales
                            </strong>
                        </h3>
                        <div class="table-responsive">
                            <table border="0" class=" table-condensed ">
                                <thead>
                                    <tr>
                                        <th>
                                            Codigo
                                        </th>
                                        <th>
                                            Descripcion
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($material as $materiales)
                                    <tr>
                                        <td>
                                            {{$materiales['clave_interna']}}
                                        </td>
                                        <td>
                                            {{$materiales['nombre']}}


                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Costo m2 mp:
                            </strong>
                        </h4>
                        <h4>
                           $ {{number_format($costo_m2_mp,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Millares:
                            </strong>
                        </h4>
                        <h4>
                            {{number_format($millares_etiqueta,0)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Tintas y máquina:
                            </strong>
                        </h4>
                        <h4>
                            {{$tintas_maquinas['numero_tinta'].' en '.$tintas_maquinas['modelo']}}
                        </h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Precio de venta:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($precio_venta,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Comisiones a terceros:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($comision_terceros,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Diseño sin cargo:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($costo_diseno,2)}}
                        </h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Fletes sin cargo:
                            </strong>
                        </h4>
                        <h4>
                           $ {{number_format($fletes_sin_cargo,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Financieros adicionales:
                            </strong>
                        </h4>
                        <h4>
                           $ {{number_format($costo_financiero,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Otros cargos:
                            </strong>
                        </h4>
                        <h4>
                          $ {{number_format($otros_costos,2)}}
                        </h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Comisiòn negociada:
                            </strong>
                        </h4>
                        <h4>
                            {{number_format($comision_negociada,1)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Carga social:
                            </strong>
                        </h4>
                        <h4>
                           {{$carga_social}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Area por millar:
                            </strong>
                        </h4>
                        <h4>
                           {{number_format($suaje['area'],2)}}
                        </h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Costo mp:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($costos_mp,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Costo fabricación:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($costo_fabricacion,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Costos extras:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($costos_extras,2)}}
                        </h4>
                    </div>

                </div>
                <div class="row">
                     <div class="col-xs-4">
                        <h4>
                            <strong>
                                Merma:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($merma_general,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Factor de venta:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($factor_venta,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Facturacón:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($facturacion,2)}}
                        </h4>
                    </div>


                </div>
                <div class="row">
                     <div class="col-xs-4">
                        <h4>
                            <strong>
                                Costo total:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($costo_total,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Margen $:
                            </strong>
                        </h4>
                        <h4>
                            $ {{number_format($margen_uno,2)}}
                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Margen %:
                            </strong>
                        </h4>
                        <h4>
                            {{number_format($margen_dos,2)}} %
                        </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                          <h4>
                            <strong>
                               Comentarios:
                            </strong>
                        </h4>
                        <h4>
                            {{$comentario}}
                        </h4>
                    </div>

                </div>
            </section>
        </div>
    </body>
</html>
