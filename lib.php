<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Video JS Filter
 *
 * Replaces a shortcode with a Video JS player
 *
 * @package    filter_videojs
 * @copyright  2014 onwards Kevin Wiliarty {@link http://kevinwiliarty.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Video JS object class.
 *
 * @package    filter_videojs
 * @copyright  2014 onwards Kevin Wiliarty {@link http://kevinwiliarty.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_videojs_object {

    protected $shortcode;
    protected $transcript;
    protected $html;
    protected $clips = array();
    protected $tracks = array();
    protected $mimes = array(
        'mp4'        => '',
        'webm'       => '',
        'ogg'        => '',
    );
    protected $params = array(
        'poster'     => '',
        'height'     => '',
        'width'      => '',
        'class'      => 'video-js vjs-default-skin',
        'controls'   => 'controls',
        'preload'    => 'auto',
        'data-setup' => '{ "playbackRates" : [0.7, 1, 1.5, 2.0] }'
    );

    /**
     * Create an object for each shortcode
     *
     */
    public function __construct($shortcode) {
        global $PAGE;
        $this->shortcode = $shortcode;
        $this->get_params('videojs');
        $this->clips = $this->get_clips();
        $this->tracks = $this->get_tracks();
        $this->transcript = new filter_videojs_transcript($this->trackparams['src']);
        $this->build_html();
        $PAGE->requires->yui_module('moodle-filter_videojs-transcript', 'M.filter_videojs.transcript.init', array());
    }

    /**
     * Get the clips
     */
    public function get_clips() {
        $regex = '\[clip\].*?\[\/clip\]';
        preg_match_all("/$regex/sm", $this->shortcode, $clips, PREG_SET_ORDER);
        foreach ($clips as $key => $clip) {
            $this->clips[$key] = new filter_videojs_clip($clip[0], $this->mimes);
        }
        return $this->clips;
        echo "<pre>";
        print_r($this->clips);
        echo "</pre>";
    }

    /**
     * Get the tracks
     */
    public function get_tracks() {
        $regex = '\[track\].*?\[\/track\]';
        preg_match_all("/$regex/sm", $this->shortcode, $tracks, PREG_SET_ORDER);
        foreach ($tracks as $key => $track) {
            $this->clips[$key] = new filter_videojs_clip($track[0]);
        }
        return $this->tracks;
        echo "<pre>";
        print_r($this->tracks);
        echo "</pre>";
    }

    /**
     * Parse the shortcode parameters
     */
    public function get_params($kind) {
        $paramlist = str_replace("[$kind]", '', $this->shortcode);
        $paramlist = str_replace("[/$kind]", '', $paramlist);
        $paramlist = preg_replace("/\[(\w*)\].*?\[\/\\1\]/sm", '', $paramlist);
        $this->get_values($this->params, $paramlist);
        $this->get_values($this->mimes, $paramlist);
    }

    /**
     * Get the values for a given parameter
     */
    public function get_values(&$keys, $paramlist) {
        foreach ($keys as $key => $value) {
            preg_match( "/${key}=(.)/", $paramlist, $quotes);
            if (!array_key_exists(1, $quotes)) {
                continue;
            }
            switch ($quotes[1]) {
                case '"';
                    $needle = "${key}=\"([^\"]*)";
                    break;
                case "'";
                    $needle = "${key}='([^']*)";
                    break;
                default;
                    $needle = "${key}=([^ ]*)";
                    break;
            }
            preg_match("/$needle/", $paramlist, $matches);
            if (array_key_exists(1, $matches)) {
                $keys[$key] = $matches[1];
            }
        }
    }

    /**
     * Get HTML
     */
    public function get_html() {
        return $this->html;
    }

    /**
     * Build HTML
     */
    public function build_html() {
        $sourcetags = '';
        foreach ($this->mimes as $mime => $source) {
            if ($source == '') {
                continue;
            }
            $sourceatts = array(
                'src'  => $source,
                'type' => "video/$mime"
            );
            $sourcetags .= html_writer::empty_tag('source', $sourceatts);
        }
        $tracktag = html_writer::empty_tag('track', $this->trackparams);
        $videotag = html_writer::tag('video', $sourcetags.$tracktag, $this->params);
        $videodiv = html_writer::tag('div', $videotag, null);
        $this->html = "$videodiv";
    }
}

class filter_videojs_clip extends filter_videojs_object {

    protected $params = array(
        'in'         => '',
        'out'        => '',
        'label'      => '',
    );

    public function __construct($clip, $mimes) {
        $this->shortcode = $clip;
        $this->get_params('clip');
        unset($this->clips);
        if (array_count_values($this->mimes)[''] == count($this->mimes)) {
            $this->mimes = $mimes;
        }
    }
}

class filter_videojs_track extends filter_videojs_object {

    protected $params = array(
        'src'        => '',
        'kind'       => 'captions',
        'label'      => 'English',
        'srclang'    => 'en'
    );

    public function __construct($track) {
        $this->shortcode = $track;
        $this->get_params('track');
    }
}

class filter_videojs_transcript {

    private $src;

    public function __construct($src) {
        $this->src = $src;
        return $this->src;
    }
}
