@extends('layouts.admin')
@section('title')
    Menü
    <?php $lastOrder = 0; ?>
@endsection
@section('css')
    <style>
        tbody tr {
            transition: .4s;
        }

        tbody tr:hover {
            transition: unset;
            background-color: #FFFFFF;
            box-shadow: 1px 1px 10px #555;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <h5 class="card-title">Menü</h5>
                    <a class="btn-floating waves-effect waves-light teal  modal-trigger" title="Yeni Makale Ekle"
                       href="#newTag">
                        <i class="material-icons">add</i>
                    </a>
                    <table id="tblMenu" class="responsive-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>İşlem</th>
                            <th>Menü Başlığı</th>
                            <th>Aktif/Pasif</th>
                            <th>Kullanıcı Adı</th>
                            <th>Rota Türü</th>
                            <th>Rota</th>
                            <th>Oluşturma Tarihi</th>
                            <th>Güncelleme Tarihi</th>
                        </tr>
                        </thead>
                        <tbody id="dragDrop">
                        @foreach($list as $item)
                            <?php
                            if ($item->order > $lastOrder)
                            {
                                $lastOrder = $item->order;
                            }
                            ?>
                            <tr id="menu{{ $item->id }}" class="">
                                <td>{{ $item->id }}</td>
                                <td>
                                    <a href="javascript:void(0)" class="deleteCategory" data-id="{{ $item->id }}">
                                        <i class="fas fa-trash  red-text"></i>
                                    </a>
                                    <a href="#editTag" class="editCategory modal-trigger"
                                       data-id="{{ $item->id }}">
                                        <i class="fas fa-edit  yellow-text"></i>
                                    </a>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if ($item->status)
                                        <a class="waves-effect waves-light btn green changeStatus"
                                           data-id="{{ $item->id }}">Aktif</a>
                                    @else
                                        <a class="waves-effect waves-light btn red changeStatus"
                                           data-id="{{ $item->id }}">Pasif</a>
                                    @endif
                                </td>
                                <td>{{ $item->getUser->name }}</td>
                                <td>
                                    @if ($item->route_type == 1)
                                        Dinamik Rota
                                    @elseif ($item->route_type == 2)
                                        Statik URL
                                    @endif
                                </td>
                                <td>{{ $item->route }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="newTag" class="modal">
        <div class="modal-content">
            <div class="row">
                <div class="col s12 l12">
                    <div class="card">
                        <div class="card-content">
                            <h5 class="card-title activator">Menü Ekleme</h5>
                            <form id="frmTag" action="{{route('menu.add')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">account_circle</i>
                                        <input name="name" id="name" type="text">
                                        <label for="name">Menü Başlığı</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">account_circle</i>
                                        <select name="route_type" id="route_type">
                                            <option value="1">Dinamik Rota</option>
                                            <option value="2">Statik URL</option>
                                        </select>
                                        <label for="name">Rota Türü</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">account_circle</i>
                                        <input name="route" id="route" type="text">
                                        <label for="name">Rota</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">account_circle</i>
                                        <input name="order" id="order" type="number" min="1" max="9999"
                                               value="{{$lastOrder+1}}">
                                        <label for="name">Sıralama</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <div class="switch">
                                            <label for="status">
                                                Pasif
                                                <input name="status" id="status" type="checkbox">
                                                <span class="lever"></span>
                                                Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s6">
                                        <button id="btnSave" class="btn green waves-effect btn-block" type="button">
                                            Değişiklikleri Kaydet
                                        </button>
                                    </div>
                                    <div class="input-field col s6">
                                        <button class="btn red waves-effect btn-block modal-close" type="button">
                                            Vazgeç
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editTag" class="modal modalEdit">
        <div class="modal-content">
            <div class="row">
                <div class="col s12 l12">
                    <div class="card">
                        <div class="card-content">
                            <h5 class="card-title activator">Menü Düzenleme</h5>
                            <form id="frmEditTag" action="{{route('menu.edit')}}" method="POST">
                                @csrf
                                <input id="idEdit" name="id" type="hidden">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">account_circle</i>
                                        <input name="name" id="nameEdit" type="text">
                                        <label for="nameEdit">Menü Başlığı</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">account_circle</i>
                                        <select name="route_type" id="route_typeEdit">
                                            <option value="1">Dinamik Rota</option>
                                            <option value="2">Statik URL</option>
                                        </select>
                                        <label for="route_typeEdit">Rota Türü</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">account_circle</i>
                                        <input name="route" id="routeEdit" type="text">
                                        <label for="routeEdit">Rota</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">account_circle</i>
                                        <input name="order" id="orderEdit" type="number" min="1" max="9999"
                                               value="9999">
                                        <label for="orderEdit">Sıralama</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <div class="switch">
                                            <label for="statusEdit">
                                                Pasif
                                                <input name="status" id="statusEdit" type="checkbox">
                                                <span class="lever"></span>
                                                Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s6">
                                        <button id="btnSaveEdit" class="btn green waves-effect btn-block" type="button">
                                            Güncelle
                                        </button>
                                    </div>
                                    <div class="input-field col s6">
                                        <button class="btn red waves-effect btn-block modal-close" type="button">
                                            Vazgeç
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('js')
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script>
        $(document).ready(function ()
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var newID,currentID,trID;
            $("#dragDrop").sortable({
                start: function (event, ui)
                {
                    newID=null;
                    currentID=null;
                    trID=null;
                    trID = ui.item[0].id.replace('menu','');
                    console.log(trID);
                    currentID = $(ui.item).index()+1;
                },
                update: function (event, ui)
                {
                    newID = $(ui.item).index()+1;
                    console.log('Eski: ' + currentID.toString());
                    console.log('Yeni: ' + newID.toString());
                }, stop: function (event, ui)
                {
                    if (newID!=null){
                    $.ajax({
                        method: 'POST',
                        url: '{{route('menu.editOrder')}}',
                        data: {
                            'trID' : trID,
                            'currentID': currentID,
                            'newID': newID
                        },
                        async:false,
                        success:function(response){
                        }
                    });
                    }

                }
            });


            function inputValidation(inputArray, formID)
            {
                let validation = true;
                for (let i = 0; i < inputArray.length; i++)
                {
                    var inputInfo = inputArray[i];
                    var input = $('#' + inputInfo.id).val();
                    if (input.trim() == "")
                    {
                        Swal.fire({
                            icon: 'error',
                            title: inputInfo.alertTitle,
                            text: inputInfo.alertTextAttr + ' boş bırakılamaz!',
                            confirmButtonText: 'Tamam'
                        });
                        validation = false;
                    }
                }
                validation ? $('#' + formID).submit() : '';
            }

            $('#btnSave').click(function ()
            {

                let inputArray = [
                    {
                        id: 'name',
                        alertTextAttr: 'Menü Başlığı',
                        alertTitle: "Uyarı",
                    },
                    {
                        id: 'route',
                        alertTextAttr: 'Rota',
                        alertTitle: "Uyarı",
                    }
                ];
                inputValidation(inputArray, 'frmTag');
            })



            $('.deleteTag').click(function ()
            {
                let dataID = $(this).data('id');
                let route = '{{route('tag.destroy', 'tagID')}}';
                route = route.replace('tagID', dataID);
                Swal.fire({
                    title: 'Uyarı',
                    text: `${dataID} ID'li etiketi silmek istediğinize emin misiniz?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet',
                    cancelButtonText: 'Hayır'
                }).then((result) =>
                {
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            url: route,
                            method: 'POST',
                            data: {
                                '_method': 'DELETE'
                            },
                            async: false,
                            success: function (response)
                            {
                                document.getElementById("tag" + dataID).remove();
                            },
                            error: function ()
                            {

                            }
                        })

                    }
                })


            });



            $('.editCategory').click(function ()
            {
                let dataID = $(this).data('id');
                let nameEdit = $('#nameEdit');
                let route_typeEdit = $('#route_typeEdit');
                let routeEdit = $('#routeEdit');
                let orderEdit = $('#orderEdit');
                let status = $('#statusEdit');
                let id = $('#idEdit');
                let self = $(this);

                $.ajax({
                    url: '{{ route('menu.editShow') }}',
                    method: 'POST',
                    data: {
                        id: dataID,
                    },
                    async: false,
                    success: function (response)
                    {
                        console.log(response);
                        $('label[for="nameEdit"]').addClass('active');
                        $('label[for="route_typeEdit"]').addClass('active');
                        $('label[for="routeEdit"]').addClass('active');
                        $('label[for="orderEdit"]').addClass('active');
                        let menu = response.menu;
                        console.log(menu);
                        nameEdit.val(menu.name);
                        $('#route_typeEdit option[value=' + menu.route_type + ']').prop('selected', true);
                        // route_typeEdit.val(menu.route_type.toString());
                        routeEdit.val(menu.route);
                        orderEdit.val(menu.order);
                        id.val(menu.id);
                        if (menu.status)
                        {
                            status.attr('checked', true);
                        }
                        else
                        {
                            status.attr('checked', false);
                        }
                    },
                    error: function ()
                    {

                    }
                })


            });


            $('#btnSaveEdit').click(function(){
                let inputArray = [
                    {
                        id: 'nameEdit',
                        alertTextAttr: 'Menü Başlığı',
                        alertTitle: "Uyarı",
                    },
                    {
                        id: 'routeEdit',
                        alertTextAttr: 'Rota',
                        alertTitle: "Uyarı",
                    }
                ];
                inputValidation(inputArray, 'frmEditTag');
            });
        });
    </script>

@endsection
