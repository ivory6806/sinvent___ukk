<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
    
        // Query untuk mencari barang masuk berdasarkan keyword
        $rsetBarangMasuk = BarangMasuk::with('barang')
            ->whereHas('barang', function ($query) use ($keyword) {
                $query->where('merk', 'LIKE', "%$keyword%")
                      ->orWhere('seri', 'LIKE', "%$keyword%")
                      ->orWhere('spesifikasi', 'LIKE', "%$keyword%");
            })
            ->orWhere('tgl_masuk', 'LIKE', "%$keyword%")
            ->orWhere('qty_masuk', 'LIKE', "%$keyword%")
            ->paginate(10);
    
        return view('barangmasuk.index', compact('rsetBarangMasuk'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    public function create()
    {
        $abarangmasuk = Barang::all();
        return view('barangmasuk.create',compact('abarangmasuk'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tgl_masuk'     => 'required|date',
            'qty_masuk'     => 'required|numeric|min:1',
            'barang_id'     => 'required|exists:barang,id',
        ]);
    
        $tgl_masuk = $request->tgl_masuk;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangKeluar with a date earlier than tgl_masuk
        $existingBarangKeluar = BarangKeluar::where('barang_id', $barang_id)
            ->where('tgl_keluar', '<', $tgl_masuk)
            ->exists();
    
        if ($existingBarangKeluar) {
            return redirect()->back()->withInput()->withErrors(['tgl_masuk' => 'Tanggal masuk tidak boleh melebihi tanggal keluar!']);
        }
    
        BarangMasuk::create([
            'tgl_masuk'  => $tgl_masuk,
            'qty_masuk'  => $request->qty_masuk,
            'barang_id'  => $barang_id,
        ]);
    
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        return view('barangmasuk.show', compact('barangMasuk'));
    }

    public function edit(string $id)
    {
        $abarang = Barang::all();
        $rsetBarangMasuk = BarangMasuk::find($id);
        $selectedBarang = Barang::find($rsetBarangMasuk->barang_id);

        return view('barangmasuk.edit', compact('rsetBarangMasuk', 'abarang', 'selectedBarang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl_masuk'     => 'required|date',
            'qty_masuk'     => 'required|numeric|min:1',
            'barang_id'     => 'required|exists:barang,id',
        ]);
    
        $tgl_masuk = $request->tgl_masuk;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangKeluar with a date earlier than tgl_masuk
        $existingBarangKeluar = BarangKeluar::where('barang_id', $barang_id)
            ->where('tgl_keluar', '<', $tgl_masuk)
            ->exists();
    
        if ($existingBarangKeluar) {
            return redirect()->back()->withInput()->withErrors(['tgl_masuk' => 'Tanggal masuk tidak boleh melebihi tanggal keluar!']);
        }
    
        $barangMasuk = BarangMasuk::findOrFail($id);
    
        $barangMasuk->update([
            'tgl_masuk'  => $tgl_masuk,
            'qty_masuk'  => $request->qty_masuk,
            'barang_id'  => $barang_id,
        ]);
    
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id)
    {
        $datamasuk = BarangMasuk::find($id);
        
        // Memeriksa apakah ada record di tabel BarangKeluar dengan barang_id yang sama
        $referencedInBarangKeluar = BarangKeluar::where('barang_id', $datamasuk->barang_id)->exists();

        if ($referencedInBarangKeluar) {
        // Jika ada referensi, penghapusan ditolak
        return redirect()->route('barangmasuk.index')->with(['error' => 'Data Tidak Bisa Dihapus Karena Masih Digunakan di Tabel Barang dan Barang Keluar!']);
        }

        // Menghapus record di tabel BarangMasuk
        $datamasuk->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);

    }

}
