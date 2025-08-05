/**
 * * Copyright since 2007 PrestaShop SA and Contributors
 *  * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *  *
 *  * NOTICE OF LICENSE
 *  *
 *  * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 *  * that is bundled with this package in the file LICENSE.md.
 *  * It is also available through the world-wide-web at this URL:
 *  * https://opensource.org/licenses/AFL-3.0
 *  * If you did not receive a copy of the license and are unable to
 *  * obtain it through the world-wide-web, please send an email
 *  * to license@prestashop.com so we can send you a copy immediately.
 *  *
 *  * DISCLAIMER
 *  *
 *  * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 *  * versions in the future. If you wish to customize PrestaShop for your
 *  * needs please refer to https://devdocs.prestashop.com/ for more information.
 *  *
 *  * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 *  * @copyright Since 2007 PrestaShop SA and Contributors
 *  * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

$(document).ready(function () {
    $('#eg-alert-modal').hide();
    
    let currentPage = window.location.pathname; // Récupère l'URL de la page (sans domaine)
    let storageKey = 'submitted_' + currentPage; // Clé unique par page produit

    $(document).on('click', '#display-alert-stock', function (event) {
        $('#eg-alert-modal').show();
        $('body').addClass('modal-open');
        if (localStorage.getItem(storageKey) !== null) {
            $('.eg-alert-form').remove();
            $('.form-card .h4').hide();
            $('.msg-success-rejected').show();
            $('.msg-success-title').hide();
            $('.msg-success-subtitle').hide();
        }
    });

    $('.eg-alert .close').on('click', function () {
        $('#eg-alert-modal').hide();
        $('body').removeClass('modal-open');
    });

 

    $('#locadevis-form').on('submit', function (event) {
        event.preventDefault(); 
        $.ajax({
            type: 'POST',
            url: ajax_url,
            async: true,
            dataType : "json",
            data: $(this).serialize(),
            headers: {"cache-control": "no-cache"},
            success: function (result) {
                if (result.msg == 'OK') {
                    localStorage.setItem(storageKey, true);
                    $('.alert-modal').addClass('form-card');
                    $('.form-card .h4').hide();
                    $('.msg-success').show();
                    $('.eg-alert-form').remove();
                } else {
                    $('.msg-success').text('Erreur lors de l\'envoi du devis. Merci de réssayer dans un moment.').show();
                }
            }
        });
    });

 
});
