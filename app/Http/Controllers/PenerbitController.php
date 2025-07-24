<?php

namespace App\Http\Controllers;

use App\Models\penerbit;
use Illuminate\Http\Request;

class PenerbitController extends Controller
{
   
    public function index()
    {
        $penerbits = penerbit::all();
        return response()->json([
            'message' => 'List of Publishers',
            'data' => $penerbits
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:penerbits,nama',
            'alamat' => 'nullable|string|max:255',
        ]);

        $penerbit = penerbit::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'message' => 'Publisher successfully added',
            'data' => $penerbit
        ], 201);
    }

    public function show(penerbit $penerbit)
    {
        $penerbit = penerbit::find($penerbit->id);

        if (!$penerbit) {
            return response()->json([
                'message' => 'Publisher not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Penerbit Details',
            'data' => $penerbit
        ], 200);
    }

    public function update(Request $request, penerbit $penerbit)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:penerbits,nama,' . $penerbit->id,
            'alamat' => 'nullable|string|max:255',
        ]);

        $penerbit->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'message' => 'Publisher successfully updated',
            'data' => $penerbit
        ], 200);
    }

    public function destroy(penerbit $penerbit)
    {
        $penerbit->delete();

        return response()->json([
            'message' => 'Publisher successfully deleted',
        ], 200);
    }
}
