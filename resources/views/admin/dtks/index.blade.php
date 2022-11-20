@extends('layouts.admin.app')
@section('content')
<style>
    table td {
        color: black;
    }
</style>
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>Data DTKS Bantuan Sosial</h1>
        @php
         $roles = ['psm', 'kelurahan'];
        @endphp
        @if (!in_array(Auth::user()->role, $roles))
          <a href="{{ route('dtks.create') }}" class="btn btn-primary pt-2 ml-4">Tambah DTKS</a>
        @endif  
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
          <div class="breadcrumb-item"><a href="#">Data DTKS </a></div>
          <div class="breadcrumb-item"> Semua Data DTKS </div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">Data DTKS Bantuan Sosial</h2>
        <p class="section-lead">
            Data DTKS Bantuan Sosial.
        </p>

        <div class="row">

        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Data DTKS Bantuan Sosial</h4>
              </div>
              <div class="card-body">

                @php
                  $roles = ['psm', 'kelurahan'];
                @endphp
                @if (!in_array(Auth::user()->role, $roles))
                  <div class="float-left">
                    <div class="form-group">
                      <form action="{{ route('dtks.uploadDocument') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <label>File</label>
                        <div class="input-group mb-3">
                          <input type="file" name="uploaded_file" class="form-control">
                          <input type="submit" value="Upload" class="btn btn-primary btn-sm">
                          <a href="{{ route('dtks.proses') }}" class="btn btn-primary pt-2 ml-2">Proses</a>
                        </div>
                      </form>
                    </div>
                  </div>
                @endif
                <div class="clearfix mb-3"></div>

                <div class="table-responsive">
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>RT/RW</th>
                                <th>No KK</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                                <th>Umur</th>
                                <th>Jenis Kelamin</th>
                                <th>Pekerjaan</th>
                                <th>Kondisi Rumah</th>
                                <th>Jenis Transportasi</th>
                                <th>Hubungan Keluarga</th>
                                <th>Tanggungan</th>
                                <th>Jenis Bansos</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($dtks as $dt)
                            @php $i++ @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $dt->rt }}/{{ $dt->rw }}</td>
                                <td>{{ $dt->no_kk }}</td>
                                <td>{{ $dt->nik }}</td>
                                <td>{{ $dt->nama }}</td>
                                <td>{{ $dt->tgl_lahir }}</td>
                                <td>{{ $dt->umur }}</td>
                                <td>{{ $dt->jk }}</td>
                                <td>{{ $dt->pekerjaan}}</td>
                                <td>{{ $dt->kondisi_rumah}}</td>
                                <td>{{ $dt->jenis_transportasi}}</td>
                                <td>{{ $dt->hub_keluarga }}</td>
                                <td>{{ $dt->tanggungan}}</td>
                                <td>
                                    <a href="{{ route("bansos.index") }}">{{ $dt->jenis_bansos }}</a>
                                </td>
                                <td>
                                  @php
                                    $roles = ['psm', 'kelurahan'];
                                  @endphp
                                  @if (!in_array(Auth::user()->role, $roles))
                                    <div class="row">
                                      <a class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" title='Edit' href="{{ route('dtks.edit', ['id' => $dt->id]) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                      </a>

                                      <form action="{{ route('dtks.destroy', ['id' => $dt->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus data?');">
                                        @csrf
                                        @method('delete')

                                        <button class="btn btn-danger btn-sm mr-1" data-toggle="tooltip" title='Delete'>
                                          <i class="fas fa-trash"></i>
                                        </button>
                                      </form>
                                    </div>
                                  @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
@section('script')
<script>
    $(document).ready( function () {
        $('#table_id').DataTable();
    });
</script>
@endsection
