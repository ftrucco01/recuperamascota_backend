<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\GoogleChat\Card;
use NotificationChannels\GoogleChat\GoogleChatChannel;
use NotificationChannels\GoogleChat\GoogleChatMessage;
use NotificationChannels\GoogleChat\Section;
use NotificationChannels\GoogleChat\Widgets\KeyValue;

class GoogleChatSimpleNotification extends Notification
{
    use Queueable;

    private string $message;

    public function __construct($data)
    {
        $this->message = $data;
    }

    /**
     * @return string[]
     */
    public function via(): array
    {
        return [
            GoogleChatChannel::class
        ];
    }

    /**
     * @return GoogleChatMessage
     */
    public function toGoogleChat(): GoogleChatMessage
    {
        return GoogleChatMessage::create($this->message);
    }
}
