@extends('layouts.admin.app')
@section('content')
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>Data Warga</h1>
        @php
         $roles = ['psm', 'kelurahan'];
        @endphp
        @if (!in_array(Auth::user()->role, $roles))
          <a href="{{ route('warga.create') }}" class="btn btn-primary pt-2 ml-4">Tambah Warga</a>
        @endif  
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
          <div class="breadcrumb-item"><a href="#">Data Warga</a></div>
          <div class="breadcrumb-item"> Semua Data Warga</div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">Semua Data Warga</h2>
        <p class="section-lead">
          Semua Data Warga.
        </p>

        <div class="row">
          
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Data Warga</h4>
              </div>
              <div class="card-body">
              
                @if (Auth::user()->role !== 'kelurahan')
                  <div class="float-left">
                    <div class="form-group">
                      <form action="{{ route('warga.uploadDocument') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <label>File</label>

                        <div class="input-group mb-3">
                          <input type="file" name="uploaded_file" class="form-control">
                          <input type="submit" value="Upload" class="btn btn-primary btn-sm">
                        </div>
                        
                      </form>
                    </div>
                  </div> 
                @endif  
                <div class="clearfix mb-3"></div>

                <div class="table-responsive">
                    <table table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No KK</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir </th>
                                <th>Pendidikan</th>
                                <th>Pekerjaan</th>
                                <th>Status Perkawinan</th>
                                <th>Hubungan Keluarga</th>
                                <th>Keterangan Warga</th>
                                <th style="width: 500px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($warga as $wg)
                            @php $i++ @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $wg->no_kk }}</td>
                                <td>{{ $wg->nik }}</td>
                                <td>{{ $wg->nama }}</td>
                                <td>{{ $wg->jk }}</td>
                                <td>{{ $wg->tmp_lahir }}</td>
                                <td>{{ $wg->tgl_lahir }}</td>
                                <td>{{ $wg->pendidikan }}</td>
                                <td>{{ $wg->pekerjaan}}</td>
                                <td>{{ $wg->status_perkawinan }}</td>
                                <td>{{ $wg->hub_keluarga }}</td>
                                <td>{{ $wg->ket_warga }}</td>
                                <td>
                                  @php
                                  $roles = ['psm', 'kelurahan'];
                                  @endphp
                                  @if (!in_array(Auth::user()->role, $roles))
                                    <div class="row d-flex justify-content-center">
                                      <a class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" title='Edit' href="{{ route('warga.edit', ['id' => $wg->id]) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                      </a>
                                      <form action="{{ route('warga.destroy', ['id' => $wg->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus data?');">
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