<?php

namespace App\Enums;

enum InvestigationStatus: string {
    case Created = 'created';
    case PendingConfirmation = 'pending_confirmation';
    case Confirmed = 'confirmed';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
}