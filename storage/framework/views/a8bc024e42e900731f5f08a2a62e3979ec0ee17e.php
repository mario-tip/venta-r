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
                        <?php echo e($fecha->toDateString()); ?>

                    </h3>
                </div>
                <div class="col-xs-4">
                    <h3 >
                        <strong>
                            <?php if($margen_dos < 21): ?>
                           Verificar margen
                            <?php endif; ?>
                        </strong>

                    </h3>
                </div>
                <div class="col-xs-4">

                    <h3 class=" ">
                        <strong>
                            Folio:
                        </strong>
                    <?php echo e($folio); ?>

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
                           <?php echo e($cliente); ?>

                        </h4>
                        <h4>
                            <strong>
                                Asesor:
                            </strong>
                        </h4>
                        <h4>
                            <?php echo e($asesor); ?>

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
                            $ <?php echo e(number_format($tipo_cambio,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-2">
                        <h4>
                            <strong>
                                Suaje:
                            </strong>
                        </h4>
                        <h4>
                            <?php echo e($suaje['numero']); ?>

                        </h4>
                    </div>
                    <div class="col-xs-1">
                        <h4>
                            <strong>
                                Ancho:
                            </strong>
                        </h4>
                        <h4>
                            <?php echo e($suaje['ancho']); ?>

                        </h4>
                    </div>
                    <div class="col-xs-2">
                        <h4>
                            <strong>
                                Largo:
                            </strong>
                        </h4>
                        <h4>
                            <?php echo e($suaje['largo']); ?>


                        </h4>
                    </div>
                    <div class="col-xs-3">
                        <h4>
                            <strong>
                                Rep. al paso:
                            </strong>
                        </h4>
                        <h4>
                             <?php echo e($suaje['repeticion_paso']); ?>

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
                        <?php $__currentLoopData = $sistema_impresion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $impresion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <h4>
                           <?php echo e($impresion['metodo']); ?>

                        </h4>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                                    <?php $__currentLoopData = $material; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materiales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php echo e($materiales['clave_interna']); ?>

                                        </td>
                                        <td>
                                            <?php echo e($materiales['nombre']); ?>



                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                           $ <?php echo e(number_format($costo_m2_mp,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Millares:
                            </strong>
                        </h4>
                        <h4>
                            <?php echo e(number_format($millares_etiqueta,0)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Tintas y máquina:
                            </strong>
                        </h4>
                        <h4>
                            <?php echo e($tintas_maquinas['numero_tinta'].' en '.$tintas_maquinas['modelo']); ?>

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
                            $ <?php echo e(number_format($precio_venta,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Comisiones a terceros:
                            </strong>
                        </h4>
                        <h4>
                            $ <?php echo e(number_format($comision_terceros,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Diseño sin cargo:
                            </strong>
                        </h4>
                        <h4>
                            $ <?php echo e(number_format($costo_diseno,2)); ?>

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
                           $ <?php echo e(number_format($fletes_sin_cargo,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Financieros adicionales:
                            </strong>
                        </h4>
                        <h4>
                           $ <?php echo e(number_format($costo_financiero,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Otros cargos:
                            </strong>
                        </h4>
                        <h4>
                          $ <?php echo e(number_format($otros_costos,2)); ?>

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
                            <?php echo e(number_format($comision_negociada,1)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Carga social:
                            </strong>
                        </h4>
                        <h4>
                           <?php echo e($carga_social); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Area por millar:
                            </strong>
                        </h4>
                        <h4>
                           <?php echo e(number_format($suaje['area'],2)); ?>

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
                            $ <?php echo e(number_format($costos_mp,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Costo fabricación:
                            </strong>
                        </h4>
                        <h4>
                            $ <?php echo e(number_format($costo_fabricacion,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Costos extras:
                            </strong>
                        </h4>
                        <h4>
                            $ <?php echo e(number_format($costos_extras,2)); ?>

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
                            $ <?php echo e(number_format($merma_general,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Factor de venta:
                            </strong>
                        </h4>
                        <h4>
                            $ <?php echo e(number_format($factor_venta,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Facturacón:
                            </strong>
                        </h4>
                        <h4>
                            $ <?php echo e(number_format($facturacion,2)); ?>

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
                            $ <?php echo e(number_format($costo_total,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Margen $:
                            </strong>
                        </h4>
                        <h4>
                            $ <?php echo e(number_format($margen_uno,2)); ?>

                        </h4>
                    </div>
                    <div class="col-xs-4">
                        <h4>
                            <strong>
                                Margen %:
                            </strong>
                        </h4>
                        <h4>
                            <?php echo e(number_format($margen_dos,2)); ?> %
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
                            <?php echo e($comentario); ?>

                        </h4>
                    </div>

                </div>
            </section>
        </div>
    </body>
</html>
