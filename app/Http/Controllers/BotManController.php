<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\firstConversation;
use App\Conversations\ExampleConversation;
use Illuminate\Support\Collection;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
/*
        $botman->fallback(function($bot) 
        { 
            $bot->reply( $this->fallBackResponse()); 
        });
*/
        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }

    public function firstConversation(BotMan $bot) {       

        $bot->startConversation(new firstConversation()); 
    }

    public function fallBackResponse() { 
        
        return Collection::make([ 
            "Sorry, I didn't understand. Could you repeat, please?", 
            "I still don't understand, try again, right?", 
            "Could you please repeat, because I could not understand.", 
            "Try out: choose color, hi, hello, menu or start conversation", 
        ])->random(); 
    }

}
