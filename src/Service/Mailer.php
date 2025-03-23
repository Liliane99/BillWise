<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class Mailer
{
    public function __construct(private ParameterBagInterface $parameterBag){}

    /**
     * @throws Exception
     */
    public function sendTemplateWithAttachment(int $templateId, array $to, array $params, string $pdfContent, string $pdfName, $senderEmail, $senderName, $replyEmail, $replyName): string {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->parameterBag->get('sendinblue_api_key'));
        $apiInstance = new TransactionalEmailsApi(new Client(), $config);
        $sender = [
            'email' => $senderEmail,
            'name' => $senderName
        ];
        $reply = [
            'email' => $replyEmail,
            'name' => $replyName
        ];
        $sendSmtpEmail = new SendSmtpEmail([
            'sender' => $sender,
            'to' => $to,
            'templateId' => $templateId,
            'params' => $params,
            'attachment' => [
                [
                    'content' => base64_encode($pdfContent),
                    'name' => $pdfName,
                    'type' => 'application/pdf'
                ]
            ],
            'replyTo' => $reply
        ]);
        
    
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            return $result;
        } catch (Exception $e) {
            throw new Exception('Exception when calling TransactionalEmailsApi->sendTransacEmail: ' . $e->getMessage());
        }
    }
    public function sendPaymentReminder(int $templateId, array $to, array $params, $senderEmail, $senderName, $replyEmail, $replyName): string {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->parameterBag->get('sendinblue_api_key'));
        $apiInstance = new TransactionalEmailsApi(new Client(), $config);
        $sender = [
            'email' => $senderEmail,
            'name' => $senderName
        ];
        $reply = [
            'email' => $replyEmail,
            'name' => $replyName
        ];
        $sendSmtpEmail = new SendSmtpEmail([
            'sender' => $sender,
            'to' => $to,
            'templateId' => $templateId,
            'params' => $params,
            'replyTo' => $reply
        ]);
    
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            return $result;
        } catch (Exception $e) {
            throw new Exception('Exception when calling TransactionalEmailsApi->sendTransacEmail: ' . $e->getMessage());
        }
    }
}