<?php

if (function_exists('dd') == FALSE) {
	function dd() {
		array_map(function ($x) {
			var_dump($x);
		}, func_get_args());
		
		die(1);
	}
}

// (c) Laravel idea 
if (function_exists('asset') == FALSE) {
	function asset ($asset, $path = 'public') {
		$manifest_file = sprintf('%s/%s/mix-manifest.json', ROOT_PATH, $path);

		if (file_exists($manifest_file) == FALSE) {
			throw new Exception('The Mix manifest does not exist.');
		}
		
		$manifest = json_decode(file_get_contents($manifest_file), true);

		if (substr($asset, 0, 1) != '/') {
			$asset = '/' . $asset;
		}
		
		if (array_key_exists($asset, $manifest) == FALSE) {
			throw new Exception(sprintf('Unable to locate asset "%s" in manifest.', $asset));
		}

		$asset_path = sprintf('%s/%s%s', ROOT_PATH, $path, $asset);

		if (file_exists($asset_path) == FALSE) {
			throw new Exception(sprintf('Asset "%s" not found.', $asset_path));
		}

		return $manifest[$asset];
	}
}