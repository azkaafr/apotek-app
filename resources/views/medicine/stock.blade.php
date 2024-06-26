@extends('layouts.template')

@section('content')
<div id="msg-success"></div>
    <table class="table table-striped table-bordered table-hovered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Stock</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($medicines as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$item['name']}}</td>
                <td style="background: {{ $item['stock'] <= 3 ? 'red' : 'none' }}">{{$item['stock']}}</td>
                <td>
                    <button class="btn btn-primary" onclick="edit({{$item['id']}})">Tambah Stock</button>
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
    <!-- Modal -->
<div class="modal fade" id="tambah-stock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Stock</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-stock" onsubmit="updateStock(); return false;">
        <div class="modal-body">
          <div id="msg"></div>
          <div>
            <input type="hidden" name="id" id="id">
            <label for="name" class="form-label">Nama Obat :</label>
            <input type="text" name="name" id="name" class="form-control" disabled>
          </div>
          <div>
            <label for="stock" class="form-label">Stock :</label>
            <input type="text" name="stock" id="stock" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" >Simpan</button>
        </div>
    </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
    <script>
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
      });

        function edit(id) {
            // panggil route dr web.php yg akan menangani proses ambil satu data
            let url = "{{ route('medicine.show', 'id') }}";
            // ganti bagian 'id' di url nya jadi data dari parameter id di function nya
            url = url.replace('id', id);

            $.ajax({
                type: 'GET',
                url: url,
                contentType: 'json',
                success: function (res) {
                    $("#tambah-stock").modal("show");
                    $("#name").val(res.name);
                    $("#stock").val(res.stock);
                    $("#id").val(res.id);
                }
            });
        }

        function updateStock() {
          let id = $("#id").val();
          let url = "{{ route('medicine.stock.update', 'id') }}";
          url = url.replace('id', id);

          let data = {
            stock: $("#stock").val(),
          }

          $.ajax({
            type: 'PATCH',
            url: url,
            data: data,
            cache: false,
            success: function (res) {
              $("#tambah-stock").modal("hide");
              sessionStorage.successUpdateStock = true;
              window.location.reload();
            },
            error: function (err) {
              $("#msg").attr("class", "alert alert-danger");
              $("#msg").text(err.responseJSON.message);
            }
          })
        }

        $(function () {
          if (sessionStorage.successUpdateStock) {
            $("#msg-success").attr("class", "alert alert-success");
            $("#msg-success").text("Berhasil mengubah Data stock!");
            // hapus kembali data session setelah alert success dimunculkan
            sessionStorage.clear();
          }
        })
    </script>
  @endpush
