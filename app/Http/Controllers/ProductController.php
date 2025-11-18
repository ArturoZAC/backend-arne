<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ============================
    // 1. LISTAR TODOS
    // ============================
    public function index()
    {
        return response()->json(Product::all());
    }

    // ============================
    // 2. OBTENER UNO POR ID (UUID)
    // ============================
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    // ============================
    // 3. CREAR PRODUCTO (ya lo tenías)
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:products,slug',
            'Titulo01' => 'required',
            'Parrafo01' => 'required',
            'Subtitulo01' => 'required',
            'Imagen01' => 'required|image|max:4096',

            'Subtitulo02' => 'required',
            'Parrafo02' => 'required',
            'Imagen02' => 'required|image|max:4096',

            'Parrafo03' => 'required',
            'BotonLink' => 'required',
        ]);

        $imagen01 = $request->file('Imagen01')->store('products', 'public');
        $imagen02 = $request->file('Imagen02')->store('products', 'public');

        $product = Product::create([
            'slug' => $request->slug,
            'Titulo01' => $request->Titulo01,
            'Parrafo01' => $request->Parrafo01,
            // 'Subtitulo01' => $request->Subtitulo01,
            'Subtitulo01' => $request->input('Subtitulo01'),
            'Imagen01' => $imagen01,

            // 'Subtitulo02' => $request->Subtitulo02,
            'Subtitulo02' => $request->input('Subtitulo02'),
            'Parrafo02' => $request->Parrafo02,
            'Imagen02' => $imagen02,

            'Parrafo03' => $request->Parrafo03,
            'BotonLink' => $request->BotonLink,
        ]);

        return response()->json($product, 201);
    }

    // ============================
    // 4. ACTUALIZAR PRODUCTO
    // ============================
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // validación (sin unique para slug si sigue siendo el mismo)
        $request->validate([
            'slug' => 'required|unique:products,slug,' . $id,
            'Titulo01' => 'required',
            'Parrafo01' => 'required',
            'Subtitulo01' => 'required',
            'Subtitulo02' => 'required',
            'Parrafo02' => 'required',
            'Parrafo03' => 'required',
            'BotonLink' => 'required',
        ]);

        // IMAGEN 1 OPCIONAL
        if ($request->hasFile('Imagen01')) {
            $product->Imagen01 = $request->file('Imagen01')->store('products', 'public');
        }

        // IMAGEN 2 OPCIONAL
        if ($request->hasFile('Imagen02')) {
            $product->Imagen02 = $request->file('Imagen02')->store('products', 'public');
        }

        // ACTUALIZAR CAMPOS RESTANTES
        $product->update($request->except(['Imagen01', 'Imagen02']));

        return response()->json($product);
    }

    // ============================
    // 5. ELIMINAR PRODUCTO
    // ============================
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Borrar imágenes si existen
        if ($product->Imagen01 && Storage::disk('public')->exists($product->Imagen01)) {
            Storage::disk('public')->delete($product->Imagen01);
        }

        if ($product->Imagen02 && Storage::disk('public')->exists($product->Imagen02)) {
            Storage::disk('public')->delete($product->Imagen02);
        }

        // Borrar el producto
        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }

    // ProductController.php
    public function getBySlug($slug) {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return response()->json($product);
    }

}
