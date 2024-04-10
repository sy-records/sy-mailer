<?php

namespace SyMailer;

class Config
{
    public static function lists()
    {
        return [
            '1und1' => [
                'host' => 'smtp.1und1.de',
                'port' => 465,
                'secure' => true,
            ],
            __('Aliyun', 'sy-mailer') => [
                'host' => 'smtp.aliyun.com',
                'port' => 465,
                'secure' => true,
            ],
            __('Aliyun Qiye', 'sy-mailer') => [
                'host' => 'smtp.qiye.aliyun.com',
                'port' => '465',
                'secure' => true,
            ],
            'AOL' => [
                'host' => 'smtp.aol.com',
                'port' => 587,
            ],
            'Bluewin' => [
                'host' => 'smtpauths.bluewin.ch',
                'port' => 465,
            ],
            'DebugMail' => [
                'host' => 'debugmail.io',
                'port' => 25,
            ],
            'DynectEmail' => [
                'host' => 'smtp.dynect.net',
                'port' => 25,
            ],
            'Ethereal' => [
                'host' => 'smtp.ethereal.email',
                'port' => 587,
            ],
            'FastMail' => [
                'host' => 'smtp.fastmail.com',
                'port' => 465,
                'secure' => true,
            ],
            'Forward Email' => [
                'host' => 'smtp.forwardemail.net',
                'port' => 465,
                'secure' => true,
            ],
            'GandiMail' => [
                'host' => 'mail.gandi.net',
                'port' => 587,
            ],
            __('Gmail', 'sy-mailer') => [
                'host' => 'smtp.gmail.com',
                'port' => 465,
                'secure' => true,
            ],
            'Godaddy' => [
                'host' => 'smtpout.secureserver.net',
                'port' => 25,
            ],
            'GodaddyAsia' => [
                'host' => 'smtp.asia.secureserver.net',
                'port' => 25,
            ],
            'GodaddyEurope' => [
                'host' => 'smtp.europe.secureserver.net',
                'port' => 25,
            ],
            'Hotmail' => [
                'host' => 'smtp-mail.outlook.com',
                'port' => 587,
            ],
            'iCloud' => [
                'host' => 'smtp.mail.me.com',
                'port' => 587,
            ],
            'Infomaniak' => [
                'host' => 'mail.infomaniak.com',
                'port' => 587,
            ],
            'Mail.ru' => [
                'host' => 'smtp.mail.ru',
                'port' => 465,
                'secure' => true,
            ],
            'Mailcatch.app' => [
                'host' => 'sandbox-smtp.mailcatch.app',
                'port' => 2525,
            ],
            'Mailgun' => [
                'host' => 'smtp.mailgun.org',
                'port' => 465,
                'secure' => true,
            ],
            'Mailjet' => [
                'host' => 'in.mailjet.com',
                'port' => 587,
            ],
            'Mailosaur' => [
                'host' => 'mailosaur.io',
                'port' => 25,
            ],
            'Mailtrap' => [
                'host' => 'smtp.mailtrap.io',
                'port' => 2525,
            ],
            'Mandrill' => [
                'host' => 'smtp.mandrillapp.com',
                'port' => 587,
            ],
            'Naver' => [
                'host' => 'smtp.naver.com',
                'port' => 587,
            ],
            'One' => [
                'host' => 'send.one.com',
                'port' => 465,
                'secure' => true,
            ],
            'OpenMailBox' => [
                'host' => 'smtp.openmailbox.org',
                'port' => 465,
                'secure' => true,
            ],
            'Outlook365' => [
                'host' => 'smtp.office365.com',
                'port' => 587,
                'secure' => false,
            ],
            'OhMySMTP' => [
                'host' => 'smtp.ohmysmtp.com',
                'port' => 587,
                'secure' => false,
            ],
            'Postmark' => [
                'host' => 'smtp.postmarkapp.com',
                'port' => 2525,
            ],
            __('QQ', 'sy-mailer') => [
                'host' => 'smtp.qq.com',
                'port' => 465,
                'secure' => true,
            ],
            __('QQex', 'sy-mailer') => [
                'host' => 'smtp.exmail.qq.com',
                'port' => 465,
                'secure' => true,
            ],
            'SendCloud' => [
                'host' => 'smtp.sendcloud.net',
                'port' => 2525,
            ],
            'SendGrid' => [
                'host' => 'smtp.sendgrid.net',
                'port' => 587,
            ],
            'SendinBlue' => [
                'host' => 'smtp-relay.brevo.com',
                'port' => 587,
            ],
            'SendPulse' => [
                'host' => 'smtp-pulse.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES' => [
                'host' => 'email-smtp.us-east-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-US-EAST-1' => [
                'host' => 'email-smtp.us-east-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-US-WEST-2' => [
                'host' => 'email-smtp.us-west-2.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-EU-WEST-1' => [
                'host' => 'email-smtp.eu-west-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-AP-SOUTH-1' => [
                'host' => 'email-smtp.ap-south-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-AP-NORTHEAST-1' => [
                'host' => 'email-smtp.ap-northeast-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-AP-NORTHEAST-2' => [
                'host' => 'email-smtp.ap-northeast-2.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-AP-NORTHEAST-3' => [
                'host' => 'email-smtp.ap-northeast-3.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-AP-SOUTHEAST-1' => [
                'host' => 'email-smtp.ap-southeast-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'SES-AP-SOUTHEAST-2' => [
                'host' => 'email-smtp.ap-southeast-2.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            'Sparkpost' => [
                'host' => 'smtp.sparkpostmail.com',
                'port' => 587,
                'secure' => false,
            ],
            'Tipimail' => [
                'host' => 'smtp.tipimail.com',
                'port' => 587,
            ],
            'Yahoo' => [
                'host' => 'smtp.mail.yahoo.com',
                'port' => 465,
                'secure' => true,
            ],
            'Yandex' => [
                'host' => 'smtp.yandex.ru',
                'port' => 465,
                'secure' => true,
            ],
            'Zoho' => [
                'host' => 'smtp.zoho.com',
                'port' => 465,
                'secure' => true,
            ],
            __('126', 'sy-mailer') => [
                'host' => 'smtp.126.com',
                'port' => 465,
                'secure' => true,
            ],
            __('163', 'sy-mailer') => [
                'host' => 'smtp.163.com',
                'port' => 465,
                'secure' => true,
            ],
        ];
    }
}
