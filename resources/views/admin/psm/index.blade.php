@extends('layouts.admin.app')
@section('content')
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>PSM</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">AHP</a></div>
          <div class="breadcrumb-item"><a href="#">PSM</a></div>
          <div class="breadcrumb-item">PSM</div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">PSM (Pekerja Sosial Masyarakat)</h2>
        <p class="section-lead">
            PSM.
        </p>

        <div class="row">
          
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>PSM</h4>
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
                        @foreach ($listPSM as $ps)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $ps->dtks->nik }}</td>
                            <td>{{ $ps->dtks->nama }}</td>
                            <td>{{ $ps->dtks->umur }}</td>
                            <td>{{ $ps->dtks->jenis_bansos}}</td>
                            <td>{{ $ps->score_psm }}</td>
                            <td>{{ $ps->rank_psm }}</td>
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