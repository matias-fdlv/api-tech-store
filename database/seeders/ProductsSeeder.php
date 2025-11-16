<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        // ❌ Product::truncate(); // CUIDADO: Borra TODOS los datos
        // Mejor usar: Product::query()->delete(); si quieres limpiar

        $productos = [
            [
                'nombre' => 'Playstation 5',
                'precio_actual' => 699.99,
                'precio_original' => 699.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/1',
                'imagen_url' => '/images/consoles/ps5.jpg',
                'disponible' => true,
                'valoracion' => 4.9
            ],
            [
                'nombre' => 'XBOX Series X',
                'precio_actual' => 699.99,
                'precio_original' => 799.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/2',
                'imagen_url' => '/images/consoles/xboxsx.jpg',
                'disponible' => true,
                'valoracion' => 4.5
            ],
            [
                'nombre' => 'Iphone 15',
                'precio_actual' => 1599.99,
                'precio_original' => 1599.99,
                'categoria' => 'phones',
                'enlace_producto' => '/product/3',
                'imagen_url' => '/images/phones/iphone15.jpg',
                'disponible' => true,
                'valoracion' => 4.1
            ],
            [
                'nombre' => 'Samsung Galaxy S23',
                'precio_actual' => 999.99,
                'precio_original' => 1199.99,
                'categoria' => 'phones',
                'enlace_producto' => '/product/4',
                'imagen_url' => '/images/phones/samsunggalaxy23.jpg',
                'disponible' => true,
                'valoracion' => 4.2
            ],
            [
                'nombre' => 'Zeebo',
                'precio_actual' => 59.99,
                'precio_original' => 59.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/5',
                'imagen_url' => '/images/consoles/zeebo.jpg',
                'disponible' => true,
                'valoracion' => 5.0
            ],
            // mas productos
        ];

        foreach ($productos as $productoData) {
            // ✅ FORMA SEGURA: Usando el modelo
            Product::create($productoData);
            
            // ❌ NO HACER: $producto = new Product(); $producto->save();
            // Porque bypass las validaciones del modelo
        }
    }
}