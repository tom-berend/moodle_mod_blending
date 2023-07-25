<?php

/*
 * This file is part of the BalancedHtmlTagsTest package.
 *
 * (c) The Plankmeister <plankmeister@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source
 * code.
 */

class HTMLtester
{

    /**
     * Balances tags of string using a modified stack.
     *
     * @since 2.0.4
     *
     * @author Leonard Lin <leonard@acm.org>
     * @license GPL v2.0
     * @copyright November 4, 2001
     * @version 1.1
     * @todo Make better - change loop condition to $text in 1.2
     * @internal Modified by Scott Reilly (coffee2code) 02 Aug 2004
     *		1.1  Fixed handling of append/stack pop order of end text
     *			 Added Cleaning Hooks
     *		1.0  First Version
     *
     * @param string $text Text to be balanced.
     * @return string Balanced text.
     */
    function validate($text, $isUnitTest = false)
    {
        $originalText = $text;
        // lazy, i sometimes use '<br>' when I mean <br />
        // fix it here, so it still matches the output
        $originalText = str_replace('<br>', '<br />', $originalText);


        $tagstack = array();
        $stacksize = 0;
        $tagqueue = '';
        $newtext = '';
        $single_tags = array(
            'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta',
            'param', 'source', 'track', 'wbr'
        ); //Known single-entity/self-closing tags
        $nestable_tags = array('blockquote', 'div', 'span'); //Tags that can be immediately nested within themselves


        # WP bug fix for comments - in case you REALLY meant to type '< !--'
        $text = str_replace('< !--', '<    !--', $text);
        # WP bug fix for LOVE <3 (and other situations with '<' before a number)
        $text = preg_replace('#<([0-9]{1})#', '&lt;$1', $text);

        while (preg_match("/<(\/?\w*)\s*([^>]*)>/", $text, $regex)) {
            $newtext .= $tagqueue;

            $i = strpos($text, $regex[0]);
            $l = strlen($regex[0]);

            // clear the shifter
            $tagqueue = '';
            // Pop or Push
            if (isset($regex[1][0]) && '/' == $regex[1][0]) { // End Tag
                $tag = strtolower(substr($regex[1], 1));
                // if too many closing tags
                if ($stacksize <= 0) {
                    $tag = '';
                    //or close to be safe $tag = '/' . $tag;
                }
                // if stacktop value = tag close value then pop
                else if ($tagstack[$stacksize - 1] == $tag) { // found closing tag
                    $tag = '</' . $tag . '>'; // Close Tag
                    // Pop
                    array_pop($tagstack);
                    $stacksize--;
                } else { // closing tag not at top, search for it
                    for ($j = $stacksize - 1; $j >= 0; $j--) {
                        if ($tagstack[$j] == $tag) {
                            // add tag to tagqueue
                            for ($k = $stacksize - 1; $k >= $j; $k--) {
                                $tagqueue .= '</' . array_pop($tagstack) . '>';
                                $stacksize--;
                            }
                            break;
                        }
                    }
                    $tag = '';
                }
            } else { // Begin Tag
                $tag = strtolower($regex[1]);

                // Tag Cleaning

                // If self-closing or '', don't do anything.
                if ((substr($regex[2], -1) == '/') || ($tag == '')) {
                }
                // ElseIf it's a known single-entity tag but it doesn't close itself, do so
                elseif (in_array($tag, $single_tags)) {
                    $regex[2] .= '/';
                } else {    // Push the tag onto the stack
                    // If the top of the stack is the same as the tag we want to push, close previous tag
                    if (($stacksize > 0) && !in_array($tag, $nestable_tags) && ($tagstack[$stacksize - 1] == $tag)) {
                        $tagqueue = '</' . array_pop($tagstack) . '>';
                        $stacksize--;
                    }
                    $stacksize = array_push($tagstack, $tag);
                }

                // Attributes
                $attributes = $regex[2];
                if ($attributes) {
                    $attributes = ' ' . $attributes;
                }
                $tag = '<' . $tag . $attributes . '>';
                //If already queuing a close tag, then put this tag on, too
                if ($tagqueue) {
                    $tagqueue .= $tag;
                    $tag = '';
                }
            }
            $newtext .= substr($text, 0, $i) . $tag;
            $text = substr($text, $i + $l);
        }

        // Clear Tag Queue
        $newtext .= $tagqueue;

        // Add Remaining text
        $newtext .= $text;

        // Empty Stack
        while ($x = array_pop($tagstack)) {
            $newtext .= '</' . $x . '>'; // Add remaining tags to close
        }

        // WP fix for the bug with HTML comments
        $newtext = str_replace("< !--", "<!--", $newtext);
        $newtext = str_replace("<    !--", "< !--", $newtext);

        // fix for <math-field>
        $newtext = str_replace("math -field", "math-field", $newtext);
        $newtext = str_replace("</math", "</math-field", $newtext);

        // found an error, but only report if we are not unittesting this module
        if (!$isUnitTest) {
            if (rtrim($text) !== rtrim($newtext)) {

                // printNice(htmlentities($originalText));
                for ($offset = 0; $offset + 1 < min(strlen($originalText), strlen($newtext)); $offset++) {
                    if ($originalText[$offset] !== $newtext[$offset]) {

                        $a1 = explode(" ", $originalText);
                        $a2 = explode(" ", $newtext);

                        $b1 = join("<", array_diff($a1, $a2));
                        $b2 = join("<", array_diff($a2, $a1));

                        if (!empty($b1)) {
                            // alertMessage("BAD:'$b1',    CORRECTED:'$b2'");
                            printNice($b1, 'bad'); // output :- world
                            printNice($b2, 'corrected'); // output :- world
                        }
                        // printNice($this->diff($a1,$a2),'difference');

                        // assertTrue(false, 'HTML ERROR, correction in blue (look for "OK until"');
                        // printNice(neutered($originalText), neutered($newtext));

                        // printNice("First difference at position $offset: {$originalText[$offset]} vs {$newtext[$offset]}");
                        // printNice("OK until $offset: " . substr($originalText, $offset - 5));
                        break;
                    }
                }
            }
        }
        return $newtext == $originalText;
    }


    /*
        Paul's Simple Diff Algorithm v 0.1
        (C) Paul Butler 2007 <http://www.paulbutler.org/>
        May be used and distributed under the zlib/libpng license.

        This code is intended for learning purposes; it was written with short
        code taking priority over performance. It could be used in a practical
        application, but there are a few ways it could be optimized.

        Given two arrays, the function diff will return an array of the changes.
        I won't describe the format of the array, but it will be obvious
        if you use print_r() on the result of a diff on some test data.

        htmlDiff is a wrapper for the diff command, it takes two strings and
        returns the differences in HTML. The tags used are <ins> and <del>,
        which can easily be styled with CSS.
    */

    function diff($old, $new)
    {
        $matrix = array();
        $maxlen = 0;
        foreach ($old as $oindex => $ovalue) {
            $nkeys = array_keys($new, $ovalue);
            foreach ($nkeys as $nindex) {
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                    $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if ($matrix[$oindex][$nindex] > $maxlen) {
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }
        }
        if ($maxlen == 0) return array(array('d' => $old, 'i' => $new));
        return array_merge(
            $this->diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
            array_slice($new, $nmax, $maxlen),
            $this->diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen))
        );
    }

    function htmlDiff($old, $new)
    {
        $ret = '';
        $diff = $this->diff(preg_split("/[\s]+/", $old), preg_split("/[\s]+/", $new));
        foreach ($diff as $k) {
            if (is_array($k))
                $ret .= (!empty($k['d']) ? "<del>" . implode(' ', $k['d']) . "</del> " : '') .
                    (!empty($k['i']) ? "<ins>" . implode(' ', $k['i']) . "</ins> " : '');
            else $ret .= $k . ' ';
        }
        return $ret;
    }





    /**
     * Tests broken tag nesting.
     */
    public function testBrokenTags()
    {

        $brokenHtml = '<div>abc';
        assertTrue(!$this->validate($brokenHtml, true));


        $brokenHtml = '<div>one<p></div>two</p>';
        assertTrue(!$this->validate($brokenHtml, true));
    }

    /**
     * Tests unbalanced tags
     */
    public function testUnbalancedTags()
    {
        $brokenHtml = '<div>one<p></div>two';
        assertTrue(!$this->validate($brokenHtml, true));
    }

    /**
     * Tests balanced tags.
     */
    public function testBalancedTags()
    {
        $validHtml = '<div><p><i>blah</i><input name="fred" type="whatever" /></p></div>';
        assertTrue($this->validate($validHtml, true));
    }

    public function testStuff()
    {
        $tests = [
            ['<p>something1</p>', true],
            ['<p>something2&nbsp;</p>', true],
            ['<p>something2&nbsp;</p><br>', true],
            ['<p>something2&nbsp;</p><br />', true],
            ['<p>something3', false],
            ["<html><body></body></html>", true],
            ["<html><body><span /></body></html>", true],
            ["<html><body><table><tr><td>first element</td><td>second element<img src='stuff here' /></td></tr></table></body></html>", true],
            ["<html><body><span>missing span close</div></body></html>", false],
            ["<html><body><span>missing bracket</span></div</body></html>", false],
        ];

        foreach ($tests as $test) {
            if ($test[1]) {
                assertTrue($this->validate($test[0], true), neutered($test[0]));
            } else {
                assertTrue(!$this->validate($test[0], true), neutered($test[0]));
            }
        }
    }
    // /**
    //  * Tests balancing broken tag nesting.
    //  */
    // public function testBalancingBrokenTags()
    // {
    //     $brokenHtml = '<div>one<p></div>two</p>';
    //     $this->assertEquals('<div>one<p></p></div>two', BalancedHtmlTags::balanceTags($brokenHtml));
    // }

    // /**
    //  * Tests balancing unbalanced tags
    //  */
    // public function testBalancingUnbalancedTags()
    // {
    //     $brokenHtml = '<div>one<p></div>two';
    //     $this->assertEquals('<div>one<p></p></div>two', BalancedHtmlTags::balanceTags($brokenHtml));
    // }

    // /**
    //  * Tests balancing balanced tags.
    //  */
    // public function testBalancingBalancedTags()
    // {
    //     $validHtml = '<div><p><i>blah</i><input name="fred" type="whatever" /></p></div>';
    //     $this->assertEquals($validHtml, BalancedHtmlTags::balanceTags($validHtml));
    // }
}
