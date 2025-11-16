<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * $fillable = "Campos que acepto que me llenen desde fuera"
     * SEGURIDAD: Evita que alguien envíe campos maliciosos
     */
    protected $fillable = [
        'nombre',           // ✅ Acepto que me cambien el nombre
        'precio_actual',    // ✅ Acepto que me cambien el precio
        'categoria',        // ✅ Acepto que me cambien la categoría
        // ... todos los campos que quieres permitir
    ];

    /**
     * $casts = "Cómo convertir los datos automáticamente"
     * CONVENIENCIA: Laravel convierte por ti
     */
    protected $casts = [
        'disponible' => 'boolean',      // "1" → true, "0" → false
        'precio_actual' => 'decimal:2', // "99.99" → 99.99 (decimal)
        'valoracion' => 'decimal:1',    // "4.5" → 4.5 (decimal)
    ];

    // ❌ NOTA: NO necesitas definir $table = 'products'
    // Laravel automáticamente usa el plural del modelo: Product → products
}