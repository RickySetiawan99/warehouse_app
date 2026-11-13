<?php

namespace App\Exceptions;

/** Token API ditolak server (401): sesi gudang dihapus dan staf diminta masuk ulang. */
class ApiSessionExpired extends \RuntimeException {}
