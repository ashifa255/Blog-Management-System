// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{post}', [PostController::class, 'show']);
    Route::get('posts/{post}/comments', [CommentController::class, 'index']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Post routes
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{post}', [PostController::class, 'update'])->middleware('can:update,post');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->middleware('can:delete,post');
    
    // Comment routes
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update'])->middleware('can:update,comment');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->middleware('can:delete,comment');
    
    // Interaction routes
    Route::post('posts/{post}/like', [InteractionController::class, 'like']);
    Route::post('posts/{post}/unlike', [InteractionController::class, 'unlike']);
    Route::post('posts/{post}/bookmark', [InteractionController::class, 'bookmark']);
    Route::post('posts/{post}/remove-bookmark', [InteractionController::class, 'removeBookmark']);
    
    // User specific routes
    Route::get('user/posts', [PostController::class, 'userPosts']);
    Route::get('user/bookmarks', [PostController::class, 'bookmarkedPosts']);
    Route::put('profile', [AuthController::class, 'updateProfile']);
});
