@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
		<div class="pull-left">
		    <h2>DAFTAR BARANG KELUAR</h2>
		</div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        @if ($error = Session::get('qty_keluar'))
            <div class="alert alert-danger">
                <p>{{ $error }}</p>
            </div>
        @endif
        
        <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div  class="flex-shrink-0">
                        <a href="{{ route('barangkeluar.create') }}" class="btn btn-md btn-success my-2">TAMBAH BARANG KELUAR</a>
                        </div>
                        <!-- Form pencarian -->
                        <form  method="GET" action="{{ route('barangkeluar.index') }}" class="form-inline my-2 my-lg-0">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" name="keyword" placeholder="Search for..."
                                    aria-label="Search" aria-describedby="basic-addon2"  value="{{ request()->input('keyword') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"> <!-- Perubahan di sini: menambahkan type="submit" -->
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- Form pencarian untuk tampilan kecil (XS) -->
                        <form  method="GET" action="{{ route('barangkeluar.index') }}" class="d-sm-none form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" name="keyword" placeholder="Search for..."
                                    aria-label="Search" aria-describedby="basic-addon2" value="{{ request()->input('keyword') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"> <!-- Perubahan di sini: menambahkan type="submit" -->
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TANGGAL KELUAR</th>
                            <th>JUMLAH KELUAR</th>
                            <th>BARANG</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rsetBarangKeluar as $rowbarangkeluar)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $rowbarangkeluar->tgl_keluar  }}</td>
                                <td>{{ $rowbarangkeluar->qty_keluar  }}</td>
                                <td>{{ $rowbarangkeluar->barang->merk  }} {{ $rowbarangkeluar->barang->seri  }}</td>
                                <td>
                                    <form action="{{ route('barangkeluar.destroy', $rowbarangkeluar->id) }}" method="POST">
                                    <a href="{{ route('barangkeluar.show', $rowbarangkeluar->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('barangkeluar.edit', $rowbarangkeluar->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>    
                                    @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <div class="alert">
                                Data barang keluar belum tersedia!
                            </div>
                        @endforelse
                    </tbody>
                   
                </table>
                {!! $rsetBarangKeluar->links('pagination::bootstrap-5') !!}


            </div>
        </div>
    </div>
@endsection