<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Exports\OrderExport;
use Excel;

class OrderController extends Controller
{
    public function search(Request $request) 
    {
        $search = $request->input('search');

        $orders = Order::whereDate('created_at', $search)->simplePaginate(3);

        return view('order.kasir.index', compact('orders'));
        
    }
    
    /**
     * Display a listing of the resource.
     */

     public function data()
     {
        $orders = Order::with('user')->simplePaginate(5);
        return view('order.admin.index', compact('orders'));
     }

    public function index()
    {
        // with: mengambil function relasi PK ke FK atau FK ke PK dari model
        // isi di petik disamakan dengan nama function di modelnya
        // dd untuk mengecek value
        // dd($orders);
        $orders = Order::with('user')->simplePaginate(5);
        return view('order.kasir.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view('order.kasir.create', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_customer' => 'required',
            'medicines' => 'required',
        ]);

        // array_count_values : menghitung jumlah item yang sama di dalam array
        // hasilnya berbentuk : "itemnya" => "jumlah yang sama"
        // menentukan qty
        $medicines = array_count_values($request->medicines);

        // penampung detail berbentuk array2 assoc dari obat2 yg  dipilih
        $dataMedicines = [];
        foreach ($medicines as $key => $value) {
            $medicine = Medicine::where('id', $key)->first();
            $arrayAssoc = [
                "id" => $key,
                "name_medicine" => $medicine['name'],
                "price" => $medicine['price'],
                "qty" => $value,
                // (int) memastikan dan mengubah tipe data menjadi integer
                "price_after_qty" => (int)$value * (int)$medicine['price'],
            ];
            // format assoc dimasukkan ke array penampung sebelumnya
            array_push($dataMedicines, $arrayAssoc);
        }
        $totalPrice = 0;
        // loop data dari array penampung yg udah di format
        foreach ($dataMedicines as $formatArray) {
            // dia bakal menjumlahkan totalPrice sebelumnya ditambah harga dari after qty
            $totalPrice += (int)$formatArray['price_after_qty'];
        }

        $prosesTambahData = Order::create([
            'name_customer' => $request->name_customer,
            'medicines' => $dataMedicines,
            'total_price' => $totalPrice,
            // user id menyimpan data id dari orang yang login (kasir penanggung jawab)
            'user_id' => Auth::user()->id,
        ]);
        // redirect ke halaman struk
        return redirect()->route('order.struk', $prosesTambahData['id']);
    }

    public function strukPembelian($id) 
    {
        $order = Order::where('id', $id)->first();

        return view('order.kasir.struk', compact('order'));
    }
    public function downloadPDF($id)
    {
        // get data yang akan ditampilkan di pdf
        // data yang dikirim ke pdf wajib bertipe array
        $order = Order::where('id', $id)->first()->toArray();

        // ketika data dipanggil di blade pdf, akan dipanggil dengan $ apa
        view()->share('order', $order);

        // lokasi dan nama blade yg akan di download ke pdf serta data yg akan ditampilkan
        $pdf = PDF::loadView('order.kasir.download', $order);

        // ketika di download nama filenya aoa
        return $pdf->download('Bukti Pembelian.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function downloadExcel()
    {
        $file_name = 'Data seluruh Pembelian.xlsx';
        return Excel::download(new OrderExport, $file_name);
    }

    public function cari(Request $request)
    {
        $cari = $request->input('cari');

        $orders = Order::whereDate('created_at', $cari)->simplePaginate(3);

        return view('order.admin.index', compact('orders'));
    }
}
