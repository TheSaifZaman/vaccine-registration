<?php

namespace App\Jobs;

use App\Enum\LogLabelEnum;
use App\Enum\LogTypeEnum;
use App\Helpers\LogHelper;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected array $emailConfigSettings;
    /**
     * @var string|mixed
     */
    protected string $subject;
    /**
     * @var string|mixed
     */
    protected string $body;
    /**
     * @var array|mixed
     */
    protected array $receiverAddress;
    /**
     * @var string|mixed
     */
    protected string $senderAddress;
    /**
     * @var mixed
     */
    protected string $senderName;
    /**
     * @var array|mixed
     */
    protected array $receiverCcAddress;
    /**
     * @var array|mixed
     */
    protected array $receiverBccAddress;

    /**
     * @param array $emailConfigSettings
     * @param array $processedEmailData
     */
    public function __construct(
        array $emailConfigSettings,
        array $processedEmailData
    )
    {
        $this->emailConfigSettings = $emailConfigSettings;
        $this->subject = $processedEmailData['subject'];
        $this->body = $processedEmailData['body'];
        $this->receiverAddress = $processedEmailData['recipients'];
        $this->senderAddress = $emailConfigSettings['sender_address'];
        $this->senderName = $emailConfigSettings['sender_name'] ?? config('app.name');
        $this->receiverCcAddress = $processedEmailData['recipientsCc'] ?? [];
        $this->receiverBccAddress = $processedEmailData['recipientsBcc'] ?? [];
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            $this->configureMailer($this->emailConfigSettings);
            Mail::send([], [], function (Message $message) {
                $message->to($this->receiverAddress);
                if (!empty($this->receiverCcAddress)) {
                    $message->cc($this->receiverCcAddress);
                }
                if (!empty($this->receiverBccAddress)) {
                    $message->bcc($this->receiverBccAddress);
                }
                $message->subject($this->subject);
                $message->from($this->senderAddress, $this->senderName);
                $message->html($this->body, 'text/html');
            });
        } catch (Exception $e) {
            LogHelper::factory()
                ->setExceptionOrMessage($e)
                ->setLabel(LogLabelEnum::UPDATE->value)
                ->setLogType(LogTypeEnum::Error)
                ->log();
        }
    }

    /**
     * @param array $emailConfigSettings
     * @return void
     * @throws BindingResolutionException
     */
    private function configureMailer(array $emailConfigSettings): void
    {
        app()->make('config')->offsetUnset('mail.mailers.' . $emailConfigSettings['mailer']);
        config()->set('mail.mailers.' . $emailConfigSettings['mailer'], $this->processMailConfig($emailConfigSettings));
        Mail::setDefaultDriver($emailConfigSettings['mailer']);
    }

    /**
     * @param array $emailConfigSettings
     * @return array
     */
    private function processMailConfig(array $emailConfigSettings): array
    {
        return [
            'transport' => $emailConfigSettings['mailer'],
            'host' => $emailConfigSettings['hostname'],
            'port' => $emailConfigSettings['port'],
            'encryption' => $emailConfigSettings['encryption_mode'],
            'username' => $emailConfigSettings['username'],
            'password' => $emailConfigSettings['password'],
            'timeout' => $emailConfigSettings['timeout'] ?? null,
            'local_domain' => $emailConfigSettings['local_domain'] ?? env('MAIL_EHLO_DOMAIN'),
        ];
    }
}
