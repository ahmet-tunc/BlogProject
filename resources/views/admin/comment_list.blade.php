@extends('layouts.admin')
@section('title')
    Yorumlar
@endsection
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="col s12">
                        <div class="col s12 m6">
                            <h5 class="card-title">Yorumlar</h5>
                        </div>
                        <div class="col s12 m6">
                            <div class="row right">
                                <a id="btnDelete" class="btn orange modal-trigger s4" style="width: 170px" title="Seçili yorumları sil"
                                   href="javascript:void(0)">Seçili Yorumları Sil
                                </a>
                                <a id="btnSelectAll" class="btn blue modal-trigger s4" style="width: 170px" title="Seçili yorumları sil"
                                   href="javascript:void(0)">Tümünü Seç
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tblComment" class="responsive-table">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Ad Soyad</th>
                            <th>Mail</th>
                            <th>Post</th>
                            <th>Aktif/Pasif</th>
                            <th>Oluşturma Tarihi</th>
                        </tr>
                        </thead>
                        <tbody id="dragDrop">
                        @foreach($comment as $item)
                            <tr id="row{{$item->id}}">
                                <td>
                                    <label>
                                        <input name="checkComment" id="{{$item->id}}" type="checkbox"/>
                                        <span></span>
                                    </label>

                                </td>
                                <td>
                                    <a href="{{ route('post.edit', $item->id) }}">
                                        <i class="fas fa-search  blue-text"></i>
                                    </a>
                                </td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->getPost->title}}</td>
                                <td>
                                    @if ($item->status)
                                        <a class="waves-effect waves-light btn green changeStatus"
                                           data-id="{{ $item->id }}">Aktif</a>
                                    @else
                                        <a class="waves-effect waves-light btn red changeStatus"
                                           data-id="{{ $item->id }}">Pasif</a>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('j F Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 center-align justify-content-center">
                    {{$comment->links('vendor.pagination.commentPaginate')}}

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
                                               value="">
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


@endsection
@section('js')
    <script>
        $(document).ready(function ()
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let control = false;
            $('#btnSelectAll').click(function ()
            {
                let btnSelectAll = document.getElementById('btnSelectAll');
                let checkComment = document.getElementsByName('checkComment');
                if (!control)
                {
                    for (let i = 0; i < checkComment.length; i++)
                    {
                        checkComment[i].checked = true;
                    }
                    btnSelectAll.classList.remove('blue');
                    btnSelectAll.classList.add('red');
                    btnSelectAll.innerText="Seçimi Kaldır";
                    control = true;
                }
                else
                {
                    for (let i = 0; i < checkComment.length; i++)
                    {
                        checkComment[i].checked = false;
                    }
                    btnSelectAll.classList.remove('red');
                    btnSelectAll.classList.add('blue');
                    btnSelectAll.innerText="Tümünü Seç"
                    control = false;
                }

            });

            $('#btnDelete').click(function()
            {
                let arrayComment = [];
                let checkComment = document.getElementsByName('checkComment');
                for (let i = 0; i < checkComment.length; i++)
                {
                    if (checkComment[i].checked)
                    {
                        arrayComment.push(checkComment[i].id);
                    }
                }

                Swal.fire({
                    title: 'Uyarı',
                    text: 'Seçili '+ arrayComment.length +' yorumu silmek istediğinize emin misiniz?',
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
                           url:'{{route('comment.delete')}}',
                           method:'POST',
                           data:{
                               'id':arrayComment
                           },
                            async:false,
                            success:function (response){
                               console.log('başarılı');
                            },
                            error:function(error){

                            }
                        });
                    }
                })
            });


        });
    </script>

@endsection
