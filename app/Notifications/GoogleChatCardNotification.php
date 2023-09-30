<?php

namespace App\Notifications;

use App\Classes\GoogleChat\GoogleChatSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\GoogleChat\Card;
use NotificationChannels\GoogleChat\Components\Button\TextButton;
use NotificationChannels\GoogleChat\GoogleChatChannel;
use NotificationChannels\GoogleChat\GoogleChatMessage;
use NotificationChannels\GoogleChat\Section;
use NotificationChannels\GoogleChat\Widgets\Buttons;
use NotificationChannels\GoogleChat\Widgets\Image;
use NotificationChannels\GoogleChat\Widgets\TextParagraph;

class GoogleChatCardNotification extends Notification
{
    use Queueable;

    private GoogleChatSettings $googleChatProperties;

    public function __construct($googleChatProperties)
    {
        $this->googleChatProperties = $googleChatProperties->googleChatSettings;
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
        $buttons = [];

        if (array_key_exists('buttons', (array) $this->googleChatProperties)) {
            foreach ($this->googleChatProperties->buttons as $btn) {
                $buttons[] = TextButton::create(
                    $btn['url'],
                    $btn['name']
                );
            }
        }

        $urlImage = 'https://api.prodooh.com/img/barra.jpg';
        $link = null;

        if (array_key_exists('image', (array) $this->googleChatProperties)) {
            $urlImage = $this->googleChatProperties->image['url'];
            $link = $this->googleChatProperties->image['link'];
        }

        return GoogleChatMessage::create()
            ->card(
                Card::create()
                    ->header(
                        $this->googleChatProperties->title,
                        $this->googleChatProperties->subtitle,
                        $this->googleChatProperties->urlImg,
                        $this->googleChatProperties->styleImage
                    )
                    ->section(
                        Section::create(
                            [
                                TextParagraph::create($this->googleChatProperties->message),
                                Buttons::create($buttons),
                                Image::create($urlImage, $link)
                            ]
                        )
                    )
            )
            ->to($this->googleChatProperties->channel);
    }
}
