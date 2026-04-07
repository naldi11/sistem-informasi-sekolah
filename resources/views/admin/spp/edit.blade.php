@extends('layouts.admin')
@section('title', 'Edit Nominal SPP')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header py-3">Edit Nominal SPP</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.spp.update', $spp) }}">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-medium">Kelas</label>
                            <select name="kelas_id" class="form-select" required>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ $spp->kelas_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control" value="{{ $spp->nominal }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" value="{{ $spp->tahun_ajaran }}"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Berlaku Mulai</label>
                            <input type="date" name="berlaku_mulai" class="form-control"
                                value="{{ $spp->berlaku_mulai->format('Y-m-d') }}" required>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.spp.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection