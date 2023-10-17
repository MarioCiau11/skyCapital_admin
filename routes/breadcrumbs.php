<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Crypt;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Inicio', route('dashboard.index.index'));
});

//usuarios
Breadcrumbs::for('config.usuarios.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Usuarios', route('config.usuarios.index'));
});

Breadcrumbs::for('config.usuarios.create', function ($trail) {
    $trail->parent('config.usuarios.index');
    $trail->push('Crear Usuario', route('config.usuarios.create'));
});

Breadcrumbs::for('config.usuarios.show', function ($trail, $user) {
    $trail->parent('config.usuarios.index');
    $trail->push('Ver Usuario', route('config.usuarios.show', Crypt::encrypt($user->user_id)));
});

Breadcrumbs::for('config.usuarios.edit', function ($trail, $user) {
    $trail->parent('config.usuarios.show', $user);
    $trail->push('Editar Usuario', route('config.usuarios.show', Crypt::encrypt($user->user_id)));
});


//roles
//usuarios
Breadcrumbs::for('config.roles.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Roles', route('config.roles.index'));
});

Breadcrumbs::for('config.roles.create', function ($trail) {
    $trail->parent('config.roles.index');
    $trail->push('Crear Rol', route('config.roles.create'));
});

Breadcrumbs::for('config.roles.show', function ($trail, $rol) {
    $trail->parent('config.roles.index');
    $trail->push('Ver Rol', route('config.roles.show', Crypt::encrypt($rol->id)));
});

Breadcrumbs::for('config.roles.edit', function ($trail, $rol) {
    $trail->parent('config.roles.show', $rol);
    $trail->push('Editar Rol', route('config.roles.show', Crypt::encrypt($rol->id)));
});



//condiciones de credito
Breadcrumbs::for('config.condiciones-credito.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Condiciones ', route('config.condiciones-credito.index'));
});

Breadcrumbs::for('config.condiciones-credito.create', function ($trail) {
    $trail->parent('config.condiciones-credito.index');
    $trail->push('Crear Condición ', route('config.condiciones-credito.create'));
});

Breadcrumbs::for('config.condiciones-credito.show', function ($trail, $condicionesCred) {
    $trail->parent('config.condiciones-credito.index');
    $trail->push('Ver Condición ', route('config.condiciones-credito.show', Crypt::encrypt($condicionesCred->idCondicionesc)));
});

Breadcrumbs::for('config.condiciones-credito.edit', function ($trail, $condicionesCred) {
    $trail->parent('config.condiciones-credito.show', $condicionesCred);
    $trail->push('Editar Condición', route('config.condiciones-credito.show', Crypt::encrypt($condicionesCred->idCondicionesc)));
});

//formas de pago

Breadcrumbs::for('config.formas-pago.index',function ($trail){
    $trail->parent('home');
    $trail->push('Formas de Pago',route('config.formas-pago.index'));
});


Breadcrumbs::for('config.formas-pago.create',function ($trail){
    $trail->parent('config.formas-pago.index');
    $trail->push('Crear Forma de Pago',route('config.formas-pago.create'));
});

Breadcrumbs::for('config.formas-pago.show', function ($trail, $formasPago) {
    $trail->parent('config.formas-pago.index');
    $trail->push('Ver Forma de Pago ', route('config.formas-pago.show', Crypt::encrypt($formasPago->idFormaspc)));
});

Breadcrumbs::for('config.formas-pago.edit', function ($trail, $formasPago) {
    $trail->parent('config.formas-pago.show', $formasPago);
    $trail->push('Editar forma de Pago', route('config.formas-pago.show', Crypt::encrypt($formasPago->idFormaspc)));
});

//Monedas
Breadcrumbs::for('config.monedas.index',function ($trail){
    $trail->parent('home');
    $trail->push('Monedas',route('config.monedas.index'));
});


Breadcrumbs::for('config.monedas.create',function ($trail){
    $trail->parent('config.monedas.index');
    $trail->push('Crear Moneda',route('config.monedas.create'));
});

Breadcrumbs::for('config.monedas.show', function ($trail, $monedas) {
    $trail->parent('config.monedas.index');
    $trail->push('Ver Moneda ', route('config.monedas.show', Crypt::encrypt($monedas->idMoneda)));
});

Breadcrumbs::for('config.monedas.edit', function ($trail, $monedas) {
    $trail->parent('config.monedas.show', $monedas);
    $trail->push('Editar Moneda', route('config.monedas.show', Crypt::encrypt($monedas->idMoneda)));
});

//Unidades
Breadcrumbs::for('config.unidades.index',function ($trail){
    $trail->parent('home');
    $trail->push('Unidades',route('config.unidades.index'));
});

Breadcrumbs::for('config.unidades.create',function ($trail){
    $trail->parent('config.unidades.index');
    $trail->push('Crear Unidad',route('config.unidades.create'));
});

Breadcrumbs::for('config.unidades.show', function ($trail, $unidades) {
    $trail->parent('config.unidades.index');
    $trail->push('Ver Unidad ', route('config.unidades.show', Crypt::encrypt($unidades->idUnidades)));
});

Breadcrumbs::for('config.unidades.edit', function ($trail, $unidades) {
    $trail->parent('config.unidades.show', $unidades);
    $trail->push('Editar Unidad', route('config.unidades.show', Crypt::encrypt($unidades->idUnidades)));
});

//Conceptos de módulos
Breadcrumbs::for('config.conceptos-modulos.index',function ($trail){
    $trail->parent('home');
    $trail->push('Conceptos',route('config.conceptos-modulos.index'));
});

Breadcrumbs::for('config.conceptos-modulos.create',function ($trail){
    $trail->parent('config.conceptos-modulos.index');
    $trail->push('Crear Concepto',route('config.conceptos-modulos.create'));
});

Breadcrumbs::for('config.conceptos-modulos.show', function ($trail, $conceptos) {
    $trail->parent('config.conceptos-modulos.index');
    $trail->push('Ver Unidad ', route('config.conceptos-modulos.show', Crypt::encrypt($conceptos->idConceptosm)));
});

Breadcrumbs::for('config.conceptos-modulos.edit', function ($trail, $conceptos) {
    $trail->parent('config.conceptos-modulos.show', $conceptos);
    $trail->push('Editar Concepto', route('config.conceptos-modulos.show', Crypt::encrypt($conceptos->idConceptosm)));
});

//Autorizaciones
Breadcrumbs::for('config.autorizaciones.index',function ($trail){
    $trail->parent('home');
    $trail->push('Autorizaciones',route('config.autorizaciones.index'));
});

Breadcrumbs::for('config.autorizaciones.create',function ($trail){
    $trail->parent('config.autorizaciones.index');
    $trail->push('Crear Autorización',route('config.autorizaciones.create'));
});

//Empresas 
Breadcrumbs::for('cat.empresas.index',function ($trail){
    $trail->parent('home');
    $trail->push('Empresas',route('cat.empresas.index'));
});

Breadcrumbs::for('cat.empresas.create',function ($trail){
    $trail->parent('cat.empresas.index');
    $trail->push('Crear Empresa',route('cat.empresas.create'));
});

Breadcrumbs::for('cat.empresas.show', function ($trail, $Empresa) {
    $trail->parent('cat.empresas.index');
    $trail->push('Ver Empresa', route('cat.empresas.show', Crypt::encrypt($Empresa->idEmpresa)));
});

Breadcrumbs::for('cat.empresas.edit', function ($trail, $Empresa) {
    $trail->parent('cat.empresas.show', $Empresa);
    $trail->push('Editar Empresa', route('cat.empresas.show', Crypt::encrypt($Empresa->id)));
});

//Sucursales
Breadcrumbs::for('cat.sucursales.index',function ($trail){
    $trail->parent('home');
    $trail->push('Sucursales',route('cat.sucursales.index'));
});

Breadcrumbs::for('cat.sucursales.create',function ($trail){
    $trail->parent('cat.sucursales.index');
    $trail->push('Crear Sucursal',route('cat.sucursales.create'));
});

Breadcrumbs::for('cat.sucursales.show', function ($trail, $Sucursal) {
    $trail->parent('cat.sucursales.index');
    $trail->push('Ver Sucursal', route('cat.sucursales.show', Crypt::encrypt($Sucursal->idSucursal)));
});

Breadcrumbs::for('cat.sucursales.edit', function ($trail, $Sucursal) {
    $trail->parent('cat.sucursales.show', $Sucursal);
    $trail->push('Editar Sucursal', route('cat.sucursales.show', Crypt::encrypt($Sucursal->id)));
});

//Agentes de Venta
Breadcrumbs::for('cat.agentes-venta.index',function ($trail){
    $trail->parent('home');
    $trail->push('Asesores',route('cat.agentes-venta.index'));
});

Breadcrumbs::for('cat.agentes-venta.create',function ($trail){
    $trail->parent('cat.agentes-venta.index');
    $trail->push('Crear Asesor',route('cat.agentes-venta.create'));
});

Breadcrumbs::for('cat.agentes-venta.show', function ($trail, $Agente) {
    $trail->parent('cat.agentes-venta.index');
    $trail->push('Ver Asesor', route('cat.agentes-venta.show', Crypt::encrypt($Agente->idAgentes)));
});

Breadcrumbs::for('cat.agentes-venta.edit', function ($trail, $Agente) {
    $trail->parent('cat.agentes-venta.show', $Agente);
    $trail->push('Editar Asesor', route('cat.agentes-venta.show', Crypt::encrypt($Agente->idAgentes)));
});

//Etiquetas
Breadcrumbs::for('cat.etiquetas.index',function ($trail){
    $trail->parent('home');
    $trail->push('Etiquetas',route('cat.etiquetas.index'));
});

Breadcrumbs::for('cat.etiquetas.create',function ($trail){
    $trail->parent('cat.etiquetas.index');
    $trail->push('Crear Etiqueta',route('cat.etiquetas.create'));
});

Breadcrumbs::for('cat.etiquetas.show', function ($trail, $Etiqueta) {
    $trail->parent('cat.etiquetas.index');
    $trail->push('Ver Etiqueta', route('cat.etiquetas.show', Crypt::encrypt($Etiqueta->idEtiqueta)));
});

Breadcrumbs::for('cat.etiquetas.edit', function ($trail, $Etiqueta) {
    $trail->parent('cat.etiquetas.show', $Etiqueta);
    $trail->push('Editar Etiqueta', route('cat.etiquetas.show', Crypt::encrypt($Etiqueta->id)));
});

//Promociones
Breadcrumbs::for('cat.promociones.index',function ($trail){
    $trail->parent('home');
    $trail->push('Promociones',route('cat.promociones.index'));
});

Breadcrumbs::for('cat.promociones.create',function ($trail){
    $trail->parent('cat.promociones.index');
    $trail->push('Crear Promoción',route('cat.promociones.create'));
});

Breadcrumbs::for('cat.promociones.show', function ($trail, $Promocion) {
    $trail->parent('cat.promociones.index');
    $trail->push('Ver Promoción', route('cat.promociones.show', Crypt::encrypt($Promocion->idPromocion)));
});

Breadcrumbs::for('cat.promociones.edit', function ($trail, $Promocion) {
    $trail->parent('cat.promociones.show', $Promocion);
    $trail->push('Editar Promoción', route('cat.promociones.show', Crypt::encrypt($Promocion->id)));
});
//Articulos
Breadcrumbs::for('cat.articulos.index',function ($trail){
    $trail->parent('home');
    $trail->push('Artículos',route('cat.articulos.index'));
});

Breadcrumbs::for('cat.articulos.create',function ($trail){
    $trail->parent('cat.articulos.index');
    $trail->push('Crear Artículo',route('cat.articulos.create'));
});

Breadcrumbs::for('cat.articulos.show', function ($trail, $Articulo) {
    $trail->parent('cat.articulos.index');
    $trail->push('Ver Artículo', route('cat.articulos.show', Crypt::encrypt($Articulo->idArticulos)));
});

Breadcrumbs::for('cat.articulos.edit', function ($trail, $Articulo) {
    $trail->parent('cat.articulos.show', $Articulo);
    $trail->push('Editar Artículo', route('cat.articulos.show', Crypt::encrypt($Articulo->idArticulos)));
});


//Modulos
Breadcrumbs::for('cat.modulos.index',function ($trail){
    $trail->parent('home');
    $trail->push('Módulos',route('cat.modulos.index'));
});

Breadcrumbs::for('cat.modulos.create',function ($trail){
    $trail->parent('cat.modulos.index');
    $trail->push('Crear Módulo',route('cat.modulos.create'));
});

Breadcrumbs::for('cat.modulos.show', function ($trail, $Modulo) {
    $trail->parent('cat.modulos.index');
    $trail->push('Ver Módulo', route('cat.modulos.show', Crypt::encrypt($Modulo->idModulo)));
});

Breadcrumbs::for('cat.modulos.edit', function ($trail, $Modulo) {
    $trail->parent('cat.modulos.show', $Modulo);
    $trail->push('Editar Módulo', route('cat.modulos.show', Crypt::encrypt($Modulo->id)));
});

//clientes
Breadcrumbs::for('cat.clientes.index',function ($trail){
    $trail->parent('home');
    $trail->push('Clientes',route('cat.clientes.index'));
});

Breadcrumbs::for('cat.clientes.create',function ($trail){
    $trail->parent('cat.clientes.index');
    $trail->push('Crear Cliente',route('cat.clientes.create'));
});

Breadcrumbs::for('cat.clientes.show', function ($trail, $Cliente) {
    $trail->parent('cat.clientes.index');
    $trail->push('Ver Cliente', route('cat.clientes.show', Crypt::encrypt($Cliente->idCliente)));
});

Breadcrumbs::for('cat.clientes.edit', function ($trail, $Cliente) {
    $trail->parent('cat.clientes.show', $Cliente);
    $trail->push('Editar Cliente', route('cat.clientes.show', Crypt::encrypt($Cliente->idCliente)));
});

//Proyectos
Breadcrumbs::for('cat.proyectos.index',function ($trail){
    $trail->parent('home');
    $trail->push('Proyectos',route('cat.proyectos.index'));
});

Breadcrumbs::for('cat.proyectos.create',function ($trail){
    $trail->parent('cat.proyectos.index');
    $trail->push('Crear Proyecto',route('cat.proyectos.create'));
});

Breadcrumbs::for('cat.proyectos.show', function ($trail, $Proyecto) {
    $trail->parent('cat.proyectos.index');
    $trail->push('Ver Proyecto', route('cat.proyectos.show', Crypt::encrypt($Proyecto->idProyecto)));
});

Breadcrumbs::for('cat.proyectos.edit', function ($trail, $Proyecto) {
    $trail->parent('cat.proyectos.show', $Proyecto);
    $trail->push('Editar Proyecto', route('cat.proyectos.show', Crypt::encrypt($Proyecto->id)));
});

//Instituciones
Breadcrumbs::for('cat.instituciones.index',function ($trail){
    $trail->parent('home');
    $trail->push('Instituciones Financieras',route('cat.instituciones.index'));
});

Breadcrumbs::for('cat.instituciones.create',function ($trail){
    $trail->parent('cat.instituciones.index');
    $trail->push('Crear Institución',route('cat.instituciones.create'));
});

Breadcrumbs::for('cat.instituciones.show', function ($trail, $institucionFin) {
    $trail->parent('cat.instituciones.index');
    $trail->push('Ver Institución', route('cat.instituciones.show', Crypt::encrypt($institucionFin->idInstitucionf)));
});

Breadcrumbs::for('cat.instituciones.edit', function ($trail, $institucionFin) {
    $trail->parent('cat.instituciones.show', $institucionFin);
    $trail->push('Editar Institución', route('cat.instituciones.show', Crypt::encrypt($institucionFin->idInstitucionf)));
});

//cuentas de dinero
Breadcrumbs::for('cat.cuentas-dinero.index',function ($trail){
    $trail->parent('home');
    $trail->push('Cuentas de Dinero',route('cat.cuentas-dinero.index'));
});

Breadcrumbs::for('cat.cuentas-dinero.create',function ($trail){
    $trail->parent('cat.cuentas-dinero.index');
    $trail->push('Crear Cuenta',route('cat.cuentas-dinero.create'));
});

Breadcrumbs::for('cat.cuentas-dinero.show', function ($trail, $CuentasDinero) {
    $trail->parent('cat.cuentas-dinero.index');
    $trail->push('Ver Cuenta', route('cat.cuentas-dinero.show', Crypt::encrypt($CuentasDinero->idCuentasDinero)));
});

Breadcrumbs::for('cat.cuentas-dinero.edit', function ($trail, $CuentasDinero) {
    $trail->parent('cat.cuentas-dinero.show', $CuentasDinero);
    $trail->push('Editar Cuenta', route('cat.cuentas-dinero.show', Crypt::encrypt($CuentasDinero->idCuentasDinero)));
});

//ventas
Breadcrumbs::for('proc.ventas.index',function ($trail){
    $trail->parent('home');
    $trail->push('Ventas',route('proc.ventas.index'));
});
Breadcrumbs::for('proc.ventas.create',function ($trail){
    $trail->parent('proc.ventas.index');
    $trail->push('Crear Venta',route('proc.ventas.create'));
});

//cxc
Breadcrumbs::for('proc.cxc.index',function ($trail){
    $trail->parent('home');
    $trail->push('Cuentas por Cobrar',route('proc.cxc.index'));
});
Breadcrumbs::for('proc.cxc.create',function ($trail){
    $trail->parent('proc.cxc.index');
    $trail->push('Crear Cuenta por Cobrar',route('proc.cxc.create'));
});

//tesoreria
Breadcrumbs::for('proc.tesoreria.index',function ($trail){
    $trail->parent('home');
    $trail->push('Tesorería',route('proc.tesoreria.index'));
});
Breadcrumbs::for('proc.tesoreria.create',function ($trail){
    $trail->parent('proc.tesoreria.index');
    $trail->push('Crear Movimiento',route('proc.tesoreria.create'));
});

Breadcrumbs::for('tools.facturacion',function ($trail){
    $trail->parent('home');
    $trail->push('Facturación Masiva',route('tools.facturacion'));
});