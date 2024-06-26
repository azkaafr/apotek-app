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

    <a href="{{ route('user.create') }}" class="btn btn-secondary mt-5 ">Tambah Pengguna</a>
    <table class="table mt-5 table-striped table-bordered table-hovered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($user as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['email'] }}</td>
                <td>{{ $item['role'] }}</td>
                <td class="d-flex">
                    <a href="{{ route('user.edit', $item['id']) }}" class="btn btn-success">Edit</a>
                    {{-- method ::delete tidak bisa digunakan pada a href, harus melalui melalui form action  --}}
                    <form action="{{ route('user.delete', $item['id']) }}" method="POST" class="ms-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Delete</button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfigurasi Hapus</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Yakin Anda Ingin Menghapus Data ini ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        @if ($user->count())
            {{ $user->links() }}
        @endif
    </div>
@endsection