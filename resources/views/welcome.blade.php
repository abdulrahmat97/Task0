<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
    <!-- Fonts -->
    {{--<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">--}}
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable.css') }}">

</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="col-md-12">
        <h3>Table Stok</h3>
        '
        <button id="barangmasuk" class="btn btn-primary" onclick="inputBrngMsk(' + data +')">Input Barang Masuk</button>
        '
        <br>
        <table id="stok" class="display table table-responsive table-bordered" width="100%" cellspacing="0">
            <thead>
            <tr>
                <td>Nama Barang</td>
                <td>Stok</td>
                <td>Action</td>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td>Nama Barang</td>
                <td>Stok</td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('script.js') }}"></script>
<script src="{{ asset('datatables.js') }}"></script>
<script>
    var apiURL = "http://127.0.0.1:9000/api/";

    var dataTabelStok = $('#stok').DataTable({
        "processing": true,
        "ajax": {
            "url": apiURL + 'stokbarang',
            "dataSrc": ""
        },
        "columns": [
            {"data": "namabarang"},
            {"data": "stok"},
            {
                "data": "id",
                "render": function (data, type, row, meta) {
                    return '<button type="button" class="btn btn-link btn-sm" onclick="editStok(' + data + ')" ' +
                        'title="Edit"> <i class="glyphicon glyphicon-pencil"></i> </button>' +
                        '&emsp;' + '<button type="button" data-id="' + data + '" class="btn btn-link btn-sm"' +
                        ' onclick="destroy(' + data + ', this)" title="Hapus"><i class="glyphicon glyphicon-trash"></i></button>' + '&emsp;' +
                        '<button id="barangmasuk" class="btn btn-primary" onclick="inputBrngMsk(' + data + ')">Input Barang Masuk</button>'
                }
            },
        ],
        "pagingType": "simple_numbers"
    });

    $('#stok tfoot tr td').each(function () {
        $(this).html('<input type="text" class="form-control input-sm" style="width: 100%" placeholder="Search ' + $(this).text() + '" />');
    });

    dataTabelStok.columns().every(function () {
        var th = this;
        $('input', this.footer()).on('keyup change', function () {
            if (th.search() !== this.value) {
                th.search(this.value).draw();
            }
        });
    });

    function editStok(id) {
        var _this = this;
        $.getJSON({
            url: apiURL + 'stokbarang/' + id,
            success: function (data) {
                bootbox.dialog({
                    title: 'Edit Datadta #' + data.id,
                    message: _this.form(data),
                    buttons: {
                        main: {
                            label: 'Save',
                            className: 'btn btn-primary btn-sm',
                            callback: function () {
                                _this.putStok(data.id, $('form.editStok').serialize());
                            }
                        }
                    }
                });
            }
        });
    }

    function putStok(id, serializeData) {
        $.ajax({
            url: apiURL + 'stokbarang/' + id,
            type: 'PUT',
            data: serializeData,
            success: function (res) {
                bootbox.alert('Success edit Data #' + res.id);
                console.log('[Edit] message from server : ' + JSON.stringify(res));
                dataTabelStok.draw();
            }
        })
    }

    function form(data) {
        var _form = $('<form></form>').addClass('form').addClass('editStok').attr('role', 'form');
        var inputNmBrng = _form.append($('<div></div>').addClass('form-group'));
        inputNmBrng.append($('<label></label>').addClass('label-control').text('Nama Barang'));
        inputNmBrng.append($('<input>').addClass('form-control').attr('name', 'namabarang').attr('type', 'text').val(data.namabarang));

        var inputStok = _form.append($('<div></div>').addClass('form-group'));
        inputStok.append($('<label></label>').addClass('label-control').text('Stok'));
        inputStok.append($('<input>').addClass('form-control').attr('name', 'stok').attr('type', 'text').val(data.stok));

        return _form
    }

    function destroy(id, comp) {
        var target = $(comp).closest('tr').get(0);
        bootbox.confirm('Anda Yakin ?', function (res) {
            if (res == true) {
                $.ajax({
                    url: apiURL + 'stokbarang/' + id,
                    type: 'DELETE',
                    success: function (r) {
                        target.remove();
                        console.log('[Delete] message from serve: ' + JSON.stringify(r));
                    }
                })
            }
        })
    }

    function inputBrngMsk(id) {
        var _this = this;
        $.getJSON({
            url: apiURL + 'stokbarang/' + id,
            success: function (data) {
                bootbox.dialog({
                    title: 'Input Barang Masuk #' + data.id,
                    message: _this.formMasuk(data),
                    buttons: {
                        main: {
                            label: 'Save',
                            className: 'btn btn-primary btn-sm',
                            callback: function () {
                                _this.barangMasuk(data.id, $('form.formMasuk').serialize());
                            }
                        }
                    }
                });
            }
        });
    }

    function barangMasuk(id, serializeData) {
        $.ajax({
            url: apiURL + 'barangmasuk',
            type: 'POST',
            data: serializeData,
            success: function (res) {
                bootbox.alert('Success Barang masuk #' + res.id);
                console.log('[Input] message from server : ' + JSON.stringify(res));
                dataTabelStok.draw();
            }
        })
    }

    function formMasuk(data) {
        var _form = $('<form></form>').addClass('form').addClass('barangMasuk').attr('role', 'form');
        var inputNmBrng = _form.append($('<div></div>').addClass('form-group'));
        inputNmBrng.append($('<h5></h5>').addClass('label-control').text(data.namabarang));

        var jmlhMsk = _form.append($('<div></div>').addClass('form-group'));
        jmlhMsk.append($('<label></label>').addClass('label-control').text('Jumlah'));
        jmlhMsk.append($('<input>').addClass('form-control').attr('name', 'jmlhMasuk').attr('type', 'text'));
        jmlhMsk.append($('<input>').addClass('form-control').attr('name', 'id').attr('type', 'text').val(data.id).attr('hidden'));

        return _form
    }

</script>
</body>
</html>
