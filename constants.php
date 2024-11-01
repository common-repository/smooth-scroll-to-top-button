<?php

namespace HulkPlugins\SmoothScrollToTopButton;

const VERSION = '1.0.0';

define( 'HulkPlugins\SmoothScrollToTopButton\PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'HulkPlugins\SmoothScrollToTopButton\PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

const SUPPORT       = 'https://hulkplugins.com/support/';
const DOCUMENTATION = 'https://hulkplugins.gitbook.io/smooth-scroll-to-top-button/';

const SUPPORTED_SETTINGS = [
	'buttonColor'        => 'linear-gradient(#29323c, #485563)',
	'buttonHoverColor'   => 'linear-gradient(#000000, #434343)',
	'iconColor'          => 'rgb(255, 255, 255)',
	'iconHoverColor'     => '',
	'text'               => 'To Top',
	'enableButtonBorder' => false,
	'borderWidth'        => 1,
	'borderColor'        => '#0000004d',
	'borderHoverColor'   => '',
	'size'               => 'm',
	'shape'              => 'rounded',
	'radius'             => 8,
	'icon'               => 'arrow-4',
	'tooltip'            => '',
	'textFontWeight'     => 'bold',
	'position'           => 'bottom-right',
	'scrollPosition'     => 10,
	'scrollingSpeed'     => 0.6,
	'pages'              => 'all',
	'excludedPages'      => [],
	'specificPages'      => [],
	'devices'            => [
		'desktop' => true,
		'tablet'  => true,
		'mobile'  => true,
	],
	'customCss'          => '',
	'distanceTop'        => 0,
	'distanceLeft'       => 0,
	'distanceBottom'     => 10,
	'distanceRight'      => 10,
	'animation'          => 'fade',
	'width'              => 60,
	'height'             => 60,
	'iconSize'           => 16,
	'enable'             => true,
	'type'               => 'column',
	'direction'          => 'column',
	'orientation'        => 'horizontal',
	'reverse'            => false,
	'borderStyle'        => 'solid',
	'textSize'           => 12,
	'gap'                => 5,
];
