    @extends ('layouts.template')

    @section('content')
    <div class="mt-5">
        <form action="{{ route('order.search') }}" method="GET" class="card bg-light mt-5 p-5">
            <div class="d-flex justify-content-end">
                <a href="{{ route('order.create') }}" class="btn btn-secondary"><i class="bi bi-cart-plus"></i> Tambah Pembelian</a>
            </div>
            <div class="d-flex justify-content-start">
                <input type="date" style="width: 30%;" name="search" class="form-control">
                <button class="btn btn-primary"><i class="bi bi-search"></i>Cari</button>
                <a href="{{ route('order.index') }}" class="btn btn-secondary" style="margin-left: 5px;">Reset</a>
            </div>
            <table class="table mt-5 table-striped table-bordered table-hovered">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Nama Pembelian</th>
                    <th>Pesanan</th>
                    <th>Total Harga</th>
                    <th>Penanggung Jawab</th>
                    <th>Taggal Memesan</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        {{-- currentpage: mengambil posisi di page keberapa -1 (misal udah klik next lagi ada di page 2 berarti jadi 2-1 = 1), perpage: mengambil jumlah data yg ditampilkan per page nya berapa (ada di controller bagian paginate/simplePaginate, misal 5), loop->index: mengambil index dari array (mulai dari 0)+1 --}}
                        {{-- jadi: (2-1) x 5+1 = 6 (dimulai dari angka enam di page keduanya) --}}
                    <td>{{ ($orders->currentPage()-1) * $orders->perpage() + $loop->index + 1 }}</td>
                    <td>{{ $order['name_customer'] }}</td>
                    <td>
                        <ol>
                            @foreach ($order['medicines'] as $medicine)
                            <li>{{ $medicine['name_medicine'] }} <small>Rp. {{ number_format($medicine['price'], 0, '.'.',')}} <b>(qty : {{ $medicine['qty'] }})</b></small> = Rp. {{ number_format($medicine['price_after_qty'],0,'.'.',') }}</li>
                            @endforeach
                        </ol>
                    </td>
                    @php
                    $ppn = $order['total_price'] * 0.1;
                    @endphp
                    <td>Rp. {{ number_format(($order['total_price']+$ppn),0,'.'.',') }}</td>
                    {{-- mengambil column dari relasi, $variable['namaFunctionDiModel']['namaColumnDiRelasi'] --}}
                    <td>{{ $order['user']['name'] }} <a href="mailto:kasir@gmail.com">{{ $order['user']['email'] }}</a></td>
                    @php
                    setLocale(LC_ALL, 'IND')
                    @endphp
                    <td>{{ Carbon\Carbon::parse($order['created_at'])->formatLocalized('%d %B %Y') }}</td>
                    <td>
                        <a href="{{ route('order.download-pdf', $order['id']) }}" class="btn btn-success"><i class="bi bi-download"></i></a>
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                @if ($orders->count())
                {{ $orders->links()}}                        
                @endif
            </div> 
        </div>
        </form>
    @endsection