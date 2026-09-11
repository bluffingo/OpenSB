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
     * @var SquareBracket The core OpenSB class.
     */
    private SquareBracket $sb;

    /**
     * @var Database The Database class.
     */
    private Database $database;

    /**
     * @var Profiler The Profiler class.
     */
    private Profiler $profiler;

    /**
     * @var Storage The Storage class.
     */
    private Storage $storage;

    /**
     * @var Authentication The authentication class.
     */
    private Authentication $authentication;

    /**
     * @var Environment The Twig environment.
     */
    private Environment $twig;

    /**
     * @var array The current skin's options.
     */
    private array $skin_options;

    /**
     * function __construct
     *
     * @param SquareBracket $sb
     * @param Templating $templating
     * @param Environment $twig
     *
     * @return void
     */
    public function __construct(SquareBracket $sb, Templating $templating, Environment $twig)
    {
        $this->sb = $sb;
        $this->database = $this->sb->getDatabaseClass();
        $this->profiler = $this->sb->getProfilerClass();
        $this->storage = $this->sb->getStorageClass();
        $this->authentication = $this->sb->getAuthenticationClass();
        $this->twig = $twig;
        
        $this->skin_options = $this->sb->getSkinThemeOptions();
    }
    
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
            new TwigFunction('threadpost', [$this, 'threadpost'], ['is_safe' => ['html']]),
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
            echo $this->renderActions($pagebar['actions']);
        echo "</div></div>";
    }

    function forumlist($currentforum = -1) {
        global $userdata;
        $r = $this->database->query("SELECT c.title ctitle,f.id,f.title,f.cat FROM z_forums f LEFT JOIN z_categories c ON c.id=f.cat WHERE ? >= f.minread ORDER BY c.ord,c.id,f.ord,f.id",
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

    function threadpost($post, $pthread = '') {
        global $log, $userdata;

        if (isset($post['deleted']) && $post['deleted']) {
            //if (!IS_MOD) return;

            $pid = $post['id'];
            $ulink = "userlink"; //userlink($post, 'u');
            return <<<HTML
                <table class="c1 threadpost" id="{$post['id']}"><tr>
                    <td class="n1 sidebar">$ulink</td>
                    <td class="n1 topbar">
                        (post deleted)
                        <span class="float-right">
                            <a href="thread?pid=$pid&pin=$pid#$pid">Peek</a>
                            &ndash; <a href="editpost?pid=$pid&act=undelete">Undelete</a>
                        </span>
                    </td>
                </tr></table>
            HTML;
        }

        $headerbar = $threadlink = $postlinks = $revisionstr = '';

        if (isset($post['headerbar']))
            $headerbar = sprintf('<tr class="h"><td colspan="2">%s</td></tr>', $post['headerbar']);

        $post['id'] = $post['id'] ?? 0;
        $postlinks = [];

        if ($pthread)
            $threadlink = sprintf(' - in <a href="thread?id=%s">%s</a>', $pthread['id'], esc($pthread['title']));

        if ($post['id'])
            $postlinks[] = "<a href=\"thread?pid=$post[id]#$post[id]\">Link</a>";

        if (isset($post['revision']) && $post['revision'] >= 2)
            $revisionstr = " (edited ".date('Y-m-d H:i', $post['ptdate']).")";

        if (isset($post['thread']) && $log) {
            // TODO: check minreply
            $postlinks[] = "<a href=\"newreply?id=$post[thread]&pid=$post[id]\">Quote</a>";

            // "Edit" link for admins or post owners, but not banned users
            /*if (IS_ADMIN || $userdata['id'] == $post['uid'])
                $postlinks[] = '<a href="editpost?pid='.$post['id'].'">Edit</a>';

            if (IS_MOD)
                $postlinks[] = '<a href="editpost?pid='.$post['id'].'&act=delete">Delete</a>';

            if (isset($post['maxrevision']) && IS_MOD && $post['maxrevision'] > 1) {
                $revisionstr .= " &ndash; Revision ";
                for ($i = 1; $i <= $post['maxrevision']; $i++)
                    $revisionstr .= "<a href=\"thread?pid=$post[id]&pin=$post[id]&rev=$i#$post[id]\">$i</a> ";
            }
            */
        }

        $postlinks = join(' &ndash; ', $postlinks);

        $ulink = "userlink"; //userlink($post, 'u');
        $pdate = date('Y-m-d H:i', $post['date']);
        $picture = null; //($post['uavatar'] ? '<img class="avatar" src="'.self::avatarUrl($post, 'u').'" alt="(Avatar)"><br>' : '');

        $signature = null; //$post['usignature'] && $log ? '<div class="siggy">'.self::postfilter($post['usignature']).'</div>' : '';

        $ujoined = date('Y-m-d', $post['ujoined']);
        $posttext = self::postfilter($post['text']);
        return <<<HTML
            <table class="c1 threadpost" id="{$post['id']}">
                $headerbar
                <tr>
                    <td class="n2 topbar_mobile blkm nod clearfix">
                        <span style="float:left;margin-right:10px">$picture</span>
                        $ulink
                    </td>
                </tr>
                <tr>
                    <td class="n2 sidebar nom" rowspan="2">
                        $picture
                        $ulink
                        <br>
                        <br><strong>Posts:</strong> 0
                        <br><strong>Joined:</strong> $ujoined
                    </td>
                    <td class="n2 topbar blkm clearfix">
                        Posted on $pdate$threadlink$revisionstr <span class="float-right">$postlinks</span>
                    </td>
                </tr><tr>
                    <td class="n2 mainbar">$posttext$signature</td>
                </tr>
            </table>
        HTML;
    }

    private function avatarUrl($user, $pf = '') {
        global $sb;

        return $sb->getStorageClass()->getUserProfilePicture($user);
    }

    private function postfilter($msg) {
        $msg = str_replace("[/quote]", "[/quote]\n\n", $msg);

        //$msg = markdown($msg); right. fuck.

        $msg = preg_replace("'\[reply=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=showprivate?id=\\2>Sent by \\1</a></i></small></span><hr>', $msg);
        $msg = str_replace('[/reply]', '<hr></blockquote>', $msg);
        $msg = preg_replace("'\[quote=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=thread?pid=\\2#\\2>Posted by \\1</a></i></small></span><hr>', $msg);
        $msg = str_replace('[/quote]', '<hr></blockquote>', $msg);

        return $msg;
    }
}