// Laravel controller method
if ($request->hasFile('profile_picture')) {
    $path = $request->file('profile_picture')->store('profiles', 'public');
    $user->profile_picture = $path;
    $user->save();
}
