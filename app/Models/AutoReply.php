<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'keywords',
        'response',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Tìm auto reply phù hợp với tin nhắn
     */
    public static function findMatch(string $message): ?self
    {
        $message = mb_strtolower(trim($message));
        
        $autoReplies = self::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($autoReplies as $autoReply) {
            $keywords = array_map('trim', explode(',', mb_strtolower($autoReply->keywords)));
            
            foreach ($keywords as $keyword) {
                if (!empty($keyword) && mb_strpos($message, $keyword) !== false) {
                    return $autoReply;
                }
            }
        }

        return null;
    }
}

