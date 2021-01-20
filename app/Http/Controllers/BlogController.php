<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\PostCategory;
use App\Models\Posts;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->category = PostCategory::statusActive()->get();
        $popularPost = Posts::statusActive()->orderBy('read_count','DESC')->limit(3)->get();
        View::share('category',$this->category);
        View::share('popularPost',$popularPost);
    }


    public function index(Request $request)
    {
        $query = $request->q;
        if ($query)
        {
            $posts = $this->search($query, $request->text);
        }
        else
        {
            $posts = Posts::statusActive()
                ->where('publish_date', '<=', now())
                ->orderBy('publish_date', 'DESC')
                ->paginate(6);
        }

        return view('front.blog', compact('posts'));
    }

    public function search($query, $text)
    {
        $type = $query;

        switch ($type)
        {
            case 'user':
                $posts = Posts::where('user_id', $text)->statusActive()->paginate(6);
                break;
            case 'category':
                $findCategory = PostCategory::where('slug', 'LIKE', '%' . $text . '%')
                    ->statusActive()
                    ->first();
                $posts = Posts::category($findCategory)
//                where('category_id',$findCategory?'=':'>',($findCategory?$findCategory->id:0))
                    ->statusActive()
                    ->orderBy('publish_date', 'DESC')
                    ->paginate(6);
                break;
            default:
                $posts = Posts::statusActive()
                    ->where('publish_date', '<=', now())
                    ->defaultSearch($text)
                    ->orderBy('publish_date', 'DESC')
                    ->paginate(6);

                break;
        }


        return $posts;

    }

    public function getCategory(Request $request)
    {
        $categorySlug = $request->category;

//        $category = PostCategory::where('slug', $categorySlug)->first();
//        $posts = Posts::where('category_id', $category->id)
//            ->orderBy('publish_date', 'DESC')
//            ->paginate(6);


        $posts = Posts::join('post_category', 'post_category.id', '=', 'posts.category_id')
            ->where('post_category.slug', $categorySlug)
            ->orderBy('publish_date', 'DESC')
            ->select('posts.*')
            ->statusActive()
            ->paginate(6);



        return view('front.blog', compact('posts'));
    }

    public function post(Request $request)
    {
        $postSlug = $request->post;
//        $postCategory = $request->category;

        $post = Posts::where('slug',$postSlug)->first();

        $comment = Comment::where('post_id',$post->id)
            ->statusActive()
            ->get();


        if ($post)
        {
            $tagsID = json_decode($post->tags_id);
            $tags = $this->prepareTags($tagsID);
            return view('front.blog_detail',compact('post','tags','comment'));
        }
        alert()->info('Uyarı','Aranan içerik bulunamadı.')
            ->showConfirmButton('Tamam','#3085d6');
        return redirect()->back();
    }

    public function prepareTags($tagsID)
    {
        $explode = explode(',',$tagsID);
        $tags=[];
        foreach ($explode as $item)
        {
            $tag = Tag::find($item);
            $tags[] = $tag ?? $tags;
        }
        return $tags;
    }

    public function saveComment(Request $request)
    {
        Comment::create([
            'name' => $request->name,
            'email' => $request->mail,
            'web' => $request->web,
            'comment' => $request->comment,
            'status' => 0,
            'parent_id' => $request->parent_id,
            'post_id' => $request->post_id
        ]);

    }

}

