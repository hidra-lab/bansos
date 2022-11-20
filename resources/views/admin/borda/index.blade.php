@extends('layouts.admin.app')

@section('content')
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>Borda</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
          <div class="breadcrumb-item">Borda</div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">Borda</h2>
        <p class="section-lead">
           Borda.
        </p>

        <div class="row">
          
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Filter Tahun</h4>
              </div>

              <div class="card-body">
                <div class="form-group">
                  <form action="{{ route('borda.index') }}" class="d-flex"  method="GET">
                    <div class="flex-grow-1">
                        <select name="tahun" class="form-control" required autofocus>
                            <option value="" disabled selected>Pilih Tahun</option>
                            @foreach($years as $year)
                              <option value="{{ $year }}" @if(request('tahun') == $year) selected @endif>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary w-25 ml-2" type="submit">Cari</button>
                    <button class="btn btn-info w-25 ml-2" type="button" id="reset">Clear</button>
                    <a href="{{ route('borda.cetak') }}" class="btn btn-success ml-2 pt-2" style="width: 100px">Cetak</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Borda</h4>
              </div>
              <div class="card-body">
                
                
                <div class="clearfix mb-3"></div>

                <div class="table-responsive">
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Bobot RT</th>
                                <th>Bobot PSM</th>
                                <th>Bobot KL</th>
                                <th>Score</th>
                                <th>Rank</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listBorda as $borda)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $borda->dtks->nama }}</td>
                                <td>{{ $borda->bobot_rt }}</td>
                                <td>{{ $borda->bobot_psm }}</td>
                                <td>{{ $borda->bobot_kl }}</td>
                                <td>{{ $borda->score }}</td>
                                <td>{{ $borda->rank }}</td>
                                <td>{{ $borda->kelayakan }}</td>
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

        $('#reset').click(function () {
          window.location = window.location.href.split("?")[0];
        })
    });
</script>
@endsection