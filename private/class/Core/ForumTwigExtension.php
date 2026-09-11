<?php

/*
  OpenSB: The Open SquareBracket Software

  Copyright (C) 2026 Chaziz

  This file is based on code from Principia-web.
  Copyright (C) 2020-2026 ROllerozxa

  OpenSB is free software: you can redistribute it and/or modify it under the 
  terms of the GNU Affero General Public License as published by the Free 
  Software Foundation, either version 3 of the License, or (at your option) any
  later version. 

  OpenSB is distributed in the hope that it will be useful, but WITHOUT ANY 
  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
  FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more 
  details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

namespace Core;

use Exception;
use Parsedown;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use Data\User\UserRoleEnum;
use Data\Upload\UploadTypeEnum;
use Data\User\UserData;
use Data\User\UserFlags;

/**
 * class ForumTwigExtension
 */
class ForumTwigExtension extends AbstractExtension
{
    /**
     * function getFunctions
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('timelinks', [$this, 'timelinks']),
            new TwigFunction('new_status', [$this, 'newStatus']),
            new TwigFunction('render_page_bar', [$this, 'renderPageBar']),
            new TwigFunction('if_empty_query', [$this, 'ifEmptyQuery']),
            new TwigFunction('threadpost', [$this, 'threadpost']),
            new TwigFunction('minipost', [$this, 'minipost']),
        ];
    }

    function newStatus($type) {
        $text = match ($type) {
            'n'  => 'NEW',
            'o', 'on' => 'OFF'
        };
        $statusimg = match ($type) {
            'n'  => 'new.png',
            'o'  => 'off.png',
            'on' => 'offnew.png'
        };

        return "<img src=\"/assets/status/$statusimg\" alt=\"$text\">";
    }

    function renderActions($actions) {
        $out = [];

        foreach ($actions as $url => $title)
            $out[] = ($url == 'none' ? $title : sprintf('<a href="%s">%s</a>', htmlspecialchars($url), $title));

        return join(' &ndash; ', $out);
    }

    function renderPageBar($pagebar) {
        if (empty($pagebar)) return;

        echo '<div class="breadcrumb"><a href="./">Forum</a> &raquo; ';
        if (!empty($pagebar['breadcrumb'])) {
            foreach ($pagebar['breadcrumb'] as $url => $title)
                printf('<a href="%s">%s</a> &raquo; ', htmlspecialchars($url), $title);
        }
        echo htmlspecialchars($pagebar['title']).'<div class="actions">';
        if (!empty($pagebar['actions']))
            echo renderActions($pagebar['actions']);
        echo "</div></div>";
    }

    function forumlist($currentforum = -1) {
        global $userdata;
        $r = query("SELECT c.title ctitle,f.id,f.title,f.cat FROM z_forums f LEFT JOIN z_categories c ON c.id=f.cat WHERE ? >= f.minread ORDER BY c.ord,c.id,f.ord,f.id",
            [$userdata['rank']]);
        $out = '<select id="forumselect">';
        $c = -1;
        while ($d = $r->fetch()) {
            if ($d['cat'] != $c) {
                if ($c != -1)
                    $out .= '</optgroup>';
                $c = $d['cat'];
                $out .= '<optgroup label="'.$d['ctitle'].'">';
            }
            $out .= sprintf(
                '<option value="%s"%s>%s</option>',
            $d['id'], ($d['id'] == $currentforum ? ' selected="selected"' : ''), $d['title']);
        }
        $out .= "</optgroup></select>";

        return $out;
    }

    function ifEmptyQuery($message, $colspan = 0, $table = false) {
        if ($table) echo '<table class="c1">';
        echo '<tr><td class="n1 center" '.($colspan != 0 ? "colspan=$colspan" : '')."><p>$message</p></td></tr>";
        if ($table) echo '</table>';
    }

    function twigloaderForum() {
        /*$twig = twigloader();

        $twig->addFunction('timelinks');
        $twig->addFunction('new_status', 'newStatus');
        $twig->addFunction('render_page_bar', 'renderPageBar');
        $twig->addFunction('if_empty_query', 'ifEmptyQuery');
        $twig->addFunction('threadpost');
        $twig->addFunction('minipost');

        $twig->addGlobal('submodule', 'forum');
        */
        global $twig;

        return $twig;

    }

    function timelinks($file, $seltime) {
        /*$relativeTime = new RelativeTime([
            'suffix' => false,
            'truncate' => 1,
        ]);

        $links = [];
        foreach ([3600, 86400, 604800, 2592000] as $time) {
            $timelbl = $relativeTime->convert(1, $time+1);

            if ($time == $seltime)
                $links[] = $timelbl;
            else
                $links[] = sprintf('<a href="%s?time=%s">%s</a>', $file, $time, $timelbl);
        }

        return join(' &ndash; ', $links);*/

        return "this is where timelinks is supposed to be!";
    }
}