<div class="comment-area-box">
    <h1>Yorumlar</h1>
    <ul class="comment-tree">
        @foreach($comment as $item)
            <li>
                @if($item->parent_id ==null)
                    <div class="comment-box">
                        <img alt="" src="{{asset('assets/FrontEnd/upload/others/avatar.png')}}">
                        <div class="comment-content">
                            <h4>{{$item->name}}</h4>
                            <span>{{\Carbon\Carbon::parse($item->created_at)->translatedFormat('j F Y')}}</span>
                            <a href="#commentDiv" onclick="document.getElementById('parentID').value={{$item->id}}">Cevapla</a>
                            <p>{{$item->comment}}</p>
                        </div>
                    </div>
                @endif
                <ul class="depth">
                    @foreach($comment as $itemChild)
                        @if($itemChild->parent_id == $item->id)
                            <li>
                                <div class="comment-box">
                                    <img alt="" src="{{asset('assets/FrontEnd/upload/others/avatar.png')}}">
                                    <div class="comment-content">
                                        <h4>{{$itemChild->name}}</h4>
                                        <span>{{\Carbon\Carbon::parse($itemChild->created_at)->translatedFormat('j F Y')}}</span>
                                        <a href="#commentDiv"
                                           onclick="document.getElementById('parentID').value={{$itemChild->parent_id}}">Cevapla</a>
                                        <p>{{$itemChild->comment}}</p>
                                    </div>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>

            </li>
        @endforeach

    </ul>
</div>

<div class="contact-form-box" id="commentDiv">
    <h1>Yorum Yap</h1>

    <div class="alert alert-danger" hidden role="alert">
        Doldurulması zorunlu alanları, lütfen boş bırakmayınız.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="alert alert-success" hidden role="alert">
        Yorumunuz başarılı bir şekilde gönderilmiştir. Yönetici onayından sonra paylaşılacaktır.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="comment-form" action="{{route('comment.save')}}" method="POST">
        <input type="hidden" id="parentID">
        <div class="row">
            <div class="col-md-4">
                <input name="name" id="name" type="text" placeholder="Ad Soyad*">
            </div>
            <div class="col-md-4">
                <input name="mail" id="mail" type="text" placeholder="Email*">
            </div>
            <div class="col-md-4">
                <input name="website" id="website" type="text" placeholder="Website (isteğe bağlı)">
            </div>
        </div>
        <textarea name="comment" id="comment" placeholder="Yorumunuzu buraya yazınız*"></textarea>

        <input type="button" id="btnSave" value="Gönder">
    </form>
</div>


<script>

    $(document).ready(function ()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#btnSave').click(function ()
        {
            let name = $('#name').val();
            let mail = $('#mail').val();
            let comment = $('#comment').val();
            let web = $('#website').val();
            let parentID = $('#parentID').val();
            if (name == '' || mail == '' || comment == '')
            {
                $('.alert-danger').removeAttr('hidden');
            }
            else
            {
                $.ajax({
                    url: '{{route('comment.save')}}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'name': name,
                        'mail': mail,
                        'comment': comment,
                        'web': web,
                        'parent_id': parentID,
                        'post_id':{{$post->id}},
                    },
                    async: false,
                    success: function (response)
                    {
                        $('.alert-danger').attr('hidden', 'true');
                        $('.alert-success').removeAttr('hidden');
                    },
                    error: function (error)
                    {
                        $('.alert-success').attr('hidden', 'true');
                        $('.alert-danger').removeAttr('hidden');
                    }
                });


            }
        });
    });

</script>
