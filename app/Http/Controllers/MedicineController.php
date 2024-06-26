<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicines = Medicine::orderBy('name', 'ASC')->simplePaginate(5);
        //manggil html
        return view('medicine.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicine.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi

        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);
    

    Medicine::create([
        'name' => $request->name,
        'type' => $request->type,
        'price' => $request->price,
        'stock' => $request->stock,
    ]);

    return redirect()->back()->with('success', 'Berhasil menambahkan Data Obat!');
}
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $medicine = Medicine::find($id);
        // response status code api =
        // 200 = success
        // 400 = error
        // 419 = error token csrf
        // 500 = error server hosting
        return response()->json($medicine, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $medicine = Medicine::find($id);

        return view('medicine.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //validasi
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
        ]);
        Medicine::where('id', $id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
        ]);
        return redirect()->route('medicine.data')->with('success', 'Berhasil Mengubah Data Obat!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //cari dan hapus data
        Medicine::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil Menghapus Data!');
    }

    public function stockData()
    {
        $medicines = Medicine::orderBy('stock', 'ASC')->simplePaginate(5);
        return view('medicine.stock', compact('medicines'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|numeric',
        ]);
        $medicineBefore = Medicine::where('id', $id)->first();
        if ($request->stock <= $medicineBefore['stock']) {
            return response()->json(['message' => 'Stock tidak boleh kurang atau sama dengan stock sebelumnya!'], 400);
        }
        $medicineBefore->update(['stock' => $request->stock]);
        return response()->json('Berhasil', 200);
    }
}
