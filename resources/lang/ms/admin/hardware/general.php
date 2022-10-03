<?php

return [
    'about_assets_title'           => 'Mengenai Aset',
    'about_assets_text'            => 'Aset adalah item yang dikesan oleh nombor siri atau tag aset. Mereka cenderung menjadi item nilai yang lebih tinggi di mana mengenal pasti perkara-perkara tertentu.',
    'archived'  				=> 'Diarkibkan',
    'asset'  					=> 'Harta',
    'bulk_checkout'             => 'Daftar Keluar Aset',
    'bulk_checkin'              => 'Checkin Assets',
    'checkin'  					=> 'Terima Harta',
    'checkout'  				=> 'Aset Checkout',
    'clone'  					=> 'Pendua Harta',
    'deployable'  				=> 'Deployable',
    'deleted'  					=> 'Aset ini telah dipadamkan.',
    'edit'  					=> 'Kemaskini Harta',
    'model_deleted'  			=> 'Model Aset ini telah dipadamkan. Anda mesti kembalikan model sebelum anda boleh kembalikan Aset.',
    'requestable'               => 'Diminta',
    'requested'				    => 'Diminta',
    'not_requestable'           => 'Not Requestable',
    'requestable_status_warning' => 'Do not change  requestable status',
    'restore'  					=> 'Pulihkan Asset',
    'pending'  					=> 'Menunggu',
    'undeployable'  			=> 'Tidak dapat dipisahkan',
    'view'  					=> 'Papar Harta',
    'csv_error' => 'You have an error in your CSV file:',
    'import_text' => '
    <p>
    Upload a CSV that contains asset history. The assets and users MUST already exist in the system, or they will be skipped. Matching assets for history import happens against the asset tag. We will try to find a matching user based on the user\'s name you provide, and the criteria you select below. If you do not select any criteria below, it will simply try to match on the username format you configured in the Admin &gt; General Settings.
    </p>

    <p>Fields included in the CSV must match the headers: <strong>Asset Tag, Name, Checkout Date, Checkin Date</strong>. Any additional fields will be ignored. </p>

    <p>Checkin Date: blank or future checkin dates will checkout items to associated user.  Excluding the Checkin Date column will create a checkin date with todays date.</p>
    ',
    'csv_import_match_f-l' => 'Try to match users by firstname.lastname (jane.smith) format',
    'csv_import_match_initial_last' => 'Try to match users by first initial last name (jsmith) format',
    'csv_import_match_first' => 'Try to match users by first name (jane) format',
    'csv_import_match_email' => 'Try to match users by email as username',
    'csv_import_match_username' => 'Try to match users by username',
    'error_messages' => 'Error messages:',
    'success_messages' => 'Success messages:',
    'alert_details' => 'Please see below for details.',
    'custom_export' => 'Custom Export'
];
