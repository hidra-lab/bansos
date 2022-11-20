@extends('layouts.admin.app')

@section ('content')
<div class="main-content" style="min-height: 524px;">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url()->previous() }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Kembali</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">{{ $title }}s</a></div>
                <div class="breadcrumb-item">{{ $title }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ $title }}  Data Alternatif</h2>
            <p class="section-lead">
                {{ $title }} 
            </p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $title }}</h4>
                        </div>
                        <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            
                            <div class="card-body">
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIK</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="jk" class="form-control" value="{{ $alternatif->dtks->nik }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="jk" class="form-control" value="{{ $alternatif->dtks->nama }}" disabled>
                                    </div>
                                </div>

                                @if(Auth::user()->role !== 'kelurahan')
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jumlah Kelayakan</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select name="kelayakan" class="form-control" required autofocus>
                                                <option value="" disabled selected>Pilih Jumlah Kelayakan</option>
                                                @foreach($kelayakan as $key => $value)
                                                <option value="{{ $key }}" {{ old('kelayakan', $alternatif->ps1??'')==$key?'selected':'' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis Kelamin</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" name="jk" class="form-control" value="{{ $alternatif->dtks->jk }}" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Hubungan Keluarga</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" name="hub_keluarga" class="form-control @error('hub_keluarga') is-invalid @enderror" value="{{ old('hub_keluarga', $alternatif->dtks->hub_keluarga??'') }}" disabled>
                                        </div>
                                    </div>
                                @endif 
                                  
                                @if(Auth::user()->role !== 'psm')
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tepat Sasaran</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select name="sasaran"  class="form-control" required autofocus>
                                                <option value="" disabled selected>Pilih Tepat Sasaran</option>
                                                @foreach($sasaran as $key => $value)
                                                <option value="{{ $key }}" {{ old('sasaran', $alternatif->kl1??'')==$key?'selected':'' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tepat Jumlah</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select name="jumlah"  class="form-control" required autofocus>
                                                <option value="" disabled selected>Pilih Tepat Jumlah</option>
                                                @foreach($jumlah as $key => $value)
                                                <option value="{{ $key }}" {{ old('jumlah', $alternatif->kl2??'')==$key?'selected':'' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tepat Administrasi</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select name="administrasi"  class="form-control" required autofocus>
                                                <option value="" disabled selected>Pilih Tepat Administrasi</option>
                                                @foreach($administrasi as $key => $value)
                                                <option value="{{ $key }}" {{ old('administrasi', $alternatif->kl3??'')==$key?'selected':'' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>
</div>
</section>
</div>
@endsection

@section('script')
    <script>
        $('input[name=picture]').on('change', function(){
            const [file] = $(this)[0].files;
            if(file){
                $('#preview-img').attr('src', URL.createObjectURL(file));
            }
        })
    </script>
@endsection
