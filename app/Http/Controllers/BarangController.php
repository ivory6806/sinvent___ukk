<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use DB;

class BarangController extends Controller
{
public function index(Request $request)
    {
        // $rsetBarang = Barang::with('kategori')->latest()->paginate(10);

        // return view('barang.index', compact('rsetBarang'))
        //     ->with('i', (request()->input('page', 1) - 1) * 10);
        $keyword = $request->input('keyword');

        // Query untuk mencari barang berdasarkan keyword
        $rsetBarang = Barang::where('merk', 'LIKE', "%$keyword%")
            ->orWhere('seri', 'LIKE', "%$keyword%")
            ->orWhere('spesifikasi', 'LIKE', "%$keyword%")
            ->orWhere('stok', 'LIKE', "%$keyword%")
            ->orWhereHas('kategori', function ($query) use ($keyword) {
                $query->where('deskripsi', 'LIKE', "%$keyword%");
            })
            ->paginate(10);
    
        return view('barang.index', compact('rsetBarang'))
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $akategori = Kategori::all();
        return view('barang.create',compact('akategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'merk'          => 'required',
            'seri'          => 'required',
            'spesifikasi'   => 'required',

            'kategori_id'   => 'required',

        ]);

        Barang::create([
            'merk'             => $request->merk,
            'seri'             => $request->seri,
            'spesifikasi'      => $request->spesifikasi,
            'kategori_id'      => $request->kategori_id,
        ]);

        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id)
    {
        $rsetBarang = Barang::find($id);

        return view('barang.show', compact('rsetBarang'));
    }

    public function edit(string $id)
    {
    $akategori = Kategori::all();
    $rsetBarang = Barang::find($id);
    $selectedKategori = Kategori::find($rsetBarang->kategori_id);

    return view('barang.edit', compact('rsetBarang', 'akategori', 'selectedKategori'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'merk'        => 'required',
            'seri'        => 'required',
            'spesifikasi' => 'required',
            'kategori_id' => 'required',
        ]);

        $rsetBarang = Barang::find($id);

            $rsetBarang->update([
                'merk'          => $request->merk,
                'seri'          => $request->seri,
                'spesifikasi'   => $request->spesifikasi,
                'kategori_id'   => $request->kategori_id,
            ]);

        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Diubah!']);
    }


    public function destroy(string $id)
    {
        $rsetBarang = Barang::find($id);
    
        // cek apakah qty masuk lebih besar daripada stok 
        if ($rsetBarang->stok > 0) {
            return redirect()->route('barang.index')->with(['error' => 'Barang dengan stok lebih dari 0 tidak dapat dihapus!']);
        }
    
        // cek apakah berelasi dengan barangkeluar
        $relatedBarangKeluar = BarangKeluar::where('barang_id', $id)->exists();
    
        if ($relatedBarangKeluar) {
            return redirect()->route('barang.index')->with(['gagal' => 'Data Gagal Dihapus! Data masih digunakan dalam tabel Barang Keluar']);
        }

        // cek apakah berelasi dengan barangmasuk
        $relatedBarangMasuk = BarangMasuk::where('barang_id', $id)->exists();

        if ($relatedBarangMasuk) {
            return redirect()->route('barang.index')->with(['gagal' => 'Data Gagal Dihapus! Data masih digunakan dalam tabel Barang Masuk']);
        }
    
        $rsetBarang->delete();
        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    

}
