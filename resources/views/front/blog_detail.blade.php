@extends('layouts.front')

@section('title')
@endsection

@section('sections')

    <section class="banner-page-section">
        <div class="container">
            <h1>{{$post->title}}</h1>
            <p>{{strip_tags(substr($post->content,0,50))}}</p>
        </div>
    </section>

    <section class="blog-page-section">
        <div class="container">
            <div class="title-section">
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="blog-page-box single-post">
                        <div class="blog-post">
                            <div class="post-gallery">
                                <img src="{{asset('storage/'.$post->image)}}" alt="{{$post->title}}">
                            </div>
                            <div class="post-content">
                                <h2><a href="{{route('blog.post',['category'=>$post->getCategory->slug,'post'=>$post->slug])}}">
                                       {{$post->title}}</a></h2>
                                <ul class="post-meta">
                                    <li>{{\Carbon\Carbon::parse($post->publish_date)->translatedFormat('j,F,Y')}}</li>
                                    <li><a href="{{route('blog',['q'=>'user','text'=>$post->user_id])}}">{{$post->getUser->name}}</a></li>
                                    <li><a href="{{route('blog.category',['category' => $post->getCategory->slug])}}">{{$post->getCategory->name}}</a></li>
                                </ul>
                                {!! $post->content !!}
                            </div>
                        </div>

                        <div class="share-box">
                            <ul class="share-list">
                                <li><span>Share:</span></li>
                                <li><a href="#">facebook</a></li>
                                <li><a href="#">twitter</a></li>
                                <li><a href="#">google +</a></li>
                            </ul>
                            <ul class="tag-list">
                                <li><span>Tags:</span></li>
                                <li><a href="#">design,</a></li>
                                <li><a href="#">image,</a></li>
                                <li><a href="#">photo</a></li>
                            </ul>
                        </div>

                        <div class="prev-next-post">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="prev-post-blog">
                                        <a href="#"><span aria-hidden="true" class="arrow_carrot-left_alt2"></span></a>
                                        <img src="upload/blog/t1.jpg" alt="">
                                        <span>Previous post</span>
                                        <h2><a href="single-post.html">Aliquam tincidunt mauris eu risus.</a></h2>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="next-post-blog">
                                        <a href="#"><span aria-hidden="true" class="arrow_carrot-right_alt2"></span></a>
                                        <img src="upload/blog/t2.jpg" alt="">
                                        <span>next post</span>
                                        <h2><a href="single-post.html">Vestibulum commodo felis</a></h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                      @include('front.blog_detail_comment')
                        <!-- End contact form box -->


                    </div>
                </div>
                <div class="col-md-4">

                    <div class="sidebar">
                        @include('front.layouts.blog_sidebar')
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
@endsection
