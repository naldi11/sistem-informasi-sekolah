@extends('layouts.admin')
@section('title', 'Rekap Keseluruhan')

@section('content')
    <h5 class="fw-bold mb-3">Rekap Keseluruhan</h5>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" style="font-size:0.8rem;">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" class="form-control form-control-sm" value="{{ $tahunAjaran }}"
                        placeholder="2025/2026">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.8rem;">Semester</label>
                    <select name="semester" class="form-select form-select-sm">
                        <option value="1" {{ $semester == '1' ? 'selected' : '' }}>Ganjil</option>
                        <option value="2" {{ $semester == '2' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-primary btn-sm w-100">Tampilkan</button></div>
            </form>
        </div>
    </div>

    <div class="stat-card mb-3" style="background:linear-gradient(135deg,#4f46e5,#6366f1);">
        <div class="stat-label">Total Pemasukan Semester {{ $semester == '1' ? 'Ganjil' : 'Genap' }}</div>
        <div class="stat-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Total Tagihan</th>
                        <th>Lunas</th>
                        <th>Belum</th>
                        <th>% Lunas</th>
                        <th>Pemasukan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $r)
                        <tr>
                            <td class="fw-medium">{{ $r['nama_bulan'] }} {{ $r['tahun'] }}</td>
                            <td>{{ $r['total'] }}</td>
                            <td class="text-success fw-medium">{{ $r['lunas'] }}</td>
                            <td class="text-danger fw-medium">{{ $r['belum'] }}</td>
                            <td>
                                <div class="progress" style="height:20px;">
                                    <div class="progress-bar bg-success" style="width:{{ $r['persentase'] }}%">
                                        {{ $r['persentase'] }}%</div>
                                </div>
                            </td>
                            <td class="fw-bold">Rp {{ number_format($r['pemasukan'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection