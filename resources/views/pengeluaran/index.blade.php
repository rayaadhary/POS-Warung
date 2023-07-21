@extends('layouts.master')

@section('title')
    Daftar pengeluaran
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar pengeluaran</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button type="button" onclick="addForm('{{ route('pengeluaran.store') }}')"
                        class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Nominal</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('pengeluaran.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('pengeluaran.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: 'nominal'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('#modal-form').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                        .done((response) => {
                            $('#modal-form').modal('hide');
                            Swal.fire('Berhasil!', 'Data telah disimpan.', 'success');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            Swal.fire('Error!', 'Tidak dapat menyimpan data.', 'error');
                            return;
                        });
                }
            });
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah pengeluaran');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=tanggal]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit pengeluaran');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=tanggal]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=tanggal]').val(response.tanggal);
                    $('#modal-form [name=deskripsi]').val(response.deskripsi);
                    $('#modal-form [name=nominal]').val(response.nominal);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }
    </script>

    <script src="{{ asset('js/delete.js') }}"></script>
@endpush
