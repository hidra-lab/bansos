@extends('layouts.admin.app')

@section('content')
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>RT</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">AHP</a></div>
          <div class="breadcrumb-item"><a href="#">RT</a></div>
          <div class="breadcrumb-item">RT</div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">RT</h2>
        <p class="section-lead">
           RT.
        </p>

        <div class="row">
          
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>RT</h4>
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
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listRT as $rt)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $rt->dtks->nik }}</td>
                                <td>{{ $rt->dtks->nama }}</td>
                                <td>{{ $rt->dtks->umur }}</td>
                                <td>{{ $rt->dtks->jenis_bansos}}</td>
                                <td>{{ $rt->score_rt }}</td>
                                <td>{{ $rt->rank_rt }}</td>
                                <td>{{ $rt->ket_rt }}</td>
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