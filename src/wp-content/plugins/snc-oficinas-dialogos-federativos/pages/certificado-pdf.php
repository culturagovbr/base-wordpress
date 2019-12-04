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
            margin: 160px 55px 0 55px;
            border-top: 1px solid #000000;
        }

        #header h1 {
            font-size: 50px;
            font-style: italic;
            font-family: Arial;
        }

        #info-cert {
            position: relative;
            margin: 25px 55px 0px 55px;
            font-family: Arial;
        }

        #info-cert p {
            text-align: justify;
            font-size: 18px;
        }

        #info-cert #data-cert {
            text-align: right !important;
            float: right !important;
            z-index: 100000;
            font-size: 17px;
        }

        #footer {
            margin-top: -45px;
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

        #secretario {
            margin-top: <?php echo $margin_assinatura; ?>px;
        }

        #ministerio {
            margin-top: <?php echo $margin_assinatura_federal; ?>px;
        }
    </style>
</head>
<body>
<div id="main">
    <div id="header">
        <h1>Certificado</h1>
    </div>
    <div id="info-cert">
        <p><?php echo $texto; ?></p>
        <p id="data-cert"><?php echo $textoData; ?></p>
    </div>
    <div id="footer">
        <div id="secretario">
            <p>
                <img src="<?php echo $assinatura_img; ?>"/>
            </p>
            <p style="text-transform: uppercase;"><?php echo $secretario; ?></p>
            <p><?php echo $orgao; ?></p>
            <p>Governo <?php echo $prefixo; ?> <?php echo $unidade; ?></p>
            <p>&nbsp;</p>
            <p>
                <img src="<?php echo $assinatura_logo; ?>"/>
            </p>
        </div>

        <div id="ministerio">
            <p>
                <img src="<?php echo $assinatura_img_federal; ?>"/>
            </p>
            <p><?php echo $autoridade_federal; ?></p>
            <p><?php echo $cargo_federal; ?></p>
            <p><?php echo $setor_federal; ?></p>
            <p><?php echo $orgao_federal; ?></p>
            <p>&nbsp;</p>
            <p>
                <img src="<?php echo $assinatura_logo_federal; ?>"/>
            </p>
        </div>

    </div>
</div>
</body>
</html>