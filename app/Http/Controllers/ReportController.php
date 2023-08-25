<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Report;
use App\Models\ReportItem;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    function index() : View {
        return view('report.index');
    }

    function list() : JsonResponse {
        $reports = Report::orderBy('id', 'desc')->get();

        return DataTables::of($reports)
            ->addIndexColumn()
            ->addColumn('period', function($row) {
                return $row->period_start . ' - ' . $row->period_end;
            })
            ->addColumn('action', function($row) {
                return '<a href="'.route('report.print', $row->id).'" target="_blank" class="btn btn-success btn-sm btn-flat"><i class="fa fa-file-excel-o"></i> Cetak PDF</a>';
            })
            ->make(true);
    }

    function add() : View {
        return view('report.add');
    }

    function create() : View {
        $products = Produk::get();

        return view('report.create', compact('products'));
    }

    function store(Request $request) : RedirectResponse {
        $request->validate([
           'name' => 'required|string|max:255',
           'period_start' => 'required|date', 
           'period_end' => 'required|date|after:period_start',
           'items' => 'required|array',
        ]);

        $products = Produk::whereIn('id_produk', array_keys($request->items))->get();

        DB::beginTransaction();

        try {
            $report = Report::create([
                'name' => $request->name,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
            ]);

            $items = [];
            foreach ($request->items as $productId => $qty) {
                $product = $products->firstWhere('id_produk', $productId);
                $items[] = [
                    'id_report' => $report->id,
                    'id_product' => $productId,
                    'qty' => $qty,
                    'price' => $product->harga_beli,
                    'total' => $product->harga_beli * $qty,
                    'created_at' => now()->toDateTimeLocalString(),
                    'updated_at' => now()->toDateTimeLocalString(),
                ];

                $product->increment('stok', $qty);
            }

            ReportItem::insert($items);

            Session::flash('success-message', 'Laporan telah berhasil disimpan');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error-message', 'Laporan telah gagal disimpan');
        }

        return redirect()->route('report.index');
    }

    function print($id) {
        $report = Report::with(
            'reportItems',
            'reportItems.produk:id_produk,nama_produk',
        )->find($id);

        $pdf = PDF::loadView('report.pdf', compact('report'));
        return $pdf->download("data-laporan-stock-opname-{$report->period_start}-{$report->period_end}.pdf");
    }
}
