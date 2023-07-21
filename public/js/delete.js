function deleteData(url) {
    Swal.fire({
        title: "Apakah anda yakin ingin menghapus?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(url, {
                _token: $("[name=csrf-token]").attr("content"),
                _method: "delete",
            })
                .done((response) => {
                    Swal.fire("Berhasil!", "Data telah dihapus.", "success");
                    table.ajax.reload();
                })
                .fail((errors) => {
                    Swal.fire("Error!", "Tidak dapat menghapus data.", "error");
                    return;
                });
        }
    });
}
