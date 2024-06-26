@extends('layouts.template')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::get('deleted'))
        <div class="alert alert-success">
            {{ Session::get('deleted') }}
        </div>
    @endif
    <table class="table mt-5 table-striped table-bordered table-hovered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($medicines as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['type'] }}</td>
                <td>{{ $item['price'] }}</td>
                <td>{{ $item['stock'] }}</td>
                <td class="d-flex">
                    <a href="{{ route('medicine.edit', $item['id']) }}" class="btn btn-success">Edit</a>
                    {{-- method ::delete tidak bisa digunakan pada a href, harus melalui melalui form action  --}}
                    <form action="{{ route('medicine.delete', $item['id']) }}" method="post" class="ms-3">
                        @csrf
                        {{-- menimpa/mengubah method="post" agar menjadi method="delete" sesuai dengan method route (::delete) --}}
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        @if ($medicines->count())
            {{ $medicines->links() }}
        @endif
    </div>
@endsection