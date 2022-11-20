@extends('layouts.admin.app')
@section('content')
<div class="main-content" style="min-height: 514px;">
    <section class="section">
      <div class="section-header">
        <h1>Data Alternatif</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
          <div class="breadcrumb-item"><a href="#">Data Alternatif</a></div>
          <div class="breadcrumb-item"> Data Alternatif</div>
        </div>
      </div>
      <div class="section-body">
        <h2 class="section-title">Data Alternatif</h2>
        <p class="section-lead">
            Data Alternatif.
        </p>

        <div class="row">
          
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Filter RT/RW</h4>
              </div>

              <div class="card-body">
                <div class="form-group">
                  <form action="{{ route('alternatif.index') }}" class="d-flex"  method="GET">
                    <div class="flex-grow-1">
                        <select name="rt" class="form-control">
                            <option value="" disabled selected>Pilih RT</option>
                            @foreach($rt as $item)
                              <option value="{{ $item }}" @if(request('rt') == $item) selected @endif>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-grow-1 ml-2">
                      <select name="rw" class="form-control">
                        <option value="" disabled selected>Pilih RW</option>
                        @foreach($rw as $item)
                          <option value="{{ $item }}" @if(request('rw') == $item) selected @endif>{{ $item }}</option>
                        @endforeach
                      </select>
                    </div>

                    <button class="btn btn-primary w-25 ml-2" type="submit">Cari</button>
                    <button class="btn btn-info w-25 ml-2" type="button" id="reset">Clear</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Data Alternatif</h4>
              </div>
              <div class="card-body">
                <div class="float-left">
                  <div class="form-group">
                      <div class="input-group mb-3">
                        <a href="{{ route('alternatif.proses', ['rt' => request('rt'), 'rw' => request('rw')]) }}" class="btn btn-primary text-centerRoute::get('/', [AlternatifController::class, 'index'])->name('alternatif.index'); ml-2">Proses</a>
                      </div>
                    </form>
                  </div>
                </div>
                
                <div class="clearfix mb-3"></div>

                <div class="table-responsive">
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>RT/RW</th>
                                <th>Nama</th>
                                @if(Auth::user()->role !== 'kelurahan')
                                  <th>Jumlah Kelayakan</th>
                                  <th>Jumlah Warga</th>
                                  <th>hubungan Keluarga</th>
                                @endif

                                @if(Auth::user()->role !== 'psm')
                                  <th>Tepat Sasaran</th>
                                  <th>Tepat Jumlah</th>
                                  <th>Tepat Administrasi</th>
                                @endif

                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alternatif as $alt)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $alt->dtks->rt }}/{{ $alt->dtks->rw }}</td>
                                <td>{{ $alt->dtks->nama }}</td>
                                @if(Auth::user()->role !== 'kelurahan')
                                  <td>{{ $alt->ps1_label }}</td>
                                  <td>{{ $alt->ps2_label }}</td>
                                  <td>{{ $alt->dtks->hub_keluarga }} ({{ $alt->ps3 }})</td>
                                @endif

                                @if(Auth::user()->role !== 'psm')
                                  <td>{{ $alt->kl1_label }}</td>
                                  <td>{{ $alt->kl2_label }}</td>
                                  <td>{{ $alt->kl3_label }}</td>
                                @endif

                                <td>
                                  <a class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title='Edit' href="{{ route('alternatif.edit', ['id' => $alt->id]) }}">
                                    <i class="fas fa-pencil-alt"></i>
                                  </a>
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

        $('#reset').click(function () {
          window.location = window.location.href.split("?")[0];
        })
    });
</script>
@endsection
