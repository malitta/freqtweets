<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $this->htmlEncode($this->app->getConfig('siteName')) . ' - ' . $this->pageTitle ?></title>
	<link type="text/css" rel="stylesheet" href="<?= $this->app->getRootPath() ?>packages/bootstrap/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="<?= $this->app->getRootPath() ?>css/layout.css">
	<script type="text/javascript" src="<?= $this->app->getRootPath() ?>js/jquery-1.10.2.min.js"></script>
</head>
<body>