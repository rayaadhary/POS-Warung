@extends('layouts.master')

@section('title')
    Daftar pengguna
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar pengguna</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <div id="alerts-container"></div>
                    <button onclick="addForm('{{ route('pengguna.store') }}')" class="btn btn-success btn-xs btn-flat"><i
                            class="fa fa-plus-circle"></i> Tambah</button>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('pengguna.form')
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
                    url: '{{ route('pengguna.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
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
                            table.ajax.reload();
                            // Show success alert here
                            showAlert('success', response.message);
                        })
                        .fail((xhr) => {
                            $('#modal-form').modal('hide');
                            // Show error alert here
                            showAlert('danger', xhr.responseJSON.message);
                        });
                }
            });

            // Function to show Bootstrap alert
            function showAlert(type, message) {
                const alertDiv = `<div class="alert alert-${type} alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                ${message}
                              </div>`;
                $('#alerts-container').html(alertDiv);
            }
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah pengguna');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=name]').focus();

            $('#password, #password_confirmation').attr('required', true);
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit pengguna');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=name]').focus();

            $('#password, #password_confirmation').attr('required', false);

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=name]').val(response.name);
                    $('#modal-form [name=email]').val(response.email);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }
    </script>

    <script src="{{ asset('js/delete.js') }}"></script>
@endpush
