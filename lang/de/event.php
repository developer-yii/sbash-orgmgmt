<?php

return [

  'header' => [
    'title_a' => 'Events',
    'item' => 'Armaturenbrett',
    'title_b' => 'Events',
    'event_management' => 'Veranstaltungsmanagement',
  ],

  'modal' => [
    'title' => 'Event hinzufügen',
    'name' => 'Name des Events',

    'teaser' => 'Text für die Seite mit kommenden Events',
    'event_intro' => 'Einleitender Text für Eventseite',
    'email' => 'Text für Email',
    'location' => 'Veranstaltungsort',

    'registration_end_date' => 'Anmeldeende (Datum/Zeit)',
    'date_from' => 'Aus (Datum/Zeit)',
    'date_to' => 'Zu (Datum/Zeit)',
    'date' => 'Datum',
    'from' => 'Start',
    'to' => 'Bis',
    'from_1' => 'Von',
    'clock' => 'Uhr',
    'participants' => 'Anzahl Teilnehmer',
    'waiting' => 'Anzahl Warteliste',
    'email' => 'Bestätigungsemail Text',

    'regEndDate' => 'Registrierung möglich bis (Datum)',
    'regEndTime' => 'Registrierung möglich bis (Uhrzeit)',
    'status' => 'Ereignisstatus',
    'status_Draft' => 'Entwurf',
    'status_Published' => 'Veröffentlicht',
    'status_Started' => 'Gestartet',
    'status_Finished' => 'Beendet',
    'status_Archived' => 'Archiviert',

    'maintenance' => 'Event-Wartungsmodus (Registrierung nicht möglich solange Wartungsmodus aktiv)',
    'maintenance_mode' => 'Wartungsmodus',
    'send_cancellation_link' => 'Kündigungslink senden',

    'on' => 'Aktiv',
    'off' => 'Inaktiv',

    'upload_image' => 'Bild hochladen',

    'hidden' => 'Event-Sichtbarkeit in Übersicht kommender Events',
    'hidden_upcoming_event' => 'Versteckt in bevorstehenden Ereignissen',
    'hidden_on' => 'Nicht sichtbar (dann Registrierung z.B. per Link in Email)',
    'hidden_off' => 'Sichtbar (dann per Link oder in "Kommende Events")',

    'settings' => 'Event-Optionen während der Anmeldung',
    'settings_evtPackage' => 'Event-Päckchen anbieten (zusätzliche Postadresse für Event-Päckchen wird bei der Anmeldung abgefragt)',
    'settings_fairParticipation' => 'Messe-Teilnahme als Aussteller wird bei der Anmeldung abgefragt',
    // 'settings_reducedReg' => 'Minimale Anmeldung - es werden nur die allerwichtigsten Daten abgefragt sodass die Anmeldung schnell abgeschlossen werden kann',
    'settings_reducedReg' => 'Mindestregistrierungsdaten',

    'cancel' => 'Abbrechen',
    'duplicate' => 'Event duplizieren',

    'event_type' => 'Ereignistyp',
    'online' => 'Online',
    'local' => 'Lokal',


    'visibility' => 'Sichtweite',
    'registration' => 'Anmeldung',

    'choose_file' => 'Datei wählen',

],

  'table' => [
    'name' => 'Name',
    'date' => 'Datum',
    'from' => 'Start',
    'to' => 'Bis',
    'participants' => 'Teilnehmer',
    'waiting' => 'Warteliste',
    'action' => 'Aktion',
    'thousand_separator' => '.',
    'loading' => 'Wird geladen ...',
    'processing' => 'Wird verarbeitet ...',
    'sendEmail' => 'Email an gewählten Personenkreis',
    'participant_list_agreement' => 'Fordern Sie die Vereinbarung zur Teilnehmerliste an',

    'empty' => 'Keine Daten in der Tabelle verfügbar',
    'info' => [
      'sh' => 'Einträge',
      'to' => ' - ',
      'of' => 'von ',
      'ent' => 'anzeigen',
      'length_a' => '',
      'length_b' => 'Einträge pro Seite anzeigen',
    ],

    'filter' => [
      'pre' => 'Filterergebnis aus ',
      'post' => ' Einträgen',
    ],

    'leng' => [
      'sh' => 'Show',
      'ent' => 'Einträge',
    ],

    'sc' => 'Suche:',
    'nr' => 'Keine übereinstimmenden Elemente gefunden',
    'paginate' => [
      'next' => 'Weiter',
      'prev' => 'Zurück',
    ],

    'buttons' => [
      'copy' => 'Kopieren',
      'excel' => 'Excel-Export',
      'csv' => 'CSV Export',
      'pdf' => 'PDF Export',
    ],

  ],

  'button' => [
    'action' => 'Aktion',
    'delete' => 'Löschen',
    'export' => 'Exportieren',
    'close' => 'Abbrechen',
    'save' => 'Speichern',
    'participant' => 'Teilnehmer',
    'element' => 'Bausteine',
    'registrasi' => 'Registrierung',
    'vip_registration' => 'VIP-Registrierung',
    'reminder' => 'Erinnerung',
    'confirm' => 'Yes',
    'copy' => 'Duplizieren',
    'delete' => 'Löschen',
    'edit' => 'Bearbeiten',
    'detail' => 'Details',
    'convertToParticipant' => 'Zu Teilnehmer umwandeln',
    'cancelParticipation' => 'Teilnehmerabsage',
    'eventpage' => 'Event-Seite des Teilnehmers',
  ],

  'button_tt' => [
    'close' => 'Abbrechen',
    'save' => 'Speichern',
    'participant' => 'Teilnehmer der Veranstaltung anzeigen',
    'element' => 'Bausteine anzeigen, aus der sich diese Veranstaltung zusammensetzt',
    'registrasi' => 'Link auf die Seite, auf der sich Teilnehmer zum Event registrieren können.
Dieser Link kann kopiert und in eine Einladungsemail eingefügt werden.',
    'vip_registration' => 'Link zur VIP-Registrierungsseite',
    'copy' => 'Duplizieren einer Veranstaltung, um mit wenig Aufwand eine neue Veranstaltung anlegen zu können',
    'delete' => 'Löschen',
    'edit' => 'Bearbeiten der Veranstaltungseigenschaften',
    'detail' => 'Details',
    'eventpage' => '<p>Dieser Link führt zu der Event-Seite des Teilnehmers. Diesen Link bekommt der Teilnehmer in der Email zur Anmeldebestätigung und im Termin.</p>
                       <p>Trotzdem kann es vorkommen, dass der Teilnehmer den Link nicht mehr findet und sich meldet. Dann kann dieser Link kopiert und dem Teilnehmer erneut zugesendet werden.</p>',
  ],

  'sweet' => [
    'title' => 'Bist du sicher?',
    'text' => 'Sie können dies nicht rückgängig machen!',
    'button' => 'Ja, löschen!',
    'button_b' => 'Abbrechen',
    'success' => 'Daten erfolgreich gelöscht',
    'successfully' => 'Erfolgreich',
    'success1' => 'Erfolg',
    'abort' => 'Abbrechen',
    'confirm_cancel' => 'Stornierung bestätigen',
    'participant_cancel' => 'Teilnehmer erfolgreich storniert',
  ],

  'sweet_err' => [
    'icon' => 'Fehler',
    'title' => 'Fehler',
    'text' => 'Etwas ist schief gelaufen!',
    'footer' => 'Etwas ist schief gelaufen!',
  ],

  'detail' => [
    'page_a' => 'Details',
    'page_b' => 'Armaturenbrett',
    'page_c' => 'Element',
    'page_d' => 'Details',
    'events' => 'Events',
    'history_of_registrations' => 'Verlauf der Anmeldungen',
    'chart' => [
      'signups' => 'Anmeldungen',
    ],
  ],

  'box' => [
    'date' => 'Datum',
    'from' => 'Von',
    'to' => 'Bis',
    'participant' => 'Teilnehmer maximal',
    'waitinglist' => 'Warteliste maximal',
    'pc' => 'Angemeldete Teilnehmer',
    'wl' => 'Teilnehmer auf Warteliste',
    'desc' => 'Beschreibung',
    'event_package' => 'Veranstaltungspaket',
    'fair_participation' => 'Messe-Teilnahme als Aussteller',
    'reduce_registration' => 'Reduzierte Registrierung',
  ],

  'box_tbl' => [
    'full_name' => 'Vollständiger Name',
    'title' => 'Angemeldete Teilnehmer',
    'fname' => 'Vorname',
    'lname' => 'Nachname',
    'full_name' => 'Vollständiger Name',
    'cname' => 'Name des Unternehmens',
    'email' => 'Email',
    'phone' => 'Telefon',
    'action' => 'Aktion',
    'created_at' => 'Anmeldezeit',
    'fair_participation' => 'Faire Teilnahme',
    'title_field' => 'Anrede',
    'company_street' => 'Straße (geschäftlich)',
    'company_postal_code' => 'PLZ (geschäftlich)',
    'company_city' => 'Ort (geschäftlich)',
    'position' => 'Funktion',
    'event_participation_confirmed' => 'Teilnahme am Event',
    'registration_query_event_package' => 'Veranstaltungspaket',
    'event_package_deviating_delivery_address_provided' => 'Lieferadresse',
    'delivery_address' => 'Lieferadresse',
    'ep_delivery_address_name' => 'Abweichender Name für Lieferung',
    'ep_delivery_address_street' => 'Lieferung Straße',
    'ep_delivery_address_postal_code' => 'Lieferung Postleitzahl',
    'ep_delivery_address_city' => 'Lieferung Ort',
    'event_reminder_sent_at' => 'Ereigniserinnerung',
    'privacy_statement_accepted' => 'Datenschutzerklärung',
    'participant_status_id' => 'Status',
    'participant_attending' => 'Teilnehmer',
    'participant_waitinglist' => 'Warteliste',
    'participant_cancelled' => 'Abgesagt',
    'participant_statusunknown' => '-',
    'ref' => 'Bezug',
    'is_sent' => 'Erinnerung gesendet',
    'sent_at' => 'Erinnerung gesendet am',
    'updated_at' => 'Aktualisiert',
    'academic_degree' => 'Akademischer Titel',
    'alternative_street' => 'Alternative Straße',
    'alternative_zip' => 'Alternative PLZ',
    'alternative_city' => 'Alternativer Ort',
  ],

  'tbl_dtl' => [
    'fname' => 'Vorname',
    'lname' => 'Nachname',
    'cname' => 'Unternehmen',
    'street' => 'Straße',
    'postal' => 'Postleitzahl',
    'ccity' => 'Ort',
    'position' => 'Position',
    'email' => 'Email',
    'phone' => 'Telefon',
    'pc' => 'Teilnahme bestätigt',
    'package' => 'Eventpaket angefragt',
    'diff' => 'Abweichende Adresse für Eventpaket',
    'pp' => 'Datenschutzerklärung bestätigt',
    'status' => 'Teilnehmerstatus',
  ],

  'status' => [
    'Draft' => 'Entwurf',
    'Published' => 'Veröffentlicht',
    'Started' => 'Gestartet',
    'Finished' => 'Beendet',
    'Archived' => 'Archiviert',
    'Current' => 'Aktuell',
  ],

  'action' => [
    'Publish' => 'Veröffentlichen',
    'Start' => 'Starten',
    'Finish' => 'Beenden',
    'Archive' => 'Archivieren',
  ],

  'register' => [
    'show' => 'Anzeigen',
    'sendMail' => 'Email senden'
  ],

  'message' => [
    'fails' => 'Daten konnten nicht gespeichert werden',
    'anmeldefehler' => 'Anmeldefehler',
    'full_event' => 'Das Event ist leider ausgebucht',
    'login_success' => 'Anmeldung erfolgreich',
    'convert_success' => 'Erfolgreich zum Teilnehmer konvertiert',
    'convert_error' => 'Sie können nicht in Teilnehmer umwandeln, da die maximale Teilnehmerzahl erreicht ist.',
    'event_not_started' => 'Das Event hat noch nicht begonnen.',
    'event_start_notification' => 'Sobald das Event startet, können Sie hier die Anmeldeinformationen sehen.',
    'confirm_placeholder' => 'Die Bestätigungs-E-Mail muss den Platzhalter &lt&ltLink&gt&gt enthalten',
  ],

  'persons' => [
    'prefix' => 'Personenkreis:',
    'all' => 'Alle Teilnehmer',
    'wl' => 'Persons on waiting list',
    'fp' => 'Nur Messe-Aussteller',
    'nfp' => 'Alle Teilnehmer außer Messe-Aussteller',
    'nfcp' => 'Alle Teilnehmer außer Messe- & abgesagte Teilnehmer',
    'wl' => 'Personen auf der Warteliste',
    'cp' => 'Aktuelle Teilnehmer',
  ],

  'send_email_modal' => [
    'title' => 'E-Mail-Inhalt eingeben',
    'save_button' => 'Senden',
    'cancel_button' => 'Abbrechen',
    'subject' => 'Thema',
    'content' => 'Inhalt',
   ],

  'email' => [
    'cancel_registration' => 'Wenn sie nicht teilnehmen können, nutzen Sie bitte diesen Link: ',
    'click_here' => 'Teilnahme absagen',
  ],

  'export' => [
    'yes' => 'Ja',
    'no' => 'Nein',
  ],

  'participant' => [
    'takes_part' => 'Nimmt teil',
    'on_waiting_list' => 'Auf Warteliste',
    'does_not_participate' => 'Nimmt nicht teil',
  ],

  'heading' => [
    'copy_event' => 'Event kopieren',
    'copy_of' => 'Kopie von',
  ],

  'registration' => [
    'button' => [
      'register' => 'Anmelden',
      'wait_text' => 'Alle Plätze sind bereits ausgebucht. Sie können sich jedoch auf der Warteliste eintragen. Wir informieren Sie, falls Personen absagen und ein Platz frei wird.',
      'wait_label' => 'Auf Warteliste eintragen',
      'regret_text_1' => '<b>Alle Plätze sind bereits gebucht. </b>Bitte versuche  es bei der nächsten Veranstaltung gerne noch einmal.',
      'regret_text_2' => 'Anmeldung nicht mehr möglich',
    ],
    'notification' => [
      'regret_msg_1' => 'Die Veranstaltung ist leider bereits ausgebucht, aber sie können sich hier auf der Warteliste eintragen.',
      'regret_msg_2' => 'Die Veranstaltung ist leider bereits ausgebucht.',
    ],
    'label' => [
      'title' => 'Titel',
      'necessary' => 'erforderlich',
      'participant_list' => 'Liste der Anmeldungen',
    ],
    'alert' => [
      'fail' => 'Fehler!',      
    ],
  ],

];
