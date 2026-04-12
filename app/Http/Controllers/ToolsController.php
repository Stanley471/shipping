<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolsController extends Controller
{
    /**
     * Display coming soon page for passport generation
     */
    public function passport(): View
    {
        return $this->comingSoon('Generate Passport');
    }

    /**
     * Display coming soon page for ID card generation
     */
    public function idCard(): View
    {
        return $this->comingSoon('Generate ID Card');
    }

    /**
     * Display coming soon page for receipt generation
     */
    public function receipts(): View
    {
        return $this->comingSoon('Generate Receipts');
    }

    /**
     * Display generic coming soon page
     */
    private function comingSoon(string $feature): View
    {
        return view('tools.coming-soon', compact('feature'));
    }
}
