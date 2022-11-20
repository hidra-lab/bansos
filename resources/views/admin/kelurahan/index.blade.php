@extends('layouts.admin.app')
@section('content')
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>Kelurahan</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">AHP</a></div>
          <div class="breadcrumb-item"><a href="#">Kelurahan</a></div>
          <div class="breadcrumb-item">Kelurahan</div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">Kelurahan</h2>
        <p class="section-lead">
            Kelurahan.
        </p>

        <div class="row">
          
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Kelurahan</h4>
              </div>
              <div class="card-body">
                
                
                <div class="clearfix mb-3"></div>

                <div class="table-responsive">
                  <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nik</th>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Jenis Bansos</th>
                            <th>Score</th>
                            <th>Peringkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listKL as $kl)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $kl->dtks->nik }}</td>
                            <td>{{ $kl->dtks->nama }}</td>
                            <td>{{ $kl->dtks->umur }}</td>
                            <td>{{ $kl->dtks->jenis_bansos}}</td>
                            <td>{{ $kl->score_kl }}</td>
                            <td>{{ $kl->rank_kl }}</td>
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