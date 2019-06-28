<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>correo</title>
  </head>
  <body>
    <div class="tab-pane" id="tab_facturas">
      <div class="col-md-1 imagenLogo">
        <img
          src="../public/logo.png"
          alt="Logo"
          style="width: 100%;"
        />
      </div>
      <div class="col-md-11 col-sm-12">
        <div class="portlet">
          <div class="portlet-body">
            <div class="form-horizontal">
              <div class="form-body">
                <div class="row">
                  <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                      <label><strong>Nombre:</strong></label>
                      <label class="factura-label">
                        Luis Alberto Gutierrez Gachuz
                      </label>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <div class="form-group">
                      <label><strong>Teléfono:</strong></label>
                      <label class="factura-label">
                        31234567890
                      </label>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <div class="form-group">
                      <label><strong>Estado:</strong></label>
                      <label class="factura-label">
                        Jalisco
                      </label>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <div class="form-group">
                      <label><strong>Municipio:</strong></label>
                      <label class="factura-label">
                        Lagos de Moreno
                      </label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                      <label><strong>Dirección:</strong></label>
                      <label class="factura-label">
                        Av. Enrique Segoviano
                      </label>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <div class="form-group">
                      <label><strong>Código Postal:</strong></label>
                      <label class="factura-label">
                        12345
                      </label>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <div class="form-group">
                      <label><strong>No. exterior:</strong></label>
                      <label class="factura-label">
                        31234
                      </label>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <div class="form-group">
                      <label><strong>No. interior:</strong></label>
                      <label class="factura-label">
                        Jalisco
                      </label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-2 col-sm-6">
                    <div class="form-group">
                      <label><strong>RFC:</strong></label>
                      <label class="factura-label">
                        1234567890123
                      </label>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                      <label><strong>Email:</strong></label>
                      <label class="factura-label">
                        jorge.rodriguez@bazarsantasofia.com.mx
                      </label>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-4">
                    <div class="form-group">
                      <label><strong>No. cotización:</strong></label>
                      <label class="factura-label">
                        31234
                      </label>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-4">
                    <div class="form-group">
                      <label><strong>No. pedido:</strong></label>
                      <label class="factura-label">
                        78964531
                      </label>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-4">
                    <div class="form-group">
                      <label><strong>Fecha:</strong></label>
                      <label class="factura-label">
                        10/23/2019
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="portlet">
        <div class="portlet-body">
          <div class="table-responsive">
            {{-- <table
              ng-table="VC.dataOrders"
              class="table table-striped table-hover"
              shown-filter="true"
            >
              <tr ng-repeat="datos in $data">
                <td
                  title="'Folio'"
                  filter="{folio:'text'}"
                  sortable="'folio'"
                >
                  {{ datos.folio }}
                </td>
                <td
                  title="'Usuario'"
                  filter="{username:'text'}"
                  sortable="'username'"
                >
                  {{ datos.username }}
                </td>
                <td
                  title="'Cliente'"
                  filter="{customer_name:'text'}"
                  sortable="'customer_name'"
                >
                  {{ datos.customer_name }}
                </td>
                <td
                  title="'Fecha'"
                  filter="{created_at:'text'}"
                  sortable="'created_at'"
                >
                  {{ datos.created_at | date: "dd-MM-y" }}
                </td>
                <td
                  title="'Total'"
                  filter="{total:'text'}"
                  sortable="'total'"
                >
                  {{ datos.total | currency }}
                </td>
              </tr>
            </table> --}}
          </div>
          <table class="table">
            
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
