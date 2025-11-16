<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
                // Iniciar la consulta
        $query = Product::query();
        
        // 🔍 FILTRO: Búsqueda en nombre (like)
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('nombre', 'like', "%{$searchTerm}%");
        }
        
        // 🏷️ FILTRO: Por categoría específica
        if ($request->has('categoria') && !empty($request->categoria)) {
            $query->where('categoria', $request->categoria);
        }
        
        // 💰 FILTRO: Rango de precios
        if ($request->has('precio_min')) {
            $query->where('precio_actual', '>=', $request->precio_min);
        }
        if ($request->has('precio_max')) {
            $query->where('precio_actual', '<=', $request->precio_max);
        }
        
        // ✅ FILTRO: Solo productos disponibles
        if ($request->has('disponible')) {
            $query->where('disponible', $request->boolean('disponible'));
        }
        
        // ⭐ FILTRO: Valoración mínima
        if ($request->has('valoracion_min')) {
            $query->where('valoracion', '>=', $request->valoracion_min);
        }
        
        // 📊 OBTENER resultados
        $productos = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $productos,
            'message' => 'Productos obtenidos exitosamente',
            'count' => $productos->count(),
            'filters_applied' => $request->all() // Mostrar filtros usados
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar el producto por ID
        $producto = Product::find($id);
        
        // Si no existe, devolver error 404
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
        
        // Si existe, devolver el producto
        return response()->json([
            'success' => true,
            'data' => $producto,
            'message' => 'Producto obtenido exitosamente'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
 * Display products that are on sale (current price < original price)
 * Muestra productos que están en oferta (precio_actual < precio_original)
 */
public function offers(Request $request)
{
    // Consulta para productos en oferta
    $query = Product::whereColumn('precio_actual', '<', 'precio_original');
    
    // 🎯 Calcular el porcentaje de descuento (usando DB::raw)
    $productos = $query->get()->map(function ($producto) {
        $descuento = (($producto->precio_original - $producto->precio_actual) / $producto->precio_original) * 100;
        
        return [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio_original' => $producto->precio_original,
            'precio_actual' => $producto->precio_actual,
            'descuento_porcentaje' => round($descuento, 2), // 25.50%
            'descuento_monto' => $producto->precio_original - $producto->precio_actual,
            'categoria' => $producto->categoria,
            'imagen_url' => $producto->imagen_url,
            'valoracion' => $producto->valoracion,
            'en_oferta' => true
        ];
    });
    
    // 📊 Ordenar por mayor descuento
    $productos = $productos->sortByDesc('descuento_porcentaje')->values();
    
    return response()->json([
        'success' => true,
        'data' => $productos,
        'message' => 'Ofertas obtenidas exitosamente',
        'count' => $productos->count(),
        'total_ofertas' => $productos->count()
    ], 200);
}
}
