<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'content', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    public function getFormattedContentAttribute()
    {
        $content = e($this->content);
        
        // Regex to find @ followed by word characters (handles names without spaces easily)
        // For names with spaces, this simple version might only match the first part.
        // We will try to match `@Name` where Name is a valid user name.
        return preg_replace_callback('/@([\w\.\-]+)/', function($matches) {
            $username = $matches[1];
            $user = \App\Models\User::where('name', 'like', $username . '%')->first(); // Relaxed match
            
            if ($user) {
                return '<span class="font-bold text-indigo-700 bg-indigo-100/50 px-1 rounded-sm border border-indigo-200" title="'.$user->name.'">@'.$matches[1].'</span>';
            }
            
            return '@' . $matches[1];
        }, $content);
    }
}
