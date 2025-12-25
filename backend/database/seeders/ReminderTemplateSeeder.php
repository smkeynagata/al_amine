<?php

namespace Database\Seeders;

use App\Models\ReminderTemplate;
use Illuminate\Database\Seeder;

class ReminderTemplateSeeder extends Seeder
{
    public function run(): void
    {
        ReminderTemplate::updateOrCreate(
            ['type' => ReminderTemplate::TYPE_FACTURE_IMPAYEE],
            [
                'label' => 'Relance facture impayée',
                'channel_email' => true,
                'channel_sms' => false,
                'email_subject' => '',
                'email_body' => '',
                'sms_body' => '',
            ]
        );

        ReminderTemplate::updateOrCreate(
            ['type' => ReminderTemplate::TYPE_RDV_CONFIRMATION],
            [
                'label' => 'Rappel rendez-vous',
                'channel_email' => true,
                'channel_sms' => false,
                'email_subject' => '',
                'email_body' => '',
                'sms_body' => '',
            ]
        );
    }
}
