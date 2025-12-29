// app/Models/Post.php
class Post extends Model
{
    protected $fillable = ['title', 'content', 'image', 'visibility', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
    
    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
    
    // Scope for public posts
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }
}
