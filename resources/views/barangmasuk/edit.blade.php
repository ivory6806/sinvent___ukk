@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>Edit Barang Masuk</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('barangmasuk.update', $rsetBarangMasuk->id) }}" method="POST" enctype="multipart/form-data">                    
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">TANGGAL MASUK</label>
                                <input type="date" class="form-control @error('tgl_masuk') is-invalid @enderror" name="tgl_masuk" value="{{ old('tgl_masuk', $rsetBarangMasuk->tgl_masuk) }}" placeholder="Masukkan Tanggal Masuk Barang">
                           
                                <!-- error message untuk tgl_masuk -->
                                @error('tgl_masuk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">JUMLAH MASUK</label>
                                <input type="number" min="0" class="form-control @error('qty_masuk') is-invalid @enderror" name="qty_masuk" value="{{ old('qty_masuk', $rsetBarangMasuk->qty_masuk) }}" placeholder="Masukkan Jumlah Masuk Barang">
                           
                                <!-- error message untuk qty_masuk -->
                                @error('qty_masuk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">NAMA BARANG</label>
                                <!-- Input readonly untuk menampilkan nama barang -->
                                <input type="text" class="form-control" value="{{ $rsetBarangMasuk->barang->merk }}" readonly>
                                
                                <!-- Input hidden untuk menyimpan nilai barang_id -->
                                <input type="hidden" name="barang_id" value="{{ $rsetBarangMasuk->barang_id }}">
                                
                                <!-- error message untuk kategori -->
                                @error('barang_id')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary">UPDATE</button>
                            <a href="{{ route('barangmasuk.index') }}" class="btn btn-md btn-secondary">BATAL</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection