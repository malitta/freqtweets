<?php

namespace Swiftlet\Controllers;

/**
 * Twitter WS Controller
 * Web Services for various Twitter related functions
 * @author Malitta Nanayakkara <malitta@gmail.com>
 */
class Twitter extends Webservice
{
	/**
	 * Twitter connection singleton instance
	 */
	public static $twt = null;
	
	/**
	 * Initiate connection to Twitter API and manage connection as a singleton
	 */
	protected function getConnection(){

		if(self::$twt == null){
			$twt = $this->app->getLibrary('Twitteroauth\Twitteroauth');
			$con_key = $this->app->getConfig('TWITTER_CONSUMER_KEY');
			$con_secret = $this->app->getConfig('TWITTER_CONSUMER_SECRET');
			$oauth_callback = $this->app->getConfig('TWITTER_OAUTH_CALLBACK');

			$twt->connect($con_key, $con_secret, '371095924-EJvHxe1RGkY4TZnE3fen4dvpvHVO7d12hzYv0lc9', 'zILz1Cmfvu1kf2N2cckTc9FiMChcqURr3LKgrWb4sXUKx');

			// If token is not recieved, exit
			if(empty($twt->token->key)){
				$this->fail('Connection could not be initiated');
			}else{
				self::$twt = $twt;
			}
		}

		return self::$twt; 
	}


	public function freqwords($handle = '', $tweet_count){

		if(!preg_match('/^[A-Za-z0-9_]{1,15}$/', $handle) || empty($handle)){
			$this->fail('Please enter a valid Twitter handle');
		}

		// default limit
		if((int) $tweet_count == 0) $tweet_count = 20;

		// tweets limit
		if($tweet_count < 1 || $tweet_count > 200){
			$this->fail('You can only search from upto 200 tweets');
		}
		
		$twt = self::getConnection();

		// get latest tweets from user
		$res = $twt->get('statuses/user_timeline', array('screen_name'=>$handle, 'count'=>$tweet_count));
		
		// handle errors
		if(isset($res->errors)){
			$this->fail('User does not exist or account is protected');
		}else if(empty($res)){
			$this->fail('An error occured while trying to get user details');
		}

		$tweets = $res;

		// extract only the tweeted message
		$tweets_strs = array();
		foreach($tweets as $t){
			$tmsg = html_entity_decode($t->text);

			// remove links
			$tmsg = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $tmsg);
			
			// remove mentions
			$tmsg = preg_replace('/@([^@ ]+)/i', '', $tmsg);
			
			// get all words
			$n_words = preg_match_all('/([a-zA-Z]|\xC3[\x80-\x96\x98-\xB6\xB8-\xBF]|\xC5[\x92\x93\xA0\xA1\xB8\xBD\xBE]){2,}/', $tmsg, $match_arr);
			$word_arr = $match_arr[0];
			$tweets_strs[] = $word_arr;
		}
		
		// blacklist twitter terminology
		$bl = array('rt', 'mt', 'ft', 'via');

		// blacklist common words
		$bl = array_merge($bl, array('to', 'and', 'of', 'for', 'you', 'me', 'from', 'or', 'by', 'is', 'was', 'it', 'an', 'on', 'if', 'but', 'the', 'in', 'that', 'them', 'so', 'not', 'just', 'all', 'are', 'we', 'they', 'be', 'this', 'my', 'your', 'now', 'like', 'at', 'why', 'am', 'its', 'up', 'with', 'our', 'new', 'do', 'what', 'who', 'gets', 'he', 'can', 'will', 'have', 'no', 're', 'as'));

		$unique_word_count = array();
		foreach($tweets_strs as $words){
			foreach($words as $word){
				$word = trim(strtolower($word));
				if(in_array($word, $bl)) continue;
				$unique_word_count[$word] = (isset($unique_word_count[$word])) ? $unique_word_count[$word] + 1 : 1;
			}
		}
		
		arsort($unique_word_count);

		$top_10 = array_splice($unique_word_count, 0, 10);

		$this->result = array('top10' => $top_10);
	}

}
