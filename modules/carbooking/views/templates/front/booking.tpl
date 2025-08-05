<div class="car-reservation">
    <div class="container">
        <div class="car-reservation-content">
            <h2>Réserver votre voiture</h2>
            <form action="{$link->getCategoryLink(1)}" method="post">
                <div class="form-group">
                    <label>Agence de départ :</label>
                    <select name="departure_agency" class="form-control">
                        <option value="CASA AEROPORT">CASA AEROPORT</option>
                        <option value="CASA CITY">CASA CITY</option>
                        <option value="CASABLANCA RAILWAY">CASABLANCA RAILWAY</option>
                        <option value="MARRAKECH AEROPORT">MARRAKECH AEROPORT</option>
                        <option value="MARRAKECH CITY">MARRAKECH CITY</option>
                        <option value="MARRAKECH RAILWAY">MARRAKECH RAILWAY</option>
                        <option value="TANGER AEROPORT">TANGER AEROPORT</option>
                        <option value="TANGER CITY">TANGER CITY</option>
                        <option value="TANGER RAILWAY">TANGER RAILWAY</option>
                        <option value="AGADIR AEROPORT">AGADIR AEROPORT</option>
                        <option value="AGADIR CITY">AGADIR CITY</option>
                        <option value="RABAT AEROPORT">RABAT AEROPORT</option>
                        <option value="RABAT CITY">RABAT CITY</option>
                        <option value="RABAT AGDAL RAILWAY">RABAT AGDAL RAILWAY</option>
                        <option value="KENITRA RAILWAY">KENITRA RAILWAY</option>
                        <option value="FEZ AEROPORT">FEZ AEROPORT</option>
                        <option value="FEZ CITY">FEZ CITY</option>
                        <option value="FEZ RAILWAY">FEZ RAILWAY</option>
                        <option value="OUJDA AEROPORT">OUJDA AEROPORT</option>
                        <option value="NADOR AEROPORT">NADOR AEROPORT</option>
                        <option value="OUARZAZATE AEROPORT">OUARZAZATE AEROPORT</option>
                        <option value="OUARZAZATE CITY">OUARZAZATE CITY</option>
                        <option value="ESSAOUIRA AIRPORT">ESSAOUIRA AIRPORT</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Agence de retour :</label>
                    <select name="return_agency" class="form-control">
                        <option value="CASA AEROPORT">CASA AEROPORT</option>
                        <option value="CASA CITY">CASA CITY</option>
                        <option value="CASABLANCA RAILWAY">CASABLANCA RAILWAY</option>
                        <option value="MARRAKECH AEROPORT">MARRAKECH AEROPORT</option>
                        <option value="MARRAKECH CITY">MARRAKECH CITY</option>
                        <option value="MARRAKECH RAILWAY">MARRAKECH RAILWAY</option>
                        <option value="TANGER AEROPORT">TANGER AEROPORT</option>
                        <option value="TANGER CITY">TANGER CITY</option>
                        <option value="TANGER RAILWAY">TANGER RAILWAY</option>
                        <option value="AGADIR AEROPORT">AGADIR AEROPORT</option>
                        <option value="AGADIR CITY">AGADIR CITY</option>
                        <option value="RABAT AEROPORT">RABAT AEROPORT</option>
                        <option value="RABAT CITY">RABAT CITY</option>
                        <option value="RABAT AGDAL RAILWAY">RABAT AGDAL RAILWAY</option>
                        <option value="KENITRA RAILWAY">KENITRA RAILWAY</option>
                        <option value="FEZ AEROPORT">FEZ AEROPORT</option>
                        <option value="FEZ CITY">FEZ CITY</option>
                        <option value="FEZ RAILWAY">FEZ RAILWAY</option>
                        <option value="OUJDA AEROPORT">OUJDA AEROPORT</option>
                        <option value="NADOR AEROPORT">NADOR AEROPORT</option>
                        <option value="OUARZAZATE AEROPORT">OUARZAZATE AEROPORT</option>
                        <option value="OUARZAZATE CITY">OUARZAZATE CITY</option>
                        <option value="ESSAOUIRA AIRPORT">ESSAOUIRA AIRPORT</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date de départ :</label>
                    <input type="datetime-local" name="departure_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Date de retour :</label>
                    <input type="datetime-local" name="return_date" class="form-control" required>
                </div>
                <div class="form-btn">
                    <a href="{$link->getCategoryLink(1)}" class="btn btn-black">Rechercher</a>
                </div>
            </form>
        </div>
    </div>
</div>
