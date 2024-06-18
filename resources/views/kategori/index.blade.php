@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
		<div class="pull-left">
		    <h2>DAFTAR KATEGORI</h2>
		</div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @elseif ($message = Session::get('gagal'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif

            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div  class="flex-shrink-0">
                    <a href="{{ route('kategori.create') }}" class="btn btn-md btn-success my-2">TAMBAH KATEGORI</a>
                    </div>
                    <!-- Form pencarian -->
                    <form  method="GET"  action="{{ route('kategori.index') }}" class="form-inline my-2 my-lg-0">
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
                    <!-- Form pencarian untuk tampilan kecil (XS) -->
                    <form  method="GET"  action="{{ route('kategori.index') }}" class="d-sm-none form-inline mr-auto w-100 navbar-search">
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
                            <th>DESKRIPSI</th>
                            <th>KATEGORI</th>
                            <th>DETAIL</th>
                            <th style="width: 15%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rsetKategori as $rowkategori)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $rowkategori->deskripsi  }}</td>
                                <td>{{ $rowkategori->kategori  }}</td>
                                <td>{{ $rowkategori->kat  }}</td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('kategori.destroy', $rowkategori->id) }}" method="POST">
                                        <a href="{{ route('kategori.show', $rowkategori->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('kategori.edit', $rowkategori->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <div class="alert">
                                Data kategori belum tersedia!
                            </div>
                        @endforelse
                    </tbody>
                   
                </table>
                {!! $rsetKategori->links('pagination::bootstrap-5') !!}

            </div>
        </div>
    </div>
@endsection