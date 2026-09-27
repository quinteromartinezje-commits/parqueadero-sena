<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use App\Models\TipoVehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para la gestión de tarifas del parqueadero.
 * 
 * Implementa las operaciones CRUD (Create, Read, Update, Delete)
 * para el módulo de Configuración de Tarifas (CU-09).
 * 
 * Casos de uso implementados:
 * - Flujo normal: Crear, listar, actualizar y eliminar tarifas
 * - Flujo alternativo 1a: Validación de valores mayores a $0
 * - Flujo alternativo 2b: Manejo de errores de base de datos
 * 
 * @package App\Http\Controllers
 * @author Parking Como en Casa Team
 * @version 1.0
 */
class TarifaController extends Controller
{
    /**
     * Mostrar la lista de tarifas configuradas.
     * 
     * Este método recupera todas las tarifas de la base de datos
     * junto con su tipo de vehículo asociado y las muestra en
     * la vista de configuración de tarifas.
     * 
     * @return \Illuminate\View\View Vista con la lista de tarifas
     */
    public function index()
    {
        // Obtener todas las tarifas con su relación tipo_vehiculo
        $tarifas = Tarifa::with('tipoVehiculo')->get();
        
        // Obtener todos los tipos de vehículo para el formulario
        $tiposVehiculo = TipoVehiculo::all();
        
        // Retornar la vista con los datos
        return view('tarifas.index', compact('tarifas', 'tiposVehiculo'));
    }

    /**
     * Guardar una nueva tarifa en la base de datos.
     * 
     * Valida que los datos ingresados cumplan con:
     * - Tipo de vehículo existente
     * - Valores mayores a $0 para todas las tarifas
     * - Estado vigente válido (0 o 1)
     * 
     * @param Request $request Datos del formulario
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado
     */
    public function store(Request $request)
    {
        // Validación de datos según CU-09 flujo alternativo 1a
        $validator = Validator::make($request->all(), [
            'id_tipo_vehiculo' => 'required|integer|exists:tipo_vehiculo,id',
            'tarifa_hora' => 'required|numeric|min:0.01',
            'tarifa_fraccion' => 'required|numeric|min:0.01',
            'recargo_nocturno' => 'required|numeric|min:0.01',
            'tarifa_vigente' => 'required|boolean',
        ], [
            'tarifa_hora.min' => 'Los valores ingresados no son válidos. Debe ser mayor a $0.',
            'tarifa_fraccion.min' => 'Los valores ingresados no son válidos. Debe ser mayor a $0.',
            'recargo_nocturno.min' => 'Los valores ingresados no son válidos. Debe ser mayor a $0.',
        ]);

        // Si la validación falla, retornar error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'type' => 'valor_invalido',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Crear la tarifa en la base de datos
            Tarifa::create($request->all());
            
            // Retornar respuesta exitosa
            return response()->json([
                'success' => true,
                'type' => 'exito',
                'message' => 'La tarifa ha sido guardada correctamente.'
            ]);
        } catch (\Exception $e) {
            // Manejo de error de base de datos - CU-09 flujo alternativo 2b
            return response()->json([
                'success' => false,
                'type' => 'error_guardado',
                'message' => 'No se pudo guardar los cambios en este momento. Se mantiene la tarifa anterior.'
            ], 500);
        }
    }

    /**
     * Actualizar una tarifa existente.
     * 
     * Busca la tarifa por ID y actualiza sus valores
     * manteniendo las mismas validaciones que la creación.
     * 
     * @param Request $request Datos del formulario
     * @param int $id ID de la tarifa a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado
     */
    public function update(Request $request, $id)
    {
        // Buscar la tarifa por ID
        $tarifa = Tarifa::find($id);

        // Si no existe, retornar error
        if (!$tarifa) {
            return response()->json([
                'success' => false,
                'message' => 'Tarifa no encontrada.'
            ], 404);
        }

        // Validar datos (misma validación que store)
        $validator = Validator::make($request->all(), [
            'id_tipo_vehiculo' => 'required|integer|exists:tipo_vehiculo,id',
            'tarifa_hora' => 'required|numeric|min:0.01',
            'tarifa_fraccion' => 'required|numeric|min:0.01',
            'recargo_nocturno' => 'required|numeric|min:0.01',
            'tarifa_vigente' => 'required|boolean',
        ], [
            'tarifa_hora.min' => 'Los valores ingresados no son válidos.',
            'tarifa_fraccion.min' => 'Los valores ingresados no son válidos.',
            'recargo_nocturno.min' => 'Los valores ingresados no son válidos.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'type' => 'valor_invalido',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Actualizar la tarifa
            $tarifa->update($request->all());
            
            return response()->json([
                'success' => true,
                'type' => 'exito',
                'message' => 'La tarifa ha sido actualizada correctamente.'
            ]);
        } catch (\Exception $e) {
            // Error de base de datos - mantener tarifa anterior
            return response()->json([
                'success' => false,
                'type' => 'error_guardado',
                'message' => 'No se pudo guardar los cambios. Se mantiene la tarifa anterior.'
            ], 500);
        }
    }

    /**
     * Eliminar una tarifa del sistema.
     * 
     * Elimina permanentemente la tarifa de la base de datos.
     * Esta acción no se puede deshacer.
     * 
     * @param int $id ID de la tarifa a eliminar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado
     */
    public function destroy($id)
    {
        // Buscar la tarifa por ID
        $tarifa = Tarifa::find($id);

        // Si no existe, retornar error
        if (!$tarifa) {
            return response()->json([
                'success' => false,
                'message' => 'Tarifa no encontrada.'
            ], 404);
        }

        try {
            // Eliminar la tarifa
            $tarifa->delete();
            
            return response()->json([
                'success' => true,
                'type' => 'exito',
                'message' => 'Tarifa eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            // Error al eliminar
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la tarifa.'
            ], 500);
        }
    }
}