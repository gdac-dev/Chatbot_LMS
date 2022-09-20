<?php

namespace App\Conversations;

use app\Test as database; // your model
use App\Test;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage; 
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Conversations\Conversation; 

class MainConversation extends Conversation {
    
    public $response;

    public function run () {
        $this->askForHelp();
    }

    public function askForHelp()
    {
        $question = Question::create('Do you need Help ?')
            ->fallback('Unable to create a new database')
            ->callbackId('create_database')
            ->addButtons([
                Button::create('Of course')->value('yes'),
                Button::create('Hell no!')->value('no'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() == 'yes') {
                    $this->userHelp = 'Yes';
                    $this->say($this->userHelp);    
                }
                else if($answer->getValue() == 'no') {
                    $this->userHelp = 'No';
                    $this->say($this->userHelp);    
                }

            }
        });
    }

    public function askResponse() { 
        $question = BotManQuestion::create("User ?"); 
        $this->ask( $question, function ( BotManAnswer $answer ) { 
            if( $answer->getText () != '' ){ 
                $this->response = $answer->getText(); 
                $this->exit(); 
            } 
        }); 
    }

    public function datalink()
    {
        $this->say('create database');

        Test::create([
            'name' => $this->bot->getUser()->getFirstName().' '.$this->bot->getUser()->getLastName(),
            'email' => 'arsene@gmail.com',
            'username' => $this->bot->getUser()->getUsername(),
            'chat_id' => $this->bot->getUser()->getId()
        ]);

        $this->say('user saved');
    }

    // this function create the object that is linked to your db's table
    private function exit() { 
        $db = new database(); 
        $db->id_chat = $this->bot->getUser()->getId(); 
        $db->response = $this->response;
        $db->save();
        $message = OutgoingMessage::create('Bye!'); 
        return true; 
    } 
}


/*<?php 
use Illuminate\Support\Facades\Validator;

$validator = Validator::make($request, [
    'name' => 'required|min:3|max:50', 
    'password' => 'required|confirmed|min:6', // this will check password_confirmation 
                                              //field in request
]);

OR example 2

$validator = Validator::make($request, [
    'name' => 'required|min:3|max:50', 
    'password' => 'required|min:6', 
    'password_confirmation' => 'required|same:password|min:6', // this will check password                           
]);

OR example 3

// create the validation rules ------------------------
    $rules = array(
        'name'             => 'required',                        // just a normal required validation
        'email'            => 'required|email|unique:ducks',     // required and must be unique in the ducks table
        'password'         => 'required',
        'password_confirm' => 'required|same:password'           // required and has to match the password field
    );

    do the validation ----------------------------------
    validate against the inputs from our form
    $validator = Validator::make(Input::all(), $rules);

    check if the validator failed -----------------------
    if ($validator->fails()) {

        // get the error messages from the validator
        $messages = $validator->messages();

        // redirect our user back to the form with the errors from the validator
        return Redirect::to('home')
            ->withErrors($validator);
*/