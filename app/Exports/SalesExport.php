<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromQuery, WithHeadings {

  use Exportable;
  private $from_date;
  private $to_date;

  public function __construct(string $from_date, string $to_date)
  {
    $this->$from_date = $from_date;
    $this->$to_date = $to_date;
  }

    public function query()
    {
      return Order::query()->whereBetween('created_at', ['24-11-2018', '30-11-2018']);
      // whereDate('created_at', '>=', '24-11-2018')->whereDate('created_at', '<=', '30-11-2018' );
    }

    public function fromDate($from_date){
      $this->$from_date = $from_date;
      return $this;
    }

    public function toDate($to_date){
      $this->$to_date = $to_date;
      return $this;
    }

    public function headings(): array
    {
        return [
            '#',
            'Folio',
            'Usuario',
            'cliente',
            'orden ofline',
            'descuento',
            'fecha ofline',
            'pago',
            'cambio',
            'creado',
        ];
    }
}
