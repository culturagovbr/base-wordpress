<?php

class SNC_Oficinas_Inscricoes_Shortcode
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-login', array($this, 'snc_minc_registrations_list')); // Login
        }
    }

    public function snc_minc_registrations_list()
    {

        ?>

        <table class="js-sortable-table  inscritos--lista">
            <thead>
            <tr>
                <th>Nome</th>
                <th>UF</th>
                <th>Data</th>
                <th>Local</th>
                <th>Situação</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($candidates)) : ?>

                <?php foreach ($candidates as $pid): ?>
                    <tr class="">
                        <td class="subscription__title">
                            Fulano da silva
                        </td>
                        <td>
                            Acre(AC)
                        </td>
                        <td>
                           15/07/2019 a 15/07/2019
                        </td>
                        <td>
                           Avenidas das formigas
                        </td>
                        <td>
                            Confirmado
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr align="center">
                    <td colspan="6">Nenhum resultado</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }

}