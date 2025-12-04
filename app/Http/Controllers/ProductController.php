<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // =======================================
    // 1. LISTAR TODOS
    // =======================================
    public function index()
    {
        return response()->json(Product::all());
    }

    // =======================================
    // 2. OBTENER UNO POR ID (NUMÉRICO)
    // =======================================
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // =======================================
    // 3. CREAR PRODUCTO
    // =======================================
    public function store(Request $request)
    {
        $request->validate([
            'Titulo01' => 'required',
            'Parrafo01' => 'required',
            'Subtitulo01' => 'required',
            'Imagen01' => 'required|image|max:4096',
            'Subtitulo02' => 'required',
            'Parrafo02' => 'required',
            'Imagen02' => 'required|image|max:4096',
            'Imagen03' => 'required|image|max:4096',
            'BotonLink' => 'required',
        ]);

        // Imagen01
        $img01 = $request->file('Imagen01');
        $img01Name = time() . '_' . $img01->getClientOriginalName();
        $img01->move(public_path('products'), $img01Name);
        $path01 = 'products/' . $img01Name;

        // Imagen02
        $img02 = $request->file('Imagen02');
        $img02Name = time() . '_' . $img02->getClientOriginalName();
        $img02->move(public_path('products'), $img02Name);
        $path02 = 'products/' . $img02Name;

        // Imagen03
        $img03 = $request->file('Imagen03');
        $img03Name = time() . '_' . $img03->getClientOriginalName();
        $img03->move(public_path('products'), $img03Name);
        $path03 = 'products/' . $img03Name;

        $product = Product::create([
            'Titulo01' => $request->Titulo01,
            'Parrafo01' => $request->Parrafo01,
            'Subtitulo01' => $request->Subtitulo01,
            'Imagen01' => $path01,
            'Subtitulo02' => $request->Subtitulo02,
            'Parrafo02' => $request->Parrafo02,
            'Imagen02' => $path02,
            'Imagen03' => $path03,
            'BotonLink' => $request->BotonLink,
        ]);

        return response()->json($product, 201);
    }

    // =======================================
// 4. ACTUALIZAR PRODUCTO
// =======================================
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // VALIDACIÓN (nada requerido)
        $request->validate([
            'Titulo01' => 'nullable|string',
            'Parrafo01' => 'nullable|string',
            'Subtitulo01' => 'nullable|string',
            'Subtitulo02' => 'nullable|string',
            'Parrafo02' => 'nullable|string',
            'BotonLink' => 'nullable|string',
            'Imagen01' => 'nullable|image|max:4096',
            'Imagen02' => 'nullable|image|max:4096',
            'Imagen03' => 'nullable|image|max:4096',
        ]);

        // Imagen01 opcional
        if ($request->hasFile('Imagen01')) {
            if ($product->Imagen01 && file_exists(public_path($product->Imagen01))) {
                unlink(public_path($product->Imagen01));
            }

            $img01 = $request->file('Imagen01');
            $img01Name = time() . '_' . $img01->getClientOriginalName();
            $img01->move(public_path('products'), $img01Name);
            $product->Imagen01 = 'products/' . $img01Name;
        }

        // Imagen02 opcional
        if ($request->hasFile('Imagen02')) {
            if ($product->Imagen02 && file_exists(public_path($product->Imagen02))) {
                unlink(public_path($product->Imagen02));
            }

            $img02 = $request->file('Imagen02');
            $img02Name = time() . '_' . $img02->getClientOriginalName();
            $img02->move(public_path('products'), $img02Name);
            $product->Imagen02 = 'products/' . $img02Name;
        }

        // Imagen03 opcional
        if ($request->hasFile('Imagen03')) {
            if ($product->Imagen03 && file_exists(public_path($product->Imagen03))) {
                unlink(public_path($product->Imagen03));
            }

            $img03 = $request->file('Imagen03');
            $img03Name = time() . '_' . $img03->getClientOriginalName();
            $img03->move(public_path('products'), $img03Name);
            $product->Imagen03 = 'products/' . $img03Name;
        }

        // Actualizar campos restantes
        $product->update($request->except(['Imagen01', 'Imagen02', 'Imagen03']));

        return response()->json($product);
    }

    // =======================================
    // 5. ELIMINAR PRODUCTO + BORRAR IMÁGENES
    // =======================================
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Borrar imágenes
        foreach (['Imagen01', 'Imagen02', 'Imagen03'] as $img) {
            if ($product->$img && file_exists(public_path($product->$img))) {
                unlink(public_path($product->$img));
            }
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}
