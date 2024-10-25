<?php

namespace SyMailer;

class Config
{
    public static function lists()
    {
        return [
            __('1und1', 'sy-mailer') => [
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
            __('AOL', 'sy-mailer') => [
                'host' => 'smtp.aol.com',
                'port' => 587,
            ],
            __('Bluewin', 'sy-mailer') => [
                'host' => 'smtpauths.bluewin.ch',
                'port' => 465,
            ],
            __('DebugMail', 'sy-mailer') => [
                'host' => 'debugmail.io',
                'port' => 25,
            ],
            __('DynectEmail', 'sy-mailer') => [
                'host' => 'smtp.dynect.net',
                'port' => 25,
            ],
            __('Ethereal', 'sy-mailer') => [
                'host' => 'smtp.ethereal.email',
                'port' => 587,
            ],
            __('FastMail', 'sy-mailer') => [
                'host' => 'smtp.fastmail.com',
                'port' => 465,
                'secure' => true,
            ],
            __('Forward Email', 'sy-mailer') => [
                'host' => 'smtp.forwardemail.net',
                'port' => 465,
                'secure' => true,
            ],
            __('GandiMail', 'sy-mailer') => [
                'host' => 'mail.gandi.net',
                'port' => 587,
            ],
            __('Gmail', 'sy-mailer') => [
                'host' => 'smtp.gmail.com',
                'port' => 465,
                'secure' => true,
            ],
            __('Godaddy', 'sy-mailer') => [
                'host' => 'smtpout.secureserver.net',
                'port' => 25,
            ],
            __('GodaddyAsia', 'sy-mailer') => [
                'host' => 'smtp.asia.secureserver.net',
                'port' => 25,
            ],
            __('GodaddyEurope', 'sy-mailer') => [
                'host' => 'smtp.europe.secureserver.net',
                'port' => 25,
            ],
            __('iCloud', 'sy-mailer') => [
                'host' => 'smtp.mail.me.com',
                'port' => 587,
            ],
            __('Infomaniak', 'sy-mailer') => [
                'host' => 'mail.infomaniak.com',
                'port' => 587,
            ],
            __('Mail.ru', 'sy-mailer') => [
                'host' => 'smtp.mail.ru',
                'port' => 465,
                'secure' => true,
            ],
            __('Mailcatch.app', 'sy-mailer') => [
                'host' => 'sandbox-smtp.mailcatch.app',
                'port' => 2525,
            ],
            __('Mailgun', 'sy-mailer') => [
                'host' => 'smtp.mailgun.org',
                'port' => 465,
                'secure' => true,
            ],
            __('Mailjet', 'sy-mailer') => [
                'host' => 'in.mailjet.com',
                'port' => 587,
            ],
            __('Mailosaur', 'sy-mailer') => [
                'host' => 'mailosaur.io',
                'port' => 25,
            ],
            __('Mailtrap', 'sy-mailer') => [
                'host' => 'smtp.mailtrap.io',
                'port' => 2525,
            ],
            __('Mandrill', 'sy-mailer') => [
                'host' => 'smtp.mandrillapp.com',
                'port' => 587,
            ],
            __('Naver', 'sy-mailer') => [
                'host' => 'smtp.naver.com',
                'port' => 587,
            ],
            __('One', 'sy-mailer') => [
                'host' => 'send.one.com',
                'port' => 465,
                'secure' => true,
            ],
            __('OpenMailBox', 'sy-mailer') => [
                'host' => 'smtp.openmailbox.org',
                'port' => 465,
                'secure' => true,
            ],
            __('Outlook365', 'sy-mailer') => [
                'host' => 'smtp.office365.com',
                'port' => 587,
                'secure' => false,
            ],
            __('OhMySMTP', 'sy-mailer') => [
                'host' => 'smtp.ohmysmtp.com',
                'port' => 587,
                'secure' => false,
            ],
            __('Postmark', 'sy-mailer') => [
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
            __('SendCloud', 'sy-mailer') => [
                'host' => 'smtp.sendcloud.net',
                'port' => 2525,
            ],
            __('SendGrid', 'sy-mailer') => [
                'host' => 'smtp.sendgrid.net',
                'port' => 587,
            ],
            __('SendinBlue', 'sy-mailer') => [
                'host' => 'smtp-relay.brevo.com',
                'port' => 587,
            ],
            __('SendPulse', 'sy-mailer') => [
                'host' => 'smtp-pulse.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES', 'sy-mailer') => [
                'host' => 'email-smtp.us-east-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-US-EAST-1', 'sy-mailer') => [
                'host' => 'email-smtp.us-east-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-US-WEST-2', 'sy-mailer') => [
                'host' => 'email-smtp.us-west-2.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-EU-WEST-1', 'sy-mailer') => [
                'host' => 'email-smtp.eu-west-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-AP-SOUTH-1', 'sy-mailer') => [
                'host' => 'email-smtp.ap-south-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-AP-NORTHEAST-1', 'sy-mailer') => [
                'host' => 'email-smtp.ap-northeast-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-AP-NORTHEAST-2', 'sy-mailer') => [
                'host' => 'email-smtp.ap-northeast-2.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-AP-NORTHEAST-3', 'sy-mailer') => [
                'host' => 'email-smtp.ap-northeast-3.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-AP-SOUTHEAST-1', 'sy-mailer') => [
                'host' => 'email-smtp.ap-southeast-1.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('SES-AP-SOUTHEAST-2', 'sy-mailer') => [
                'host' => 'email-smtp.ap-southeast-2.amazonaws.com',
                'port' => 465,
                'secure' => true,
            ],
            __('Sparkpost', 'sy-mailer') => [
                'host' => 'smtp.sparkpostmail.com',
                'port' => 587,
                'secure' => false,
            ],
            __('Tipimail', 'sy-mailer') => [
                'host' => 'smtp.tipimail.com',
                'port' => 587,
            ],
            __('Yahoo', 'sy-mailer') => [
                'host' => 'smtp.mail.yahoo.com',
                'port' => 465,
                'secure' => true,
            ],
            __('Yandex', 'sy-mailer') => [
                'host' => 'smtp.yandex.ru',
                'port' => 465,
                'secure' => true,
            ],
            __('Zoho', 'sy-mailer') => [
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
