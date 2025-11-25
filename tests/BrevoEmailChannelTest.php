<?php

declare(strict_types=1);

use Illuminate\Notifications\Notification;
use YieldStudio\LaravelBrevoNotifier\BrevoEmailChannel;
use YieldStudio\LaravelBrevoNotifier\BrevoEmailMessage;
use YieldStudio\LaravelBrevoNotifier\BrevoService;
use YieldStudio\LaravelBrevoNotifier\Tests\User;

it('send notification via BrevoEmailChannel should call BrevoService sendEmail method', function () {
    $httpResponse = [
        'messageId' => '<201906041124.44340027797@smtp-relay.mailin.fr>',
    ];

    $mock = $this->mock(BrevoService::class)
        ->shouldReceive('sendEmail')
        ->once()
        ->andReturn($httpResponse);

    $channel = new BrevoEmailChannel($mock->getMock());

    $response = $channel->send(new User, new class extends Notification
    {
        public function via()
        {
            return [BrevoEmailChannel::class];
        }

        public function toBrevoEmail(User $notifiable): BrevoEmailMessage
        {
            return new BrevoEmailMessage;
        }
    });

    expect($response)->toEqual($httpResponse);
});
