@extends('layouts.master')

@section('title')
    Laporan Stok Opname
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Laporan Stok Opname</li>
@endsection

@section('content')

@if (Session::has('success-message'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-check"></i> Selamat!</h4>
    {{ Session::get('success-message') }}
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <a href="{{ route('report.create') }}" target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah Data</a>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Periode</th>
                        <th width="15%">Aksi</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
            url: '{{ route('report.list') }}',
        },
        columns: [
            {data: 'DT_RowIndex', searchable: false, sortable: false},
            {data: 'name'},
            {data: 'period'},
            {data: 'action', searchable: false, sortable: false},
        ],
        dom: 'Brt',
        bSort: false,
        bPaginate: false,
    });
</script>
@endpush