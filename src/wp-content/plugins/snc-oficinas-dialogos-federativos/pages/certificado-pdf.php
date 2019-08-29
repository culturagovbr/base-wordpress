<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Ministério da Cidadania - Oficinas dos Diálogos Federativos</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            border: 0;
        }

        body {
            background: url('<?php echo $url; ?>') no-repeat 0 0;
            background-image-resize: 6;
        }

        #main {
            margin: auto;
            position: center;
            border: 1px solid #000;
            height: 794px;
        }

        #header {
            position: relative;
            text-align: center;
            margin: 170px 55px 0 55px;
            border-top: 1px solid #000000;
        }

        #header h1 {
            font-size: 50px;
            font-style: italic;
            font-family: Arial;
        }

        #info-cert {
            position: relative;
            margin: 35px 55px 35px 55px;
            font-family: Arial;
        }

        #info-cert p {
            text-align: justify;
            font-size: 20px;
        }

        #info-cert #data-cert {
            text-align: right !important;
            float: right !important;
        }

        #footer {
            margin-top: 60px;
        }

        #footer div {
            position: relative;
            float: left;
            width: 50%;
            text-align: center;
        }

        #footer p {
            font-size: 12px;
            font-style: italic;
            font-family: Arial;
            font-weight: bold;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
<div id="main" style="">
    <div id="header">
        <h1>Certificado</h1>
    </div>
    <div id="info-cert">
        <p><?php echo $texto; ?></p>
        <p id="data-cert"><?php echo $textoData; ?></p>
    </div>
    <div id="footer">
        <div id="secretario">
            <p>XXXXXXXXX</p>
            <p>Secretário de Estado da Cultura</p>
            <p>Governo <?php echo $prefixo;?> <?php echo $unidade;?></p>
        </div>
        <div id="ministerio">
            <p>GUSTAVO CARVALHO AMARAL</p>
            <p>Secretário da Diversidade Cultural</p>
            <p>Secretaria Especial da Cultura</p>
            <p>Ministério da Cidadania</p>
        </div>
    </div>
</div>
</body>
</html>