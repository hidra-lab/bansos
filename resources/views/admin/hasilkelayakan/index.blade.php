@extends('layouts.admin.app')
@section('content')
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>Data DTKS Bantuan Sosial</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
          <div class="breadcrumb-item"><a href="#">Hasil Kelayakan</a></div>
          <div class="breadcrumb-item"> Hasil Kelayakan</div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">Hasil Kelayakan</h2>
        <p class="section-lead">
            Hasil Kelayakan DTKS Bantuan Sosial.
        </p>

        <div class="row">
          
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Hasil Kelayakan</h4>
              </div>
              <div class="card-body">
                
                
                <div class="clearfix mb-3"></div>

                <div class="table-responsive">
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No KK</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                                <th>Umur</th>
                                <th>Jenis Kelamin</th>
                                <th>Pekerjaan</th>
                                <th>Hubungan Keluarga</th>
                                <th>Bansos yang Dapat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($hasilkelayakan as $hk)
                            @php $i++ @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $hk->no_kk }}</td>
                                <td>{{ $hk->nik }}</td>
                                <td>{{ $hk->nama }}</td>
                                <td>{{ $hk->tgl_lahir }}</td>
                                <td>{{ $hk->umur }}</td>
                                <td>{{ $hk->jk }}</td>
                                <td>{{ $hk->pekerjaan}}</td>
                                <td>{{ $hk->hub_keluarga }}</td>
                                <td>{{ $hk->bansos->jenis_bansos }}</td>
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

    } );
</script>
@endsection