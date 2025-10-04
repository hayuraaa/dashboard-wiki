<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_source',
        'recipient_email',
        'cc_emails',
        'is_active',
        'send_auto_reply',
        'auto_reply_message',
    ];

    protected $casts = [
        'cc_emails' => 'array',
        'is_active' => 'boolean',
        'send_auto_reply' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getByWebsite($website)
    {
        return self::where('website_source', $website)->first();
    }

    public function isEmailActive()
    {
        return $this->is_active;
    }

    public function getAllRecipients()
    {
        $emails = [$this->recipient_email];
        
        if ($this->cc_emails && is_array($this->cc_emails)) {
            $emails = array_merge($emails, $this->cc_emails);
        }
        
        return array_filter($emails);
    }
}