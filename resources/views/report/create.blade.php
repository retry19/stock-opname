@extends('layouts.master')

@section('title')
    Tambah Data Laporan
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
    @parent
    <li><a href="{{ route('report.index') }}">Laporan Stok Opname</a></li>
    <li class="active">Tambah Data</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <a href="{{ route('report.index') }}" target="_blank" class="btn btn-default btn-xs btn-flat"><i class="fa fa-chevron-left"></i> Kembali</a>
            </div>
            <div class="box-body">
                <form action="{{ route('report.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="name">Nama Pemilik Toko</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="period">Periode</label>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-xs-6 col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="period_start" id="period_start" class="form-control datepicker" placeholder="Tanggal awal" required>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="period_end" id="period_end" class="form-control datepicker" placeholder="Tanggal akhir" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 24px; margin-bottom: 24px">
                        <div class="col-md-offset-3 col-md-2">
                            <label for="period">Cari Kode Barang</label>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="kode_produk">
                                <span class="input-group-btn">
                                    <button onclick="showProduct()" class="btn btn-info btn-flat" type="button">
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <table class="table table-striped table-bordered" id="product-table">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th width="15%">Qty</th>
                                <th>Harga Beli</th>
                                <th>Jumlah</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center" id="row-empty">Barang kosong, silahkan tambah barang terlebih dahulu</td>
                            </tr>
                        </tbody>
                    </table>
    
                    <div class="text-right" style="margin-top: 40px">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-product" tabindex="-1" role="dialog" aria-labelledby="modal-product">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Daftar Produk</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered" id="modal-table-product">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga Beli</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="label label-success">{{ $product->kode_produk }}</span></td>
                                <td>{{ $product->nama_produk }}</td>
                                <td>{{ $product->harga_beli }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary button-select-product" data-id="{{ $product->id_produk }}" data-row="{{ $product->toJson() }}">Pilih</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });

    const modalProductTable = $('#modal-table-product').DataTable({ responsive: true })

    function showProduct() {
        $('#modal-product').modal('show');
        modalProductTable.search($('#kode_produk').val()).draw();
    }

    function checkProductExists() {
        const rowProductExists = $('.row-product').length > 0;
        $('button[type="submit"]').prop('disabled', !rowProductExists);
    }

    checkProductExists();

    $('.button-select-product').on('click', function() {
        $(this).data('id');
        $(this).prop('disabled', true);

        const productId = $(this).data('id');
        const row = $(this).data('row');

        if ($('#row-empty').length) {
            $('#row-empty').remove();
        }

        $('#product-table tbody').append(`
            <tr class="row-product" id="row-product-${row.id_produk}">
                <td><span class="label label-success">${row.kode_produk}</span></td>
                <td>${row.nama_produk}</td>
                <td><input type="number" name="items[${row.id_produk}]" class="form-control input-qty" data-id="${row.id_produk}" min="0" required></td>
                <td id="price-product-${row.id_produk}">${row.harga_beli}</td>
                <td id="total-product-${row.id_produk}">0</td>
                <td>
                    <button class="btn btn-sm btn-danger button-remove-product" data-id="${row.id_produk}">Hapus</button>
                </td>
            </tr>
        `)

        checkProductExists();
    });

    $('body').on('change', '.input-qty', function() {
        const productId = $(this).data('id');
        const value = $(this).val();
        const price = $(`#price-product-${productId}`).html();
        $(`#total-product-${productId}`).html(Number(price) * Number(value));
    });

    $('body').on('click', '.button-remove-product', function() {
        const productId = $(this).data('id');
        $(`#row-product-${productId}`).remove();
        $(`.button-select-product[data-id="${productId}"]`).prop('disabled', false);

        if ($('.row-product').length < 1) {
            $('#product-table tbody').append(`
                <tr>
                    <td colspan="6" class="text-center" id="row-empty">Barang kosong, silahkan tambah barang terlebih dahulu</td>
                </tr>
            `);
        }

        checkProductExists();
    });
</script>
@endpush