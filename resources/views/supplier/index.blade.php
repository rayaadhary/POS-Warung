@extends('layouts.master')

@section('title')
    Daftar Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Supplier</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('supplier.store') }}')" class="btn btn-success btn-xs btn-flat"><i
                            class="fa fa-plus-circle"></i> Tambah</button>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('supplier.form')
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
                    url: '{{ route('supplier.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'telepon'
                    },
                    {
                        data: 'alamat'
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
            $('#modal-form .modal-title').text('Tambah Supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama]').val(response.nama);
                    $('#modal-form [name=telepon]').val(response.telepon);
                    $('#modal-form [name=alamat]').val(response.alamat);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }
    </script>

    <script src="{{ asset('js/delete.js') }}"></script>
@endpush
