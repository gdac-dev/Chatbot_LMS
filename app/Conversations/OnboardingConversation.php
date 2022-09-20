<?php

namespace App\BotMan;

use Illuminate\Support\Facades\Validator;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\BotMan\MainConversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class OnboardingConversation extends Conversation
{
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
            $this->bot->startConversation(new MainConversation());
        });
    }

    /*public function stopsConversation(IncomingMessage $message)
	{
		if ($message->getText() == 'stop') {
			return true;
		}

		return false;
	}


	public function skipsConversation(IncomingMessage $message)
	{
		if ($message->getText() == 'pause') {
			return true;
		}

		return false;
	}*/

    // ...


    public function run()
    {
        // This will be called immediately
        $this->askName();
    }
}