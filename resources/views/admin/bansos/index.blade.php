@extends('layouts.admin.app')
@section('content')
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>Bansos</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
          <div class="breadcrumb-item"><a href="#">Bansos</a></div>
          <div class="breadcrumb-item"> Semua Bansos</div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">Bansos</h2>
        <p class="section-lead">
            Bansos.
        </p>

        <div class="row">
          
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Bansos</h4>
              </div>
              <div class="card-body">
                
                
                <div class="clearfix mb-3"></div>

                <div class="table-responsive">
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Bansos</th>
                                <th>Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($bansos as $bs)
                            @php $i++ @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $bs->jenis_bansos }}</td>
                                <td>{{ $bs->info }}</td>
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