<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Lorem ipsum plugin
 *
 * Generate lorem ipsum natively using {{ lex }} tags
 *
 * @author  	Matt Potts, Tripod Software Limited
 * @package		Loremipsum
 * @copyright	Copyright (c) 2013, Tripod Software Limited
 */
class Plugin_Loremipsum extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Lorem Ipsum'
	);

	public $description = array(
		'en'	=> 'Generate lorem ipsum without having to go elsewhere'
	);

	/**
	 * return $x unformatted words taken from source data
	 */
	function _words($x)
	{
		$source = file_get_contents('addons/shared_addons/plugins/loremipsum.txt');

		// parse source
		$lower = strtolower(trim($source));
		$alpha = preg_replace('/[^a-z\s]/', '', $lower);
		$words = explode(' ', $alpha);
		$wordcount = count($words);

		// glue some words together
		$lorem = array();
		for($i=0; $i<$x; $i++)
		{
			// throw a comma in?
			$comma = '';
			if(0 === mt_rand(0, 10))
			{
				$comma = ',';
			}
		
			// pick a word
			$index = mt_rand(0, $wordcount-1);
			$lorem[] = $words[$index].$comma;
		}
		$ipsum = trim(implode(' ', $lorem), ',');
		
		return $ipsum;
	}
	
	/**
	 * return a sentence with given number of words
	 */
	function _sentence($length = null)
	{
		if(null === $length)
		{
			$length = mt_rand(7, 17); // words
		}
		
		$words = $this->_words($length);
		$sentence = ucfirst($words).'.';
		return $sentence;
	}
	
	/**
	 * return paragraph with given number of sentences
	 */
	function _paragraph($length = null)
	{
		if(null === $length)
		{
			$length = mt_rand(3, 9); // sentences
		}
		
		$sentences = array();
		for($i=0; $i<$length; $i++)
		{
			$sentences[] = $this->_sentence();
		}
		$paragraph = implode(' ', $sentences);
		return $paragraph;
	}
	
	/**
	 * return article with given number of paragraphs
	 */
	function _article($length = null)
	{
		if(null === $length)
		{
			$length = mt_rand(5, 12); // paragraphs
		}
		
		$paragraphs = array();
		for($i=0; $i<$length; $i++)
		{
			$paragraphs[] = $this->_paragraph();
		}
		$article = implode('', $paragraphs);
		return $article;
	}
	
	/**
	 * plugin method to get a single word
	 *
	 * {{ loremipsum:word }}
	 */
	function word()
	{
		$length = $this->attribute('length', null);
		list($first) = explode(' ', $this->_sentence($length));
		return trim($first,',');
	}
	
	/**
	 * plugin method to get a sentence
	 *
	 * {{ loremipsum:sentence }}
	 */
	function sentence()
	{
		$length = $this->attribute('length', null);
		return $this->_sentence($length);
	}
	
	/**
	 * plugin method to get a title
	 *
	 * {{ loremipsum:title }}
	 */
	function title()
	{
		$length = $this->attribute('length', null);
		$sentence = $this->_sentence($length);
		return trim(ucwords($sentence),'.');
	}
	
	/**
	 * plugin method to get a paragraph
	 *
	 * {{ loremipsum:paragraph }}
	 */
	function paragraph()
	{
		$length = $this->attribute('length', null);
		return $this->_paragraph($length);
	}
	
	/**
	 * plugin method to get an article
	 *
	 * {{ loremipsum:article }}
	 */
	function article()
	{
		$length = $this->attribute('length', null);
		return $this->_article($length);
	}
}
