<?php

// afficher les erreurs
ini_set('display_errors', 'stdout');

// initialiser fuseau horaire (obligatoire depuis php 5.5.10)
ini_set('date.timezone', 'Europe/Paris');

// forcer encodage
mb_internal_encoding("utf-8");

// définir locale
setlocale(LC_TIME, 'fr_FR.utf8');

?>