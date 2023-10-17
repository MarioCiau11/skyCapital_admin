<?php

use App\Http\Controllers\agrup\AgentesCategoriaController;
use App\Http\Controllers\agrup\AgentesGrupoController;
use App\Http\Controllers\agrup\ArticulosCategoriaController;
use App\Http\Controllers\agrup\ArticulosGrupoController;
use App\Http\Controllers\agrup\ClientesCategoriaController;
use App\Http\Controllers\agrup\ClientesGrupoController;
use App\Http\Controllers\agrup\ProyectosCategoriaController;
use App\Http\Controllers\agrup\ProyectosGrupoController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\catalogs\AgentesVentaController;
use App\Http\Controllers\catalogs\ArticulosController;
use App\Http\Controllers\catalogs\ClientesController;
use App\Http\Controllers\catalogs\CuentasDineroController;
use App\Http\Controllers\catalogs\EmpresasController;
use App\Http\Controllers\catalogs\EtiquetasController;
use App\Http\Controllers\catalogs\InstitucionesController;
use App\Http\Controllers\catalogs\ModulosController;
use App\Http\Controllers\catalogs\PromocionesController;
use App\Http\Controllers\catalogs\ProyectosController;
use App\Http\Controllers\catalogs\SucursalesController;
use App\Http\Controllers\config\AutorizacionesController;
use App\Http\Controllers\config\ConceptosModulosController;
use App\Http\Controllers\config\CondicionesCreditoController;
use App\Http\Controllers\config\FormasPagoController;
use App\Http\Controllers\config\MonedasController;
use App\Http\Controllers\config\ParametrosGeneralesController;
use App\Http\Controllers\config\RolesController;
use App\Http\Controllers\config\UnidadesController;
use App\Http\Controllers\config\UsuariosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\helpers\FilesController;
use App\Http\Controllers\helpers\FlujoController;
use App\Http\Controllers\helpers\HelpController;
use App\Http\Controllers\helpers\ImagesController;
use App\Http\Controllers\helpers\ProcesosController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\login\LoginController;
use App\Http\Controllers\proc\comercial\AnexosVentasController;
use App\Http\Controllers\proc\comercial\reportesVentasController;
use App\Http\Controllers\proc\finanzas\AnexosTesoreriaController;
use App\Http\Controllers\proc\finanzas\reportesFinanzasController;
use App\Http\Controllers\proc\comercial\VentasController;
use App\Http\Controllers\proc\finanzas\CxController;
use App\Http\Controllers\proc\finanzas\AnexosCxController;
use App\Http\Controllers\proc\finanzas\TesoreriaController;
use App\Http\Controllers\reports\ReportesVentaController;
use App\Http\Controllers\reports\ReportesCxCController;
use App\Http\Controllers\reports\ReportesTesoreriaController;
use App\Http\Controllers\tools\facturacionController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

//Rutas de configuracion
Route::middleware(['auth'])->group(function () {
    Route::resource('/', DashboardController::class)->names("dashboard.index");
    Route::resource('condiciones-credito', CondicionesCreditoController::class)->names("config.condiciones-credito");
    Route::resource('formas-pago', FormasPagoController::class)->names("config.formas-pago");
    Route::resource('monedas', MonedasController::class)->names("config.monedas");
    Route::resource('roles', RolesController::class)->names("config.roles");
    Route::resource('usuario', UsuariosController::class)->names("config.usuarios");
    Route::resource('unidades', UnidadesController::class)->names("config.unidades");
    Route::resource('autorizaciones', AutorizacionesController::class)->names("config.autorizaciones");
    Route::resource('parametros-generales', ParametrosGeneralesController::class)->names("config.parametros-generales");
    Route::resource('conceptos-modulos',ConceptosModulosController::class)->names("config.conceptos-modulos");

    // filtros
    Route::post('/usuarios/filtro', [UsuariosController::class, 'userAction'])->name('config.usuarios.filtro');
    Route::post('/roles/filtro', [RolesController::class, 'roleAction'])->name('config.roles.filtro');
    Route::post('/condiciones-credito/filtro', [CondicionesCreditoController::class, 'condicionAction'])->name('config.condiciones-credito.filtro');
    Route::post('/formas-pago/filtro', [FormasPagoController::class, 'formasAction'])->name('config.formas-pago.filtro');
    Route::post('/monedas/filtro',[MonedasController::class,'monedaAction'])->name('config.monedas.filtro');
    Route::post('/unidades/filtro', [UnidadesController::class,'UnidadesAction'])->name('config.unidades.filtro');
    Route::post('/conceptos-modulos/filtro', [ConceptosModulosController::class,'conceptoAction'])->name('config.conceptos-modulos.filtro');

    //ajax
    Route::get('/getMovimientos',[ApiController::class,'getMovimientos']);
    Route::get('/getClaveProd',[ApiController::class,'cat_getClaveProd']);
});

//Rutas de catalogos
Route::middleware(['auth'])->group(function () {
    Route::resource('empresas', EmpresasController::class)->names("cat.empresas");
    Route::resource('sucursales', SucursalesController::class)->names("cat.sucursales");
    Route::resource('clientes', ClientesController::class)->names("cat.clientes");
    Route::resource('articulos', ArticulosController::class)->names("cat.articulos");
    Route::resource('cuentas-dinero', CuentasDineroController::class)->names("cat.cuentas-dinero");
    Route::resource('asesor-comercial', AgentesVentaController::class)->names("cat.agentes-venta");
    Route::resource('etiquetas', EtiquetasController::class)->names("cat.etiquetas");
    Route::resource('promociones', PromocionesController::class)->names("cat.promociones");
    Route::resource('instituciones', InstitucionesController::class)->names("cat.instituciones");
    Route::resource('modulos', ModulosController::class)->names("cat.modulos");
    Route::resource('proyectos', ProyectosController::class)->names("cat.proyectos");

    //filtros
    Route::post('/empresas/filtro', [EmpresasController::class, 'empresaAction'])->name('cat.empresas.filtro');
    Route::post('/sucursales/filtro', [SucursalesController::class, 'sucursalAction'])->name('cat.sucursales.filtro');
    Route::post('/etiquetas/filtro', [EtiquetasController::class, 'etiquetaAction'])->name('cat.etiquetas.filtro');
    Route::post('/promociones/filtro', [PromocionesController::class, 'promocionAction'])->name('cat.promociones.filtro');
    Route::post('/agentes-venta/filtro',[AgentesVentaController::class,'agentesAction'])->name('cat.agentes-venta.filtro');
    Route::post('/articulos/filtro', [ArticulosController::class,'articulosAction'])->name('cat.articulos.filtro');
    Route::post('/modulos/filtro', [ModulosController::class, 'moduloAction'])->name('cat.modulos.filtro');
    Route::post('/proyectos/filtro', [ProyectosController::class, 'proyectoAction'])->name('cat.proyectos.filtro');
    Route::post('/clientes/filtro', [ClientesController::class,'clientesAction'])->name('cat.clientes.filtro');
    Route::post('/cuentas-dinero/filtro', [CuentasDineroController::class,'cuentasDineroAction'])->name('cat.cuentas-dinero.filtro');
    Route::post('/instituciones/filtro', [InstitucionesController::class, 'institucionAction'])->name('cat.instituciones.filtro');

    //Agrupadores Agentes
    Route::resource('grupo',AgentesGrupoController::class)->names("agrup.grupo");
    Route::resource('categoria',AgentesCategoriaController::class)->names("agrup.categoria");
    //Agrupadores Articulos
    Route::resource('Articulos-Categoria', ArticulosCategoriaController::class)->names('agrup.articulos.categoria');
    Route::resource('Articulos-Grupo',ArticulosGrupoController::class)->names('agrup.articulos.grupo');
    //Agrupadores Clientes
    Route::resource('Clientes-Categoria',ClientesCategoriaController::class)->names('agrup.clientes.categoria');
    Route::resource('Clientes-Grupo',ClientesGrupoController::class)->names('agrup.clientes.grupo');
    //Agrupadores Proyectos
    Route::resource('Proyectos-Categoria', ProyectosCategoriaController::class)->names('agrup.proyectos.categoria');
    Route::resource('Proyectos-Grupo',ProyectosGrupoController::class)->names('agrup.proyectos.grupo');
    //Auxiliares
    Route::get('/eliminar/img/', [ImagesController::class, 'eliminarImagen'])->name('eliminar.imagen');
    Route::get('/eliminar/doc/', [FilesController::class, 'eliminarDoc'])->name('eliminar.doc');

    Route::get('/descargar/doc/{file}', [FilesController::class, 'descargarDoc'])->name('descargar.doc');
});

Route::get('/descargarDocPortal/{file}', [FilesController::class, 'descargarDocPortal'])->name('descargar.descargarDocPortal');
Route::get('/descargarAnexosPortal/{file}', [FilesController::class, 'descargarAnexosPortal'])->name('descargar.descargarAnexosPortal');


//Rutas de procesos    
Route::middleware(['auth'])->group(function () {
    //ventas
    Route::resource('ventas', VentasController::class)->names("proc.ventas");
    Route::post('/ventas/afectar', [VentasController::class, 'afectar'])->name('proc.ventas.afectar');
    Route::post('/ventas/filtro', [VentasController::class, 'ventasAction'])->name('proc.ventas.filtro');
    Route::post('/ventas/generarFactura', [VentasController::class, 'generarFactura'])->name('proc.ventas.generarFactura');
    Route::post('/ventas/cancelar', [VentasController::class, 'cancelar'])->name('proc.ventas.cancelar');
    Route::post('/ventas/eliminar', [VentasController::class, 'destroy'])->name('proc.ventas.delete');
    Route::post('/ventas/copiar', [VentasController::class, 'copy'])->name('proc.ventas.copy');
    Route::post('/ventas/guardarFactura',[VentasController::class, 'storeFactura'])->name('proc.ventas.guardarFactura');
    //cxc 
    Route::resource('cxc', CxController::class)->names("proc.cxc");
    Route::post('/cxc/afectar', [CxController::class, 'afectar'])->name('proc.cxc.afectar');
    Route::post('/cxc/filtro', [CxController::class, 'cxcAction'])->name('proc.cxc.filtro');
    Route::post('/cxc/generarAplicacion', [CxController::class, 'generarAplicacion'])->name('proc.cxc.generarAplicacion');
    Route::post('/cxc/generarCobro', [CxController::class, 'generarCobro'])->name('proc.cxc.generarCobro');
    Route::post('/cxc/cancelar', [CxController::class, 'cancelar'])->name('proc.cxc.cancelar');
    Route::post('/cxc/eliminar', [CxController::class, 'destroy'])->name('proc.cxc.delete');
    Route::post('/cxc/copiar', [CxController::class, 'copy'])->name('proc.cxc.copy');
    //tesoreria
    Route::resource('tesoreria', TesoreriaController::class)->names("proc.tesoreria");
    Route::post('/tesoreria/filtro', [TesoreriaController::class, 'tesoreriaAction'])->name('proc.tesoreria.filtro');
    Route::post('/tesoreria/afectar', [TesoreriaController::class, 'afectar'])->name('proc.tesoreria.afectar');
    Route::post('/tesoreria/eliminar', [TesoreriaController::class, 'destroy'])->name('proc.tesoreria.delete');
    Route::post('/tesoreria/copiar', [TesoreriaController::class, 'copy'])->name('proc.tesoreria.copy');
    Route::post('/tesoreria/cancelar', [TesoreriaController::class, 'cancelar'])->name('proc.tesoreria.cancelar');
   
    //rutas de anexos
    Route::get('/ventas/show/anexo/{id?}', [AnexosVentasController::class, 'index'])->name('proc.venta.viewAnexo');
    Route::post('/ventas/create/anexo/', [AnexosVentasController::class, 'store'])->name('proc.venta.createAnexo');

    Route::get('/cxc/show/anexo/{id?}', [AnexosCxController::class, 'index'])->name('proc.cxc.viewAnexo');
    Route::post('/cxc/create/anexo/', [AnexosCxController::class, 'store'])->name('proc.cxc.createAnexo');
    Route::get('/tesoreria/show/anexo/{id?}', [AnexosTesoreriaController::class, 'index'])->name('proc.tesoreria.viewAnexo');
    Route::post('/tesoreria/create/anexo/', [AnexosTesoreriaController::class, 'store'])->name('proc.tesoreria.createAnexo');
    Route::get('/eliminar/anexo/', [FilesController::class, 'eliminarAnexo'])->name('eliminar.anexo');
    Route::get('/descargar/anexo/{file}', [FilesController::class, 'descargarAnexo'])->name('descargar.anexo');
    
    //Reporte contrato
    Route::get('/reporte/contrato-factura/{id?}', [reportesVentasController::class,'repContrato'])->name('proc.contrato');
    //Reporte financiamiento
    Route::get('/reporte/financiamiento/{id?}', [reportesVentasController::class,'repFinanc'])->name('proc.financ');
    //Reporte comisiones
    Route::get('/reporte/comisiones/{id?}', [reportesVentasController::class,'repComisiones'])->name('proc.comisiones');
    //Reporte cxc
    Route::get('/reporte/cxc/{mov}/{id?}', [reportesFinanzasController::class,'repCxC'])->name('proc.cxc.reporte');
    //reporte tesoreria
    Route::get('/reporte/tesoreria/{mov}/{id?}',[reportesFinanzasController::class,'reporteTesoreria'])->name('proc.tesoreria.reporte');
    
    
    //rutas de flujo
    Route::get('/getFlujo',[FlujoController::class,'getMovimientosPosteriores'])->name('getFlujo');
    
    //rutas de ayuda procesos
    Route::post('/tipoCambio', [ProcesosController::class, 'tipoCambio'])->name('proc.tipoCambio');
    Route::post('/getModulos', [ProcesosController::class, 'getModulos'])->name('proc.getModulos');
    Route::get('/obtenerModulos', [ProcesosController::class, 'obtenerModulos'])->name('proc.obtenerModulos');
    Route::post('/getModulo', [ProcesosController::class, 'getModulo'])->name('proc.getModulo');
    Route::post('/getCuenta', [ProcesosController::class, 'getCuenta'])->name('proc.getCuenta');
    Route::post('/getArticulo', [ProcesosController::class, 'getArticulo'])->name('proc.getArticulo');
    Route::post('/getCondicion', [ProcesosController::class, 'getCondicion'])->name('proc.getCondicion');
    Route::post('/getFormaPago', [ProcesosController::class, 'getFormaPago'])->name('proc.getFormaPago');
    Route::get('/getAgentes', [ProcesosController::class,'retornarAgentes'])->name('getAgentes');
    Route::post('/getCliente', [ProcesosController::class, 'getCliente'])->name('proc.getCliente');
    Route::post('/getSaldoCliente', [ProcesosController::class, 'getSaldoCliente'])->name('proc.getSaldoCliente');
    Route::get('/getPlanVenta', [ProcesosController::class, 'getPlanVenta'])->name('proc.getPlanVenta');
    Route::get('/getMovCxC', [ProcesosController::class, 'getMovCxC'])->name('proc.getMovCxC');
    Route::get('/avisoPorVencer', [HelpController::class, 'avisoPorVencer'])->name('proc.avisoPorVencer');
    Route::get('/avisoVencido', [HelpController::class, 'avisoVencido'])->name('proc.avisoVencido');
    Route::get('/diasMoratorios', [HelpController::class, 'diasMoratorios'])->name('proc.diasMoratorios');
    Route::get('/getSaldoCuenta',[ProcesosController::class,'getSaldoCuenta'])->name('proc.getSaldoCuenta');
});

//Rutas de reportes
Route::middleware(['auth'])->group(function () {
    Route::get('/reportes/ventas/info-modulos', [ReportesVentaController::class, 'infoMod'])->name("report.ventas.info");
    Route::get('/reportes/ventas/venta-modulos', [ReportesVentaController::class, 'ventaMod'])->name("report.ventas.venta");
    Route::get('/reportes/cxc/antigüedad-saldos', [ReportesCxCController::class, 'cxcSaldos'])->name("report.cxc.saldos");
    Route::get('/reportes/cxc/estado-cuenta', [ReportesCxCController::class, 'cxcEstado'])->name("report.cxc.estado");
    Route::get('/reportes/cxc/ingresos-proyecto', [ReportesCxCController::class, 'cxcIngresos'])->name("report.cxc.ingresos");
    Route::get('/reportes/tesoreria/concentrado', [ReportesTesoreriaController::class, 'tesoreriaConc'])->name("report.tesoreria.conc");
    Route::get('/reportes/tesoreria/desglosado', [ReportesTesoreriaController::class, 'tesoreriaDesg'])->name("report.tesoreria.desg");
    //filtros
    Route::post('/reportes/ventas', [ReportesVentaController::class, 'reportesAction'])->name('report.ventas.filtro');
    Route::post('/reportes/cxc', [ReportesCxCController::class, 'reportesAction'])->name('report.cxc.filtro');
    Route::post('/reportes/tesoreria', [ReportesTesoreriaController::class, 'reportesAction'])->name('report.tesoreria.filtro');
});
Route::get('/facturacion', [facturacionController::class, 'index'])->name('tools.facturacion');
Route::post('/facturacion/filtro', [facturacionController::class, 'facturacionAction'])->name('tools.facturacion.filtro');
Route::post('/facturacion/generar', [facturacionController::class, 'generar'])->name('tools.facturacion.generar');

Route::get('/vista', [reportesVentasController::class, 'index'])->name('vista');
//Rutas de login
Route::post('/login/verificar', [LoginController::class, 'verificarUsuario'])->name('login.verificar');
Route::post('/login/passVerificar', [LoginController::class, 'verificarPassword'])->name('login.passVerificar');
Route::post('/login/sucursales', [LoginController::class, 'getSucursales'])->name('login.getSucursales');

Route::post('/upload', [ImageController::class, 'upload'])->name('image.upload');
Route::get('/getImagenesModulo', [ProcesosController::class, 'getImagenesModulo'])->name('image.getImagenesModulo');       
Route::get('/getDocumentosModulo', [ProcesosController::class, 'getDocumentosModulo'])->name('image.getDocumentosModulo');       
Route::post('/anexarDocumentosPortal', [ProcesosController::class, 'anexarDocumentosPortal'])->name('anexarDocumentosPortal');
Route::get('/resizedImage',[ImagesController::class,'resizeImage'])->name('resizeImage');
Route::get('/ShowMail',[HelpController::class,'index'])->name('ShowMail');