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
            <h2 class="section-title">{{ $title }}  DTKS</h2>
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

                            @if(isset($dtks))
                                @method('put')
                            @endif

                            <div class="card-body">
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama</label>
                                    <div class="col-sm-12 col-md-7">
                                        @if(isset($dtks))
                                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $dtks->nama??'') }}" readonly>
                                        @else
                                            <select class="form-control ui dropdown search selection">
                                                <option value="" readonly selected>Pilih Nama</option>
                                            </select>
                                        @endif

                                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">RT</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" name="rt" class="form-control @error('rt') is-invalid @enderror" value="{{ old('rt', $dtks->rt??'') }}">
                                        @error('rt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">RW</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" name="rw" class="form-control @error('rw') is-invalid @enderror" value="{{ old('rw', $dtks->rw??'') }}">
                                        @error('rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">No KK</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" name="no_kk" class="form-control @error('no_kk') is-invalid @enderror" value="{{ old('no-kk', $dtks->no_kk??'') }}" readonly>
                                        @error('no_kk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIK</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $dtks->nik??'') }}" readonly>
                                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Lahir</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" value="{{ old('tgl_lahir', $dtks->tgl_lahir??'') }}" readonly>
                                        @error('tgl_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis Kelamin</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="enum" name="jk" class="form-control @error('jk') is-invalid @enderror" value="{{ old('jk', $dtks->jk??'') }}" readonly>
                                        @error('jk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pekerjaan</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="pekerjaan" class="form-control ui dropdown @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan', $dtks->pekerjaan??'') }}">
                                        @error('pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Kondisi Rumah</label>
                                    <div class="col-sm-12 col-md-7">
                                        @foreach ($kondisiRumah as $jenis => $items)
                                            <label class="d-inline-block">{{ $jenis }}</label>
                                            <div class="row form-group ml-1">

                                            @foreach ($items as $item)
                                                @php
                                                    $isChecked = false;
                                                    foreach ($dtks->kondisi_rumah_json ?? [] as $jsonItem) {
                                                        if ($jenis . '-' . $item == $jsonItem) {
                                                            $isChecked = true;
                                                            break;
                                                        }
                                                    }
                                                @endphp

                                                <div class="form-check mx-2">
                                                    <input class="form-check-input" type="checkbox" name="kondisi[]" value="{{ $jenis }}-{{ $item }}" id="{{ $jenis }}-{{ $item }}" @if($isChecked) checked @endif>
                                                    <label class="form-check-label" for="{{ $jenis }}-{{ $item }}">
                                                        {{ $item }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis Transpotasi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="transportasi"  class="form-control ui dropdown" required autofocus>
                                            <option value="" readonly selected>Pilih Jenis Transportasi</option>
                                            @foreach($jenisTransportasi as $transportasi)
                                            <option value="{{ $transportasi }}" {{ old('transportasi', $dtks->jenis_transportasi??'')==$transportasi?'selected':'' }}>{{ $transportasi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Hubungan Keluarga</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="hub_keluarga" class="form-control @error('hub_keluarga') is-invalid @enderror" value="{{ old('hub_keluarga', $dtks->hub_keluarga??'') }}">
                                        @error('hub_keluarga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggungan</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" name="tanggungan" class="form-control @error('tanggungan') is-invalid @enderror" value="{{ old('tanggungan', $dtks->tanggungan??'') }}">
                                        @error('tanggungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis Bansos</label>
                                    <div class="col-sm-12 col-md-7">
                                        @foreach ($bansos as $item)
                                            @php
                                                $isChecked = false;
                                                foreach ($dtks->bansos ?? [] as $dtksBansos) {
                                                    if ($item->id == $dtksBansos->id) {
                                                        $isChecked = true;
                                                        break;
                                                    }
                                                }
                                            @endphp

                                            <div class="form-check">
                                                <input type="checkbox" class="form-input-input" name="jenis_bansos[]" value="{{ $item->id }}" id="jenis_bansos-{{ $item->id }}" @if($isChecked) checked @endif>
                                                <label class="form-check-label" for="jenis_bansos-{{ $item->id }}">{{ $item->jenis_bansos }}</label>
                                            </div>
                                        @endforeach
                                        {{-- <select name="jenis_bansos" multiple="" class="form-control ui dropdown bansos">
                                            <option value="" readonly selected>Pilih Bansos</option>
                                        </select> --}}
                                        @error('jenis_bansos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button class="btn btn-primary" type="submit">Simpan</button>
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

            @if(isset($warga))
                const warga = @json($warga);
                $('.ui.dropdown.search').dropdown({
                    values: warga,
                    fields: {
                        name : 'nama',
                        value : 'nik',
                    },
                    onChange: function (value, text) {
                        let selected = null;

                        // pilih warga berdasarkan combo box yang dipilih oleh user
                        for (let i = 0; i < warga.length; i++) {
                            const elm = warga[i];

                            if (elm.nik == value) {
                                selected = elm
                                break;
                            }
                        }

                        // cek jika selected tidak null
                        if (selected) {
                            $('input[name="no_kk"]').val(selected.no_kk);
                            $('input[name="nik"]').val(selected.nik);
                            $('input[name="tgl_lahir"]').val(selected.tgl_lahir);
                            $('input[name="jk"]').val(selected.jk);
                            $('input[name="pekerjaan"]').val(selected.pekerjaan);
                            $('input[name="hub_keluarga"]').val(selected.hub_keluarga);
                        }
                    }
                });
            @endif

            @if(isset($bansos))
                const bansos = @json($bansos);
                $('.ui.dropdown.bansos').dropdown({
                    values: bansos,
                    fields: {
                        name : 'jenis_bansos',
                        value : 'id',
                    },
                });

            @endif
        });
    </script>
@endsection
