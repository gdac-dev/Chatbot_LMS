<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Validator;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class firstConversation extends Conversation
{ 
    /** * First question */ 
    protected $name;

    protected $email;

    protected $query;

    public function askName()
    {
        $this->getBot()->typesAndWaits(1.5);
        $this->ask('Hi 👋🏽! What is your name ?', function(Answer $answer) {
            // Save result
            $this->name = $answer->getText();

            $this->getBot()->typesAndWaits(1.5);

            $this->say('Nice to meet you ' .$this->name.' 😇');

            $this->getBot()->typesAndWaits(1.5);
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email address?', function(Answer $answer) {
            $validator = Validator::make(['email' => $answer->getText()], [
                'email' => 'email',
            ]);

            $this->getBot()->typesAndWaits(1.5);
            if ($validator->fails()) {
                return $this->repeat('🤔🤨 That doesn\'t look like a valid email. Please enter a valid email.');
            }
    
            // Save result
            $this->bot->userStorage()->save([
                'email' => $answer->getText(),
            ]);

            $this->say('Good 👍 We stiil need some information');
            $this->askPassword();
        });
    }

    public function askPassword()
    {
        $this->getBot()->typesAndWaits(2);
        $this->ask('Enter a password of at least 6 characters: ', function(Answer $answer) {

            $validator = Validator::make(['password' => $answer->getText()], [
                'password' => 'required|min:6',
            ]);

            $this->getBot()->typesAndWaits(1.5);
            if ($validator->fails()) {
                return $this->repeat('🤔 Invalid Passwor ! Enter a password of at least characters: ');
            }
    
            // Save result
            $this->bot->userStorage()->save([
                'password' => $answer->getText(),
            ]);

            $this->getBot()->typesAndWaits(1.5);
            $this->say('Great 🥳🔥 That is all we need, '.$this->name);

            $this->getBot()->typesAndWaits(2);
            $this->askMeWhy();
            
        });
    }
    
    public function askMeWhy() {
        $question = Question::create("Do you want to be registered in our database ?") 
            ->addButtons([ 
                Button::create('Yes I want')->value('yes'),
                Button::create('Not Yet')->value('no'),
            ]);
            
        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->say("OK Fine !");
                $this->getBot()->typesAndWaits(2);

                if ($answer->getValue() === 'yes') {
                    $this->say("You are dynamic and strong!");
                } else {
                    $this->say("You are reliable and friendly!");
                }
            }
            $this->bot->startConversation(new MainConversation());
        });
    }
    
    /** * Begin conversation */ 
    public function run() {

        $this->askName(); 
    } 
}

                
