<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sistema\LoginController;
use App\Http\Controllers\Sistema\ControlController;
use App\Http\Controllers\Sistema\RolesController;
use App\Http\Controllers\Sistema\PerfilController;
use App\Http\Controllers\Sistema\PermisoController;
use App\Http\Controllers\Sistema\ConfiguracionController;
use App\Http\Controllers\Sistema\BitacorasController;
use App\Http\Controllers\Sistema\TicketController;


Route::get('/', [LoginController::class,'vistaLoginForm'])->name('login.admin');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

// --- CONTROL WEB ---
Route::get('/panel', [ControlController::class,'indexRedireccionamiento'])->name('admin.panel');

// --- ROLES ---
Route::get('/admin/roles/index', [RolesController::class,'index'])->name('admin.roles.index');
Route::get('/admin/roles/tabla', [RolesController::class,'tablaRoles']);
Route::get('/admin/roles/lista/permisos/{id}', [RolesController::class,'vistaPermisos']);
Route::get('/admin/roles/permisos/tabla/{id}', [RolesController::class,'tablaRolesPermisos']);
Route::post('/admin/roles/permiso/borrar', [RolesController::class, 'borrarPermiso']);
Route::post('/admin/roles/permiso/agregar', [RolesController::class, 'agregarPermiso']);
Route::get('/admin/roles/permisos/lista', [RolesController::class,'listaTodosPermisos']);
Route::get('/admin/roles/permisos-todos/tabla', [RolesController::class,'tablaTodosPermisos']);
Route::post('/admin/roles/borrar-global', [RolesController::class, 'borrarRolGlobal']);

// --- PERMISOS ---
Route::get('/admin/permisos/index', [PermisoController::class,'index'])->name('admin.permisos.index');
Route::get('/admin/permisos/tabla', [PermisoController::class,'tablaUsuarios']);
Route::post('/admin/permisos/nuevo-usuario', [PermisoController::class, 'nuevoUsuario']);
Route::post('/admin/permisos/info-usuario', [PermisoController::class, 'infoUsuario']);
Route::post('/admin/permisos/editar-usuario', [PermisoController::class, 'editarUsuario']);
Route::post('/admin/permisos/nuevo-rol', [PermisoController::class, 'nuevoRol']);
Route::post('/admin/permisos/extra-nuevo', [PermisoController::class, 'nuevoPermisoExtra']);
Route::post('/admin/permisos/extra-borrar', [PermisoController::class, 'borrarPermisoGlobal']);

// --- PERFIL ---
Route::get('/admin/editar-perfil/index', [PerfilController::class,'indexEditarPerfil'])->name('admin.perfil');
Route::post('/admin/editar-perfil/actualizar', [PerfilController::class, 'editarUsuario']);

Route::get('sin-permisos', [ControlController::class,'indexSinPermiso'])->name('no.permisos.index');

// actualizar Tema
Route::post('/admin/actualizar/tema', [ControlController::class, 'actualizarTema'])->name('admin.tema.update');




// === UNIDAD ===
Route::get('/admin/unidad/index', [ConfiguracionController::class,'indexUnidad'])->name('admin.unidad.index');
Route::get('/admin/unidad/tabla', [ConfiguracionController::class,'tablaUnidad']);
Route::post('/admin/unidad/nuevo', [ConfiguracionController::class,'nuevoUnidad']);
Route::post('/admin/unidad/informacion', [ConfiguracionController::class,'informacionUnidad']);
Route::post('/admin/unidad/editar', [ConfiguracionController::class,'actualizarUnidad']);


// REGISTRO - ACCESO Y NOVEDADES
Route::get('/admin/registro/novedades-acceso/index', [BitacorasController::class,'registroNovedadesAcceso'])->name('admin.registro.novedades.acceso.index');
Route::post('/admin/bitacora-novedades/registro', [BitacorasController::class,'guardarNovedadesAcceso']);

Route::get('/admin/bitacora/lista/novedadesacceso/index', [BitacorasController::class,'indexBitacoraNovedadesAcceso'])->name('admin.listado.acceso.novedades');
Route::get('/admin/bitacora/lista/novedadesacceso/tabla', [BitacorasController::class,'tablaBitacoraNovedadesAcceso']);
Route::post('/admin/bitacora/novedadesacceso/informacion', [BitacorasController::class,'informacionNovedadesAcceso']);
Route::post('/admin/bitacora/novedadesacceso/actualizar', [BitacorasController::class,'actualizarNovedadesAcceso']);


// REGISTRO - MANTENIMIENTO
Route::get('/admin/registro/mantenimiento/index', [BitacorasController::class,'registroBitacoraMantenimiento'])->name('admin.registro.mantenimiento.index');
Route::post('/admin/bitacora-mantenimiento/registro', [BitacorasController::class,'guardarMantenimiento']);

Route::get('/admin/bitacora/lista/mantenimiento/index', [BitacorasController::class,'indexBitacoraMantenimiento'])->name('admin.listado.mantenimientos');
Route::get('/admin/bitacora/lista/mantenimiento/tabla', [BitacorasController::class,'tablaBitacoraMantenimiento']);
Route::post('/admin/bitacora/mantenimiento/informacion', [BitacorasController::class,'informacionMantenimiento']);
Route::post('/admin/bitacora/mantenimiento/actualizar', [BitacorasController::class,'actualizarMantenimiento']);

// REGISTRO - INCIDENCIAS
Route::get('/admin/registro/incidencias/index', [BitacorasController::class,'registroBitacoraIncidencias'])->name('admin.registro.incidencias.index');
Route::post('/admin/bitacora-incidencias/registro', [BitacorasController::class,'guardarIncidencias']);

// vista para ver todos
Route::get('/admin/bitacora/lista/incidencias/index', [BitacorasController::class,'indexBitacoraIncidencias'])->name('admin.listado.incidencias');
Route::get('/admin/bitacora/lista/incidencias/tabla', [BitacorasController::class,'tablaBitacoraIncidencias']);
Route::post('/admin/bitacora/incidencias/informacion', [BitacorasController::class,'informacionIncidencias']);
Route::post('/admin/bitacora/incidencias/actualizar', [BitacorasController::class,'actualizarIncidencias']);


// SOPORTE

Route::get('/admin/registro/soporte/index', [BitacorasController::class,'registroBitacoraSoporte'])->name('admin.registro.soporte.index');
Route::post('/admin/bitacora-soporte/registro', [BitacorasController::class,'guardarSoporte']);

Route::get('/admin/bitacora/lista/soporte/index', [BitacorasController::class,'indexBitacoraSoporte'])->name('admin.listado.soporte');
Route::get('/admin/bitacora/lista/soporte/tabla', [BitacorasController::class,'tablaBitacoraSoporte']);
Route::post('/admin/bitacora/soporte/informacion', [BitacorasController::class,'informacionSoporte']);
Route::post('/admin/bitacora/soporte/actualizar', [BitacorasController::class,'actualizarSoporte']);


// ADMIN GENERAR REPORTES TODOS
Route::get('/admin/todos/vista/reportes', [BitacorasController::class,'vistaReportesTodos'])->name('admin.reportes.todos.index');
Route::post('admin/generar/reporte/pdf',
    [BitacorasController::class, 'generarPdf']
)->name('admin.generar.reporte.pdf');



// TICKET
Route::get('/admin/ticket/generar/index', [TicketController::class,'indexGenerarTicket'])->name('admin.ticket.generar.index');
Route::post('/admin/ticket/registro', [TicketController::class,'generarTicket']);

Route::get('/admin/ticket/incidencias/index', [TicketController::class,'indexTicketPendiente'])->name('admin.ticket.pendiente.index');
Route::get('/admin/ticket-incidencias/tabla', [TicketController::class,'tablaTicketPendiente']);
Route::post('/admin/ticket-incidencias/solucionado', [TicketController::class,'solucionarIncidencia']);

Route::get('/admin/ticket/incidencias-porrevisar/index', [TicketController::class,'indexTicketPendientePorRevisar'])->name('admin.ticket.incidencias.porrevisar.index');
Route::get('/admin/ticket-incidencias-porrevisar/tabla', [TicketController::class,'tablaTicketPendientePorRevisar']);
Route::post('/admin/ticket-incidencias/actualizar/porrevisar', [TicketController::class,'revisadoTicketCompletado']);









