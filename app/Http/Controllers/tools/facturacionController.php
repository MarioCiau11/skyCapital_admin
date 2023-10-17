<?php

namespace App\Http\Controllers\tools;

use App\Exports\ToolExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\proc\comercial\VentasController;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\proc\comercial\Ventas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class facturacionController extends Controller
{

    public function index()
    {
        $clientes = new CAT_CLIENTES();
        return view(
            'page.tools.facturacion',
            [
                'clientes' => $clientes->getClientes()->toArray(),
            ]
        );
    }

    public function facturacionAction(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $cliente = $data['selectCliente'];
        $tipo = $data['radioContrato'] == 'Venta' ? 1 : 0;
        $movimiento = 'Mensualidad';
        $fecha = 'Rango de fechas';
        $estatus = 'PENDIENTE';
        $sucursal = session('sucursal')->idSucursal;
        $fechaInicio = $data['inputFechaInicio'] == null ? $data['inputFechaInicio'] : $data['inputFechaInicio'];
        $fechaFinal = $data['inputFechaFinal'] == null ? $data['inputFechaFinal'] : $data['inputFechaFinal'];

        // dd($data);
        switch ($request->input('action')) {
            case 'Búsqueda':
                $movimientos_filtro = Ventas::whereVentasCliente($cliente)
                ->whereVentasMovimiento($movimiento)
                ->whereVentasFecha($fecha)
                ->whereVentasTipo($tipo)
                ->whereVentasEstatus($estatus)
                ->whereVentasSucursal($sucursal)
                    ->orderBy('idVenta', 'DESC')
                    ->get();
                // dd($movimientos_filtro);
                return redirect()->route('tools.facturacion')->with('movimientos_filtro', $movimientos_filtro)
                                                             ->with('selectCliente', $cliente)
                                                             ->with('inputFechaInicio', $fechaInicio)
                                                             ->with('inputFechaFinal', $fechaFinal)
                                                                ->with('radioContrato', $data['radioContrato']);
            case 'Exportar excel':
                $facturacion = new ToolExport($cliente, $tipo, $movimiento, $fecha, $estatus, $sucursal);
                return Excel::download($facturacion, 'Facturacion.xlsx');
                break;
        }
    }

    public function generar(Request $request)
    {
        try {
            foreach ($request->movimiento as $movimiento) {
                $facturaNew = $this->generarFacturas($movimiento);

                $venta = new VentasController(new Ventas());
                $venta->concluirFactura($facturaNew);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Error al generar las facturas' . $e->getMessage()]);
        }
        return response()->json(['status' => true, 'message' => 'Facturas generadas correctamente']);
    }


    function generarFacturas($mensualidad)  {
        
        $mensualidad = Ventas::find($mensualidad);

        $factura = $mensualidad->replicate();
        $factura->movimiento = 'Factura';
        $factura->folioMov = null;
        $factura->estatus = 'CONCLUIDO';
        $factura->fechaEmision = Carbon::now();
        $factura->origenId = $mensualidad->idVenta;
        $factura->folioMov = $factura->getFolio($factura);

        if($factura->getCondition->tipoCondicion = 'Contado'){
            $factura->condicionPago = 1;
        }

        $factura->save();

        return $factura;

    }
}