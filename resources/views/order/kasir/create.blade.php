@extends('layouts.template')

@section('content')
    <form action="{{ route('order.store') }}" method="POST" class="card p-4 mt-5">
        @csrf
        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach (errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            
        @endif
        <div class="mb-3 d-flex align-items-center">
            <label for="name_customer" class="form-label" style="width: 16%">Penanggung Jawab :</label>
            <p style="width: 84%; margin-top:10px"  ><b>{{ Auth::user()->name }}</b></p>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <label for="name_customer" class="form-label" style="width: 12%">Nama Pembeli :</label>
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width: 88%">
        </div>
        <div class="mb-3">
            <div class=" d-flex align-items-center mb-3">
            <label for="medicines" class="form-label" style="width: 12%">Obat :</label>
            {{-- name dengan [] biasanya digunakan untuk column yang tipe datanya json/array, dan biasanya digunakan apabila input dengan tujuan data yang sama banyak (dan dari banyak input yg datanya sama tsb, datanya akan diambil dlm bentuk array) --}}
            <select name="medicines[]" id="medicines" class="form-control" style="width: 88%">
                <option selected hidden disabled>Pesanan 1</option>
                @foreach ($medicines as $medicine)
                    <option value="{{ $medicine['id'] }}">{{ $medicine['name'] }}</option>
                @endforeach
            </select>
        </div>
            {{-- karena akan ada JS yg menampilkan select ketika di klik, maka disediakan tempat penyimpanan element yg akan dihasilkan dari JS tersebut --}}
            <div id="wrap-select"></div>
            <p class="text-primary" style="margin-left: 12%; margin-top:15px; cursor:pointer;" onclick="addSelect()"><i class="bi bi-cart-plus"></i>Tambah Pesanan</p>
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
@endsection

@push('script')
    <script>
        let no = 2;
        function addSelect() {
            let el = `<div class="mb-3 d-flex align-items-center mb-3">
            <label for="medicines" class="form-label" style="width: 12%"></label>
            <select name="medicines[]" id="medicines" class="form-control" style="width: 88%">
                <option selected hidden disabled>Pesanan ${no}</option>
                @foreach ($medicines as $medicine)
                    <option value="{{ $medicine['id'] }}">{{ $medicine['name'] }}</option>
                @endforeach
            </select>
        </div>`;
        // gunakan JQuery untuk memanggil html tempat el baru akan ditambahkan
        // append : menambahkan html dibagian bawah sebelum penutup tag terkait
        // menggunakan # karena wrap-select di html nya diisi dibagian id
        $("#wrap-select").append(el);
        // agar no pesanan berubah sesuai jumlah select
        no++;
        }
    </script>
@endpush