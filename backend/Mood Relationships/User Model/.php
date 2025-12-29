// app/Models/User.php
class User extends Authenticatable
{
    use HasApiTokens;
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function comments()
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
}
