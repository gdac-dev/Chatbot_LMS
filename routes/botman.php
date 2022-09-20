<?php

use App\Http\Controllers\BotManController;
//use App\Http\Middleware\DialogflowV2; 

$botman = resolve('botman');

/*$dialogflow = DialogflowV2::create()->listenForAction();
$botman->middleware->received($dialogflow); */

$botman->hears('(Hi|Hey)', function ($bot) {

    $bot->reply('Greetings 👋! How can I help ?');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');

$botman->hears('Hello', function ($bot) { 

    $bot->reply('Hi 👋🙌!');
});
$botman->hears('Menu', BotManController::class.'@startConversation');

$botman->hears('start learning', BotManController::class.'@firstConversation');

/*$botman->hears('chat.Menu', function ($bot) {
    $extras = $bot->getMessage()->getExtras(); 
    $apiReply = $extras['apiReply']; 
    $apiAction = $extras['apiAction']; 
    $apiIntent = $extras['apiIntent'];

    $bot->reply($apiReply); 
})->middleware($dialogflow);*/

/*$botman->fallback(function($bot) {

    //return $bot->reply($bot->getMessage()->getExtras('apiReply'));
    return $bot->reply($bot->getMessage()->getExtras('apiIntent'));
});*/
 