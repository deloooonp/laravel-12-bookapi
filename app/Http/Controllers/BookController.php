<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $data = Book::all();
        return response()->json([
            'status' => true,
            'message' => 'Data buku berhasil ditampilkan',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataBook = new Book;

        $rules = [
            'judul' => 'required',
            'pengarang' => 'required',
            'tanggal_publikasi' => 'required|date',
        ];

        $validatedData = Validator::make($request->all(), $rules);


        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data buku gagal ditambahkan',
                'error' => $validatedData->errors(),
            ]);
        }

        $dataBook->judul = $request->judul;
        $dataBook->pengarang = $request->pengarang;
        $dataBook->tanggal_publikasi = $request->tanggal_publikasi;

        $post = $dataBook->save();

        return response()->json([
            'status' => true,
            'message' => 'Data buku berhasil disimpan',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Book::find($id);
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data buku ditampilkan',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data buku tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dataBook = Book::find($id);
        if (empty($dataBook)){
            return response()->json([
                'status' => false,
                'message' => 'Data buku tidak ditemukan',
            ], 404);
        }

        $rules = [
            'judul' => 'required',
            'pengarang' => 'required',
            'tanggal_publikasi' => 'required|date',
        ];

        $validatedData = Validator::make($request->all(), $rules);


        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data buku gagal di update',
                'error' => $validatedData->errors(),
            ]);
        }

        $dataBook->judul = $request->judul;
        $dataBook->pengarang = $request->pengarang;
        $dataBook->tanggal_publikasi = $request->tanggal_publikasi;

        $post = $dataBook->save();

        return response()->json([
            'status' => true,
            'message' => 'Data buku berhasil di update',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dataBook = Book::find($id);
        if (empty($dataBook)){
            return response()->json([
                'status' => false,
                'message' => 'Data buku tidak ditemukan',
            ], 404);
        }

        $post = $dataBook->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data buku berhasil di delete',
        ]);
    }
}
