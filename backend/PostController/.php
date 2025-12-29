// app/Http/Controllers/PostController.php
class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::public()
            ->with(['user', 'tags', 'likes', 'comments'])
            ->latest()
            ->paginate(10);
        
        return PostResource::collection($posts);
    }
    
    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
        
        $post = Post::create($data);
        
        // Sync tags
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName], [
                    'slug' => Str::slug($tagName)
                ]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }
        
        return new PostResource($post->load('tags'));
    }
}
