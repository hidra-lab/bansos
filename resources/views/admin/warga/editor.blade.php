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
                <div class="breadcrumb-item"><a href="#">{{ $title }}</a></div>
                <div class="breadcrumb-item">{{ $title }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ $title }} Warga</h2>
            <p class="section-lead">
                {{ $title }} 
            </p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $title }}</h4>
                        </div>
                        <form action="{{ $route }}" method="POST">
                            @csrf
                            
                            <div class="card-body">
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">No KK</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" name="no_kk" class="form-control @error('no_kk') is-invalid @enderror" value="{{ old('no_kk', $warga->no_kk ?? '') }}">
                                        @error('no_kk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIK</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $warga->nik ?? '') }}">
                                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $warga->nama ?? '') }}">
                                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis Kelamin</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="jenis_kelamin" class="form-control ui dropdown @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                            <option value="LAKI_LAKI" {{ old('jenis_kelamin', $warga->jk??'') == 'LAKI_LAKI'?'selected':'' }}>Laki-Laki</option>
                                            <option value="PEREMPUAN" {{ old('jenis_kelamin', $warga->jk??'') == 'PEREMPUAN'?'selected':'' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $warga->tmp_lahir ?? '') }}">
                                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Lahir</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $warga->tgl_lahir ?? '') }}">
                                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pendidikan</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="pendidikan" class="form-control @error('pendidikan') is-invalid @enderror" value="{{ old('pendidikan', $warga->pendidikan ?? '') }}">
                                        @error('pendidikan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pekerjaan</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan', $warga->pekerjaan ?? '') }}">
                                        @error('pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status Perkawinan</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="status_perkawinan" class="form-control ui dropdown @error('status_perkawinan') is-invalid @enderror">
                                            <option value="" disabled selected>Pilih Status Perkawinan</option>
                                            <option value="Belum Kawin" {{ old('status_perkawinan', $warga->status_perkawinan??'') == 'Belum Kawin'?'selected':'' }}>Belum Kawin</option>
                                            <option value="Kawin" {{ old('status_perkawinan', $warga->status_perkawinan??'') == 'Kawin'?'selected':'' }}>Kawin</option>
                                            <option value="Cerai Hidup"  {{ old('status_perkawinan', $warga->status_perkawinan??'') == 'Cerai Hidup'?'selected':'' }}>Cerai Hidup</option>
                                            <option value="Cerai Mati"  {{ old('status_perkawinan', $warga->status_perkawinan??'') == 'Cerai Mati'?'selected':'' }}>Cerai Mati</option>
                                        </select>
                                        @error('status_perkawinan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Hubungan Keluarga</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="hubungan_keluarga" class="form-control @error('hubungan_keluarga') is-invalid @enderror" value="{{ old('hubungan_keluarga', $warga->hub_keluarga ?? '') }}">
                                        @error('hubungan_keluarga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Keterangan Warga</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="keterangan_warga" class="form-control @error('keterangan_warga') is-invalid @enderror" value="{{ old('keterangan_warga', $warga->ket_warga ?? '') }}">
                                        @error('keterangan_warga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
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
        $(document).ready(function () {
            $('.ui.dropdown').dropdown();
        });
    </script>
@endsection