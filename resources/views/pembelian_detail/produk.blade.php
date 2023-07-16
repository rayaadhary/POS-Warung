<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih produk</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-produk">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga Beli</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($produk as $key => $item)
                            <tr>
                                <td width="5%">{{ (int) $key + 1 }}</td>
                                <td><span class="label label-success    ">
                                        {{ $item->kode_produk }}</span></td>
                                <td>{{ $item->nama_produk }}</td>
                                <td>{{ $item->harga_beli }}</td>
                                <td><a href="#" class="btn btn-primary btn btn-flat"
                                        onclick="pilihProduk('{{ $item->id_products }}', '{{ $item->kode_produk }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
