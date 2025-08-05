{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

<div id="eg-alert-modal" class="modal fade eg-alert in" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <section class="alert-modal js-alert">
            <div class="card card-block form-card">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">×</span>
                </button>
                <p class="h4">Demande de devis</p>
                <div class="form-section">
                    <div class="msg-success-rejected" style="display: none;">
                        Vous avez déjà effectué une demande de devis pour ce produit !
                    </div>
                    <div class="msg-success" style="display: none;">
                        <div class="msg-success-title">
                            Votre demande de devis à bien été envoyée !
                        </div>
                        <div class="msg-success-subtitle">
                            Nous vous appelons très bientôt. Restez à l'écoute !
                        </div>
                    </div>
                    <form method="post"
                            action=""
                            class="eg-alert-form"
                            id="locadevis-form">
                        <input type="hidden" name="productId" value="{$productId}">
                        <input type="hidden" name="ajax" value="1">
 
                        <div class="form-group">
                            <label>Nom :</label>
                            <input type="text" name="firstname" id="firstname" placeholder="Nom" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Prénom :</label>
                            <input type="text" name="lastname" id="lastname" placeholder="Prénom" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Agence de départ :</label>
                            <select name="departure_agency" id="departure_agency" class="form-control" required>
                                <option value="">Sélectionner une agence</option>
                                <option value="CASA_AEROPORT">CASA AEROPORT</option>
                                <option value="CASA_CITY">CASA CITY</option>
                                <option value="CASABLANCA_RAILWAY">CASABLANCA RAILWAY</option>
                                <option value="MARRAKECH_AEROPORT">MARRAKECH AEROPORT</option>
                                <option value="MARRAKECH_CITY">MARRAKECH CITY</option>
                                <option value="MARRAKECH_RAILWAY">MARRAKECH RAILWAY</option>
                                <option value="TANGER_AEROPORT">TANGER AEROPORT</option>
                                <option value="TANGER_CITY">TANGER CITY</option>
                                <option value="TANGER_RAILWAY">TANGER RAILWAY</option>
                                <option value="AGADIR_AEROPORT">AGADIR AEROPORT</option>
                                <option value="AGADIR_CITY">AGADIR CITY</option>
                                <option value="RABAT_AEROPORT">RABAT AEROPORT</option>
                                <option value="RABAT_CITY">RABAT CITY</option>
                                <option value="RABAT_AGDAL_RAILWAY">RABAT AGDAL RAILWAY</option>
                                <option value="KENITRA_RAILWAY">KENITRA RAILWAY</option>
                                <option value="FEZ_AEROPORT">FEZ AEROPORT</option>
                                <option value="FEZ_CITY">FEZ CITY</option>
                                <option value="FEZ_RAILWAY">FEZ RAILWAY</option>
                                <option value="OUJDA_AEROPORT">OUJDA AEROPORT</option>
                                <option value="NADOR_AEROPORT">NADOR AEROPORT</option>
                                <option value="OUARZAZATE_AEROPORT">OUARZAZATE AEROPORT</option>
                                <option value="OUARZAZATE_CITY">OUARZAZATE CITY</option>
                                <option value="ESSAOUIRA_AIRPORT">ESSAOUIRA AIRPORT</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Agence de retour :</label>
                            <select name="return_agency" id="return_agency" class="form-control" required>
                                <option value="">Sélectionner une agence</option>
                                <option value="CASA_AEROPORT">CASA AEROPORT</option>
                                <option value="CASA_CITY">CASA CITY</option>
                                <option value="CASABLANCA_RAILWAY">CASABLANCA RAILWAY</option>
                                <option value="MARRAKECH_AEROPORT">MARRAKECH AEROPORT</option>
                                <option value="MARRAKECH_CITY">MARRAKECH CITY</option>
                                <option value="MARRAKECH_RAILWAY">MARRAKECH RAILWAY</option>
                                <option value="TANGER_AEROPORT">TANGER AEROPORT</option>
                                <option value="TANGER_CITY">TANGER CITY</option>
                                <option value="TANGER_RAILWAY">TANGER RAILWAY</option>
                                <option value="AGADIR_AEROPORT">AGADIR AEROPORT</option>
                                <option value="AGADIR_CITY">AGADIR CITY</option>
                                <option value="RABAT_AEROPORT">RABAT AEROPORT</option>
                                <option value="RABAT_CITY">RABAT CITY</option>
                                <option value="RABAT_AGDAL_RAILWAY">RABAT AGDAL RAILWAY</option>
                                <option value="KENITRA_RAILWAY">KENITRA RAILWAY</option>
                                <option value="FEZ_AEROPORT">FEZ AEROPORT</option>
                                <option value="FEZ_CITY">FEZ CITY</option>
                                <option value="FEZ_RAILWAY">FEZ RAILWAY</option>
                                <option value="OUJDA_AEROPORT">OUJDA AEROPORT</option>
                                <option value="NADOR_AEROPORT">NADOR AEROPORT</option>
                                <option value="OUARZAZATE_AEROPORT">OUARZAZATE AEROPORT</option>
                                <option value="OUARZAZATE_CITY">OUARZAZATE CITY</option>
                                <option value="ESSAOUIRA_AIRPORT">ESSAOUIRA AIRPORT</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date de départ :</label>
                            <input type="datetime-local" id="departure_date" name="departure_date" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Date de retour :</label>
                            <input type="datetime-local" id="return_date" name="return_date" class="form-control" required="">
                        </div>
                        <div class="form-group eg-email-section product-customization-item">
                            <label>E-mail :</label>
                            <input type="email" id="email" placeholder="Email"
                                name="email" required="" class="form-control product-message">
                        </div>
                        <div class="form-group">
                            <label>Téléphone :</label>
                            <input type="tel" name="phone" id="phone" placeholder="Téléphone" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Sélectionner votre véhicule :</label>
                            <select name="product_choice" id="product_choice" class="form-control" required>
                                <option value="">Sélectionner un véhicule</option>
                                <option value="Clio 4">Renault Clio 4</option>
                                <option value="Picanto">KIA Picanto</option>
                                <option value="Hyundai i10">Hyundai i10</option>
                                <option value="Hyundai i20">Hyundai i20</option>
                                <option value="Opel Corsa">Opel Corsa</option>
                                <option value="Ford Fiesta">Ford Fiesta</option>
                            </select>
                        </div>
                        <div class="submit-btn">
                            <button type="submit" class="btn btn-black">
                                {l s='Envoyer' d='Modules.Locadevis'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

