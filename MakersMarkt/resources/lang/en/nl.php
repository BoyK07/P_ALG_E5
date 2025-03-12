<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authenticatie taalregels
    |--------------------------------------------------------------------------
    |
    | De volgende taalregels worden gebruikt tijdens authenticatie voor diverse
    | berichten die we aan de gebruiker moeten tonen. Je bent vrij om deze
    | taalregels aan te passen aan de eisen van je applicatie.
    |
    */

    'failed' => 'Geen account gevonden met deze gegevens.',
    'password' => 'Het opgegeven wachtwoord is onjuist.',
    'throttle' => 'Te veel inlogpogingen. Probeer het opnieuw over :seconds seconden.',
    'registration_throttle' => 'Te veel registratiepogingen. Probeer het later opnieuw.',
    'csrf_error' => 'Beveiligingsvalidatie mislukt. Probeer het opnieuw.',
    'username_regex' => 'Gebruikersnaam mag alleen kleine letters, cijfers, onderstrepen en streepjes bevatten.',
    'password_requirements' => [
        'min' => 'Wachtwoord moet minimaal 8 tekens lang zijn.',
        'mixed_case' => 'Wachtwoord moet zowel hoofdletters als kleine letters bevatten.',
        'letters' => 'Wachtwoord moet minimaal één letter bevatten.',
        'numbers' => 'Wachtwoord moet minimaal één cijfer bevatten.',
        'symbols' => 'Wachtwoord moet minimaal één speciaal teken bevatten.',
        'uncompromised' => 'Dit wachtwoord is aangetroffen in een datalek. Kies een ander wachtwoord.',
    ],
    'role_validation' => [
        'required' => 'Selecteer minimaal één accounttype.',
        'min' => 'Selecteer minimaal één accounttype.',
        'invalid' => 'Ongeldige rolselectie.',
    ],
];
