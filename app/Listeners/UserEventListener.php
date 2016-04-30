<?php

namespace App\Listeners;

use App\Events\UsereEventEvent;
use App\UsereEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Snowfire\Beautymail\Beautymail;

class UsereEventEventListener
{
    public $event;

    protected $mailer;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Beautymail $mailer)
    {
        $this->mailer = $mailer;
    }

    public function signedIn(UsereEvent $event) {
        //
    }

    public function signedOut(UsereEvent $event) {
        //
    }

    public function destroyed(UsereEvent $user) {
        $user = $event->user;
        $this->mailer->send('mails.user.goodbye', [], function($message) use($user) {
            $message
                ->from('noReply@'.config('app.url'))
                ->to($user->email)
                ->subject('Cheerio');
        });
    }

    public function signedUp(UsereEvent $event) {
        $user = $event->user;
        $this->mailer->send('mails.user.welcome', ['user' => $user], function($message) use($user) {
            $message
                ->from('noReply@'.config('app.url'))
                ->to($user->email)
                ->subject('Welcome!');
        });
    }

    public function ipChanged(ChangedIPNotification $event)
    {
        $user = Auth::user();
        $data = [
            'current_ip'    =>  $event->location->current_ip,
            'last_ip'       =>  $event->location->last_ip,
            'subject'       =>  'It has come to our attention, that your account shows abnormal activity.'
        ];

        $username = $user->name;

        Slack::to('#notifications')->send(
            "[Suspicious activity]: noticed an abnormal change in IP addresses for Account: $username."
        );

        $this->mailer->send('mails.location.changed', $data, function($message) use($user, $data) {
            $message
                ->from('noReply@'.config('app.url'))
                ->to($user->email)
                ->subject($data['subject']);
        });   
    }

    public function promoted(Granted $event) {
        $message = "Congrats! ". $event->message;

        Slack::to('#notifications')->send($message);

        // Additional, eg. Email confirming promotion.
    }

    public function demoted(Revoked $event) {   
        $message = "Aww, bad luck! ". $event->message;

        Slack::to('#notifications')->send($message);

        // Additional, eg. Email confirming promotion.   
    }
}
