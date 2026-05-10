<?php

// Exclui toda a sessão caso ocorra algum erro no processo de validação do token, garantindo que o usuário seja deslogado
session_start();
$_SESSION = [];
session_destroy();