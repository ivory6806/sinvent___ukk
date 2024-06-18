<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Barang;

use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
    
        // Query untuk mencari barang keluar berdasarkan keyword
        $rsetBarangKeluar = BarangKeluar::with('barang')
            ->whereHas('barang', function ($query) use ($keyword) {
                $query->where('merk', 'LIKE', "%$keyword%")
                      ->orWhere('seri', 'LIKE', "%$keyword%")
                      ->orWhere('spesifikasi', 'LIKE', "%$keyword%");
            })
            ->orWhere('tgl_keluar', 'LIKE', "%$keyword%")
            ->orWhere('qty_keluar', 'LIKE', "%$keyword%")
            ->paginate(10);
    
        return view('barangkeluar.index', compact('rsetBarangKeluar'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    public function create()
    {
        $abarangkeluar = Barang::all();
        return view('barangkeluar.create',compact('abarangkeluar'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tgl_keluar'   => 'required|date',
            'qty_keluar'   => 'required|numeric|min:1',
            'barang_id'    => 'required|exists:barang,id',
        ]);
    
        $tgl_keluar = $request->tgl_keluar;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangMasuk with a date later than tgl_keluar
        $existingBarangMasuk = BarangMasuk::where('barang_id', $barang_id)
            ->where('tgl_masuk', '>', $tgl_keluar)
            ->exists();
    
        if ($existingBarangMasuk) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh mendahului tanggal masuk!']);
        }
    
        $barang = Barang::find($barang_id);
    
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
        }
    
        BarangKeluar::create([
            'tgl_keluar'  => $tgl_keluar,
            'qty_keluar'  => $request->qty_keluar,
            'barang_id'   => $barang_id,
        ]);
    
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
    
    public function show($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        return view('barangkeluar.show', compact('barangKeluar'));
    }
    

    //delete record di tambel barangkeluar tanpa memengaruhi stok di tabel barang
    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $barangKeluar->delete();

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }


    public function edit($id)
    {
        $barangKeluar= BarangKeluar::findOrFail($id);
        $abarangkeluar = Barang::all();

        return view('barangkeluar.edit', compact('barangKeluar', 'abarangkeluar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_keluar'   => 'required|date',
            'qty_keluar'   => 'required|numeric|min:1',
            'barang_id'    => 'required|exists:barang,id',
        ]);
    
        $tgl_keluar = $request->tgl_keluar;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangMasuk with a date later than tgl_keluar
        $existingBarangMasuk = BarangMasuk::where('barang_id', $barang_id)
            ->where('tgl_masuk', '>', $tgl_keluar)
            ->exists();
    
        if ($existingBarangMasuk) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh mendahului tanggal masuk!']);
        }
    
        $barang = Barang::find($barang_id);
    
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
        }
    
        $barangKeluar = BarangKeluar::findOrFail($id);
    
        $barangKeluar->update([
            'tgl_keluar'  => $tgl_keluar,
            'qty_keluar'  => $request->qty_keluar,
            'barang_id'   => $barang_id,
        ]);
    
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

}
