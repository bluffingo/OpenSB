<?php


class PrincipiaForumExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			// datetime.php
			new \Twig\TwigFunction('timeunits', 'timeunits', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('timelinks', 'timelinks', ['is_safe' => ['html']]),

			// layout.php
			new \Twig\TwigFunction('new_status', 'newStatus', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('render_page_bar', 'renderPageBar', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('if_empty_query', 'ifEmptyQuery', ['is_safe' => ['html']]),

			// misc.php
			new \Twig\TwigFunction('mlink', 'mlink', ['is_safe' => ['html']]),

			// perm.php
			new \Twig\TwigFunction('can_view_forum', 'canViewForum'),
			new \Twig\TwigFunction('can_create_forum_post', 'canCreateForumPost'),
			new \Twig\TwigFunction('can_edit_forum_posts', 'canCreateForumPosts'),

			// post.php
			new \Twig\TwigFunction('threadpost', 'threadpost', ['is_safe' => ['html']]),
			
			// dummy useless shit
			new \Twig\TwigFunction('is_android_webview', function () {}),
			new \Twig\TwigFunction('custom_info', function () {}),
			new \Twig\TwigFunction('git_commit', 'gitCommit'),
			new \Twig\TwigFunction('profiler_stats', function () {}), //already implemented differently in squarebracket
		];
	}
	public function getFilters() {
		return [

		];
	}
}
