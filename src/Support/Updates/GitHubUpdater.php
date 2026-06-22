<?php

declare(strict_types=1);

namespace ArDesign\YaymailPaymentQr\Support\Updates;

use ArDesign\Shared\Updates\GitHubPluginUpdater as BaseGitHubPluginUpdater;

defined( 'ABSPATH' ) || exit;

require_once WP_PLUGIN_DIR . '/ar-design-shared-support/includes/updates/GitHubPluginUpdater.php';

final class GitHubUpdater extends BaseGitHubPluginUpdater
{
	public function __construct(
		string $repository_full_name,
		string $plugin_basename,
		string $current_version,
		string $plugin_slug,
		string $plugin_name,
		string $text_domain,
		string $description
	) {
		parent::__construct(
			$repository_full_name,
			$plugin_basename,
			$current_version,
			array(
				'plugin_slug' => $plugin_slug,
				'plugin_name' => $plugin_name,
				'text_domain' => $text_domain,
				'description' => $description,
				'author_label' => 'Arpad70',
				'user_agent_slug' => $plugin_slug,
				'cache_key_prefix' => 'ard_yaymail_payment_qr_github_release_',
				'preferred_zip_names' => array($plugin_slug . '.zip'),
				'allow_any_zip_fallback' => true,
			)
		);
	}
}
