<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- *** Título da página -->
	<!-- Verificação ternário se esta setado o título -->
	<?php echo (isset($titulo) ? '<title> System Ordem | '.$titulo.'</title>' : '<title>System Ordem Vendas</title>') ?>

	<!-- **** Fim, Título da página -->

	<!-- Custom fonts for this template-->
	<link href="<?php echo base_url('public/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet"
		  type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		  rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="<?php echo base_url('public/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

	<!-- Carregando o arquivo app.css -->
	<link href="<?php echo base_url('public/css/app.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('public/vendor/select2/select2.min.css'); ?>" rel="stylesheet">

	<!-- *** EFETIVANDO A CLASSE DE RENDERIZAÇÃO DA PESQUISA TABELA -->

	<!-- ***** Se existe/setado (se a variável foi criada) -->
	<?php if (isset($styles)): ?>

		<?php foreach ($styles as $style): ?>

		<!-- ****** Printar todos os arquivos que contém dentro do styles -->
			<link href="<?php echo base_url('public/' . $style); ?>" rel="stylesheet">

		<?php endforeach; ?>

	<?php endif; ?>

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
